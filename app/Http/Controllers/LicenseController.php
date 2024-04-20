<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamNumber;
use App\Models\ExamResult;
use App\Models\LicenceConfig;
use App\Models\License;
use App\Models\PersonnelCategory;
use App\Models\User;
use FPDF;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public  function index()
    {
        $users = User::with('examNumber')->where('exam_status', 'passed')->get();

        foreach ($users as $user) {
            $exam = Exam::where('id', $user->examNumber->exam_id)->first();
            $user->exam_name = $exam->exam_name;
        }

         return view('license.provisional', compact('users'));

    }
    public function myLicence($user_id)
    {
        $user = User::find($user_id);
        $license = License::where('user_id', $user_id)->first();
        $personnel_category = PersonnelCategory::find($user->personnel_category_id);

        return view('personnel.licence', compact('user', 'license', 'personnel_category'));
    }

    public function revokeLicense(Request $request)
    {
        $license = License::find($request->licenseId);
        $license->is_revoked = true;
        $license->revoke_reason = $request->reason;
        $license->save();

        echo "success";
    }
    public function reinstateLicense($id)
    {
        $license = License::find($id);
        $license->is_revoked = false;
        $license->revoke_reason = null;
        $license->save();

        echo "success";
    }

    public function originalList()
    {
        // Get all users with licence
       $active_licenses = User::whereHas('license', function ($query) {
            $query->where('is_revoked', false)
                  ->where('expiry_date', '>', now());
        })->get();

        $expired_licenses = User::whereHas('license', function ($query) {
            $query->where('expiry_date', '<', now())
                  ->where('is_revoked', false);
        })->get();

        $revoked_licenses = User::whereHas('license', function ($query) {
            $query->where('is_revoked', true);
        })->get();

        $active_licenses_count = $active_licenses->count();
        $expired_licenses_count = $expired_licenses->count();
        $revoked_licenses_count = $revoked_licenses->count();

        return view('license.original', compact('active_licenses', 'expired_licenses', 'revoked_licenses', 'active_licenses_count', 'expired_licenses_count', 'revoked_licenses_count'));
    }

    public function provisionalLicenseTemplate($user_id): void
    {
        $user = User::find($user_id);
        $licenceConfig = LicenceConfig::first();
        $results = ExamResult::where('user_id', $user_id)->where('score', '>=', 50)->get();
        $exam = ExamNumber::where('user_id', $user_id)->with('exam')->first();
        $exam_name = $exam->exam->exam_name;
        $exam_start_date = $exam->exam->start_date;
        $exam_end_date = $exam->exam->end_date;
        $exam_published_date = $exam->exam->published_date;
        $personnel_category_name = PersonnelCategory::find($user->personnel_category_id)->name;
        $training = Application::where('user_id', $user_id)->first()->training;

        foreach ($results as $result) {
            $course = Course::find($result->course_id);
            $result->course_name = $course->name;
            $result->course_code = $course->code;
        }
        $pdf = new class extends FPDF {
            function Footer()
            {
                // Position at 3.5 cm from bottom
                $this->SetY(-22);
                // Set font
                $this->AddFont('Cambria','','cambria.php');
                $this->SetFont('Cambria','',10);
                // Add line, text, and line
                $this->Cell(50,10,'', 'B', 0, 'C');
                $text = 'All correspondence should be addressed to the Director General';
                $this->Cell(100,20,$text, 0, 0, 'C');
                $this->Cell(40,10,'', 'B', 0, 'C');

                $this->Ln(10);
                $this->AddFont('Cambria','','cambria.php');
                $this->SetFont('Cambria','',8);
                $this->Cell(0,10,'Off Paul kagame / Chilambula Road. P.O Box 30241, CAPITAL CITY, LILONGWE 3, MALAWI. Phone: +265 (0) 1755165',0,1,'C');
                $this->Cell(0,0,'Fax: +265(0) 1755204. Email: info@pmra.mw. Web: www.pmra.mw',0,0,'C');
            }
        };
        $pdf->AddPage();

// Set font
        $pdf->AddFont('Cambria','B','CambriaB.php');
        $pdf->SetFont('Cambria','B',14);

// Add logo
        $logo = 'dist/img/Logo-3.png'; // Make sure this path is correct
        $pdf->Image($logo,10,10,30,0,'PNG');

        $pdf->SetLineWidth(0.3); // Set line width to 0.5
        $pdf->Line(40, 15, 200, 15);

// Add title
        $pdf->setXY(50, 15);
        $pdf->Cell(0,10,'PHARMACY AND MEDICINES REGULATORY AUTHORITY',0,1);

// Add subtitle
        $pdf->AddFont('Cambria', 'I','cambria-italic.php');
        $pdf->SetFont('Cambria','I',14);
        $width = $pdf->GetStringWidth('Quality Medicines for Malawi') + 6;
        $pdf->setX((210 - $width) / 2);
        $pdf->Cell($width,5,'Quality Medicines for Malawi',0,1, 'C');

        // Add bold line below the subtitle
        $pdf->SetLineWidth(0.3); // Set line width to 0.5
        $pdf->Line(40, $pdf->GetY() + 2, 200, $pdf->GetY() + 2);
// Add reference number
        $pdf->AddFont('Cambria','B','CambriaB.php');
        $pdf->SetFont('Cambria','B',13);
        $pdf->setY(40);
        $pdf->Cell(0,10,'Ref No: PMRA/'.strtoupper(substr($user->first_name, 0, 1)).'.'.strtoupper($user->last_name).'/EXAMINATION RESULTS',0,0);
        $y = $pdf->getY();

// Add Original
        $pdf->AddFont('Cambria','','cambria.php');
        $pdf->SetFont('Cambria','',13);
        $pdf->SetY($y + 8); // Set the Y coordinate to the stored value plus 10
        $pdf->Cell(0,10,'Orig. INS'.$licenceConfig->INS,0,1);

        $pdf->Ln(20);

        // add bold line
        $pdf->SetLineWidth(0.5);
        $pdf->Line(10, $y + 20, 200, $y + 20);

// Add date
        $pdf->AddFont('Cambria','','cambria.php');
        $pdf->SetFont('Cambria','',13);
        $pdf->setY($y);
        $pdf->Cell(0,10,date('d F Y', strtotime($exam_published_date)),0,1,'R');

// Add username
        $pdf->AddFont('Cambria','','cambria.php');
        $pdf->SetFont('Cambria','',13);
        $pdf->setY(65);
        $pdf->Cell(0,5,$user->name,0,1);

// Add address
        $pdf->Cell(0,5,$user->postal_address,0,1);
        $pdf->Cell(0,5,'Tel: '.$user->phone_number,0,1);

// Add greeting
        $pdf->ln(2);
        $pdf->Cell(0,10,$user->gender == 'male' ? 'Dear Sir,' : 'Dear Madam,',0,1);

// Add examination results title
        $pdf->AddFont('Cambria','B','CambriaB.php');
        $pdf->SetFont('Cambria','B',13);
        $pdf->Cell(0,10, strtoupper($exam_name).' '.strtoupper($personnel_category_name).' REGISTRATION EXAMINATION RESULTS',0,1,'C');

        //Add Watermark
        // Prepare the watermark image
        $watermark = 'dist/img/Logo-bw.png'; // Make sure this path is correct and the image is faint and in black and white

        // Calculate the position and size of the watermark
        $watermarkWidth = 150; // Adjust this value as needed
        $watermarkHeight = 150; // Adjust this value as needed
        $centerX = (210 - $watermarkWidth) / 2; // Calculate center of the page and subtract half the width of the watermark
        $centerY = 90; // Adjust this value as needed to position the watermark between "This is to certify that" and the signatures

        // Add the watermark to the PDF
        $pdf->Image($watermark, $centerX, $centerY, $watermarkWidth, $watermarkHeight, 'PNG');

// Add examination results text
        $pdf->AddFont('Cambria','','cambria.php');
        $pdf->SetFont('Cambria','',13);
        $pdf->MultiCell(0,7,'This letter serves as a formal communication on your success in registration examination that you undertook on '.date('d', strtotime($exam_published_date)).' and '.date('d F Y', strtotime($exam_end_date)).'. The Pharmacy and Medicines Regulatory Authority (PMRA) wishes to congratulate you on your well deserved success.');
        $pdf->Ln(7);
        $pdf->SetFont('Cambria','B',13);
        $pdf->MultiCell(0,10,'Your detailed results are as follows:',0,1);
        $pdf->AddFont('Cambria','','cambria.php');
        $pdf->SetFont('Cambria','',13);
        foreach ($results as $result) {
            $pdf->Cell(120,10,$result->course_name,0,0,'J');
            $pdf->Cell(50,10,$result->score .'% '.'('.strtoupper($result->status).')',0,1,'C');
        }
        $pdf->Ln(7);

        if($training == "local"){
            $pdf->MultiCell(0,10,'Your registration certificate will be issued in due course. In the meantime you can use this letter to confirm your eligibility to work as a '.$personnel_category_name.'.',0,1);
            $pdf->Ln(7);
        }
        else{
            $pdf->MultiCell(0,10,'Following your success on pre-internship examination, you are expected to do a continuous 12 months internship. You shall be provided with more information regarding the internship programme in due course.',0,1);
            $pdf->Ln(5);
        }

// Add yours faithfully text
        $pdf->AddFont('Cambria','','cambria.php');
        $pdf->SetFont('Cambria','',13);
        $title = 'Yours faithfully,';
        $width = $pdf->GetStringWidth($title) + 6; // Calculate width of the title cell
        $pdf->SetX((210 - $width) / 2); // Set X position to center the title
        $pdf->Cell($width,0,$title,0,1, 'C'); // Add centered title
        $pdf->Ln(7);

// Add signature image
        $signature = 'images/sigs/'.$licenceConfig->DG_SIGNATURE; // Make sure this path is correct
        $signatureWidth = 30; // Adjust this value as needed
        $pdf->SetX((210 - $signatureWidth) / 2); // Set X position to center the signature
        $pdf->Image($signature, $pdf->GetX(), $pdf->GetY(), $signatureWidth, 0, 'PNG');
        $pdf->Ln(7);
        $y = $pdf->GetY(); // Store the current Y coordinate

// Add name
        $pdf->AddFont('Cambria','','cambria.php');
        $pdf->SetFont('Cambria','',13);
        $pdf->SetY($y + 10); // Set the Y coordinate to the stored value plus 15
        $pdf->Cell(0,15,$licenceConfig->DG_NAME,0,1, 'C');

// Add position
        $pdf->AddFont('Cambria','B','CambriaB.php');
        $pdf->SetFont('Cambria','B',13);
        $pdf->SetY($y + 15); // Set the Y coordinate to the stored value plus 25
        $pdf->Cell(0,15,'DIRECTOR GENERAL',0,1, 'C');

        $pdf->Output(dest: 'I', name: 'provisional_license_for_'.$user->name.'.pdf');
    }

    public function originalLicenseTemplate($user_id): void
    {
        // Get user
        $user = User::find($user_id);
        $licenceConfig = LicenceConfig::first();

        // if user exists
        if($user){
            // Get a licence for this user in the license table
            $licence = License::where('user_id', $user_id)->first();

            $pdf = new class extends FPDF {
                function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
                {
                    $k = $this->k;
                    $hp = $this->h;
                    if($style=='F')
                        $op='f';
                    elseif($style=='FD' || $style=='DF')
                        $op='B';
                    else
                        $op='S';
                    $MyArc = 4/3 * (sqrt(2) - 1);
                    $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));

                    $xc = $x+$w-$r;
                    $yc = $y+$r;
                    $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
                    if (!str_contains($corners, '2'))
                        $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k ));
                    else
                        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

                    $xc = $x+$w-$r;
                    $yc = $y+$h-$r;
                    $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
                    if (!str_contains($corners, '3'))
                        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
                    else
                        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

                    $xc = $x+$r;
                    $yc = $y+$h-$r;
                    $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
                    if (!str_contains($corners, '4'))
                        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
                    else
                        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

                    $xc = $x+$r ;
                    $yc = $y+$r;
                    $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
                    if (!str_contains($corners, '1'))
                    {
                        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
                        $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k ));
                    }
                    else
                        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
                    $this->_out($op);
                }

                function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
                {
                    $h = $this->h;
                    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
                        $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
                }
            };

            $pdf->AddPage();

//Add Watermark
            // Prepare the watermark image
            $watermark = 'dist/img/Logo-bw.png';

            // Calculate the position and size of the watermark
            $watermarkWidth = 130;
            $watermarkHeight = 130;
            $centerX = (210 - $watermarkWidth) / 2;
            $centerY = 99;

            // Add the watermark to the PDF
            $pdf->Image($watermark, $centerX, $centerY, $watermarkWidth, $watermarkHeight, 'PNG');

            if($licence->is_revoked){
                $watermark1 = 'dist/img/revoked.png';

                // Calculate the position and size of the watermark
                $watermarkWidth1 = 200;
                $watermarkHeight1 = 200;
                $centerX1 = ($watermarkWidth1) / 30;
                $centerY1 = 75;

                // Add the watermark to the PDF
                $pdf->Image($watermark1, $centerX1, $centerY1, $watermarkWidth1, $watermarkHeight1, 'PNG');
            }

            if($licence->expiry_date < now()){
                $watermark2 = 'dist/img/expired.png';

                // Calculate the position and size of the watermark
                $watermarkWidth2 = 120;
                $watermarkHeight2 = 120;
                $centerX2 = ($watermarkWidth2) - 75;
                $centerY2 = 100;

                // Add the watermark to the PDF
                $pdf->Image($watermark2, $centerX2, $centerY2, $watermarkWidth2, $watermarkHeight2, 'PNG');
            }


// Set font
            $pdf->AddFont('Cambria', 'B', 'CambriaB.php');
            $pdf->SetFont('Cambria', 'B', 12);

// Add logo
            $logo = 'dist/img/Logo-3.png';
            $logoWidth = 40;
            $centerX = (210 - $logoWidth) / 2;
            $pdf->Image($logo, $centerX, 10, $logoWidth, 0, 'PNG');

// Add text below logo
            $pdf->AddFont('Cambria', 'BI','Cambria Bold Italic.php');
            $pdf->SetFont('Cambria','BI',14);
            $pdf->SetY(10 + $logoWidth);
            $pdf->Cell(0, 10, 'Quality Medicines for Malawi', 0, 1, 'C');

// Add "PRACTISING LICENCE" text
            $pdf->SetFont('Cambria', 'B', 25);
            $text = 'PRACTISING LICENCE';
            $cellWidth = $pdf->GetStringWidth($text) + 6;
            $cellHeight = 10;
            $centerX = (210 - $cellWidth) / 2;
            $centerY = $pdf->GetY() + 10;
            $pdf->SetXY($centerX, $centerY);

            $pdf->SetLineWidth(0.6);
            $pdf->RoundedRect($centerX, $centerY, $cellWidth, $cellHeight, 4, '1234');
            $pdf->SetXY($centerX, $centerY); // Reset X and Y position
            $pdf->MultiCell($cellWidth, $cellHeight, $text, 0, 'C');

            // Add two horizontal lines connecting to the box side by side
            $lineLength = 45;
            $lineSpacing = 2;

            $pdf->SetLineWidth(0.8);
            $pdf->Line($centerX - $lineLength, $centerY + $cellHeight / 2, $centerX, $centerY + $cellHeight / 2); // Add line on the left
            $pdf->Line($centerX + $cellWidth, $centerY + $cellHeight / 2, $centerX + $cellWidth + $lineLength, $centerY + $cellHeight / 2); // Add line on the right

// Add "This is to certify that" text
            $pdf->AddFont('Cambria','','cambria.php');
            $pdf->SetFont('Cambria', '', 14);
            $text = 'This is to certify that';
            $cellWidth = $pdf->GetStringWidth($text) + 6;
            $cellHeight = 10;
            $centerX = (210 - $cellWidth) / 2;
            $centerY = $pdf->GetY() + 10;
            $pdf->SetXY($centerX, $centerY); // Set X and Y position
            $pdf->Cell($cellWidth, $cellHeight, $text, 0, 1, 'C');

// Add "Personnel Name and Registration Number" text
            $pdf->SetFont('Cambria', 'B', 18); // Set font to Cambria Bold 14
            $text = strtoupper($user->name) . "\nREGISTRATION No. $licence->registration_number"; // Text to be added with a line break
            $cellWidth = $pdf->GetStringWidth($text) + 6; // Calculate width of the cell
            $cellHeight = 10; // Height of the cell
            $centerX = (210 - $cellWidth) / 2; // Calculate center of the page and subtract half the width of the cell
            $centerY = $pdf->GetY() + 10; // Get current Y position and add 10 for spacing
            $pdf->SetXY($centerX, $centerY); // Set X and Y position
            $pdf->MultiCell($cellWidth, $cellHeight, $text, 0, 'C');

// Add "is authorised to be practising as a" text
            $pdf->AddFont('Cambria','','cambria.php');
            $pdf->SetFont('Cambria', '', 14); // Set font to Cambria Regular 14
            $text = 'is authorised to be practising as a'; // Text to be added
            $cellWidth = $pdf->GetStringWidth($text) + 6; // Calculate width of the cell
            $cellHeight = 10; // Height of the cell
            $centerX = (210 - $cellWidth) / 2; // Calculate center of the page and subtract half the width of the cell
            $centerY = $pdf->GetY() + 10; // Get current Y position and add 10 for spacing
            $pdf->SetXY($centerX, $centerY); // Set X and Y position
            $pdf->Cell($cellWidth, $cellHeight, $text, 0, 1, 'C'); // Add centered text

// Add "Personnel Category" text
            $pdf->SetFont('Cambria', 'B', 20); // Set font to Cambria Bold 14
            $text = strtoupper($user->PersonnelCategory->name); // Text to be added
            $cellWidth = $pdf->GetStringWidth($text) + 6; // Calculate width of the cell
            $cellHeight = 10; // Height of the cell
            $centerX = (210 - $cellWidth) / 2; // Calculate center of the page and subtract half the width of the cell
            $centerY = $pdf->GetY() + 10; // Get current Y position and add 10 for spacing
            $pdf->SetXY($centerX, $centerY); // Set X and Y position
            $pdf->Cell($cellWidth, $cellHeight, $text, 0, 1, 'C'); // Add centered text
            // Add space at the bottom
            $spaceHeight = 10; // Adjust this value as needed
            $pdf->Ln($spaceHeight);

// Add "Effective Date"
            $pdf->SetFont('Cambria', '', 14); // Set font to Cambria Regular 14
            $label = 'Effective Date:     '; // Label to be added
            $date = date("j F, Y", strtotime($licence->effective_date));// Date to be added
            $cellWidth = $pdf->GetStringWidth($label . $date) + 6; // Calculate width of the cell
            $cellHeight = 10; // Height of the cell
            $centerX = (210 - $cellWidth) / 2; // Calculate center of the page and subtract half the width of the cell
            $centerY = $pdf->GetY(); // Get current Y position and add 5 for spacing
            $pdf->SetXY($centerX, $centerY); // Set X and Y position
            $pdf->Cell($pdf->GetStringWidth($label), $cellHeight, $label, 0, 0, 'C'); // Add centered label

            $pdf->SetFont('Cambria', 'B', 14); // Set font to Cambria Bold Underlined 14
            $pdf->Cell($pdf->GetStringWidth($date), $cellHeight, $date, 0, 1, 'C'); // Add centered date

// Add "Expiry Date"
            $pdf->SetFont('Cambria', '', 14); // Set font to Cambria Regular 14
            $label = 'Expiry Date:    '; // Label to be added
            $date = date("j F, Y", strtotime($licence->expiry_date)); // Date to be added
            $cellWidth = $pdf->GetStringWidth($label . $date) + 6; // Calculate width of the cell
            $centerX = (210 - $cellWidth) / 2; // Calculate center of the page and subtract half the width of the cell
            $centerY = $pdf->GetY(); // Get current Y position and add 5 for spacing
            $pdf->SetXY($centerX, $centerY); // Set X and Y position
            $pdf->Cell($pdf->GetStringWidth($label), $cellHeight, $label, 0, 0, 'C'); // Add centered label

            $pdf->SetFont('Cambria', 'B', 14); // Set font to Cambria Bold Underlined 14
            $pdf->Cell($pdf->GetStringWidth($date), $cellHeight, $date, 0, 1, 'C'); // Add centered date

// Set initial Y position
            $initialY = $pdf->GetY() + 30; // Adjust this value as needed

// Add Director General's signature
            $signatureDG = 'images/sigs/'.$licenceConfig->DG_SIGNATURE; // Make sure this path is correct
            $signatureWidth = 40; // Adjust this value as needed
            $centerX = (210 - $signatureWidth) / 2 - 60; // Calculate center of the page and subtract half the width of the signature and adjust for spacing
            $pdf->SetXY($centerX, $initialY); // Set X and Y position to center the signature
            $pdf->Image($signatureDG, $pdf->GetX(), $pdf->GetY(), $signatureWidth, 0, 'PNG');

            // Add underline below Director General's signature
            $pdf->SetY($pdf->GetY() + 20); // Move Y position below the signature
            $pdf->SetX($centerX); // Set X position to center the underline
            $pdf->Cell($signatureWidth, 0, '', 'T', 0, 'C'); // Add centered underline

            // Add Director General's name
            $pdf->SetFont('Cambria', 'B', 16); // Set font to Cambria Bold 14
            $pdf->SetY($pdf->GetY() + 5); // Move Y position below the underline
            $pdf->SetX($centerX); // Set X position to align with the signature
            $pdf->Cell($signatureWidth, 0, $licenceConfig->DG_NAME, 0, 1, 'C'); // Add centered name

            // Add Director General's position
            $pdf->SetFont('Cambria', 'B', 16); // Set font to Cambria Regular 12
            $pdf->SetY($pdf->GetY() + 5); // Move Y position below the name
            $pdf->SetX($centerX); // Set X position to align with the signature
            $pdf->Cell($signatureWidth, 0, 'DIRECTOR GENERAL', 0, 1, 'C'); // Add centered position

// Add Qr Code
            $qr_code = public_path($licence->qr_code);
            $qr_codeWidth = 40;
            $centerX = (210 - $qr_codeWidth) / 2;
            $pdf->SetXY($centerX, $initialY);
            $pdf->Image($qr_code, $pdf->GetX(), $pdf->GetY(), $qr_codeWidth, 0, 'PNG');

// Add Board Chairperson's signature
            $signatureBC = 'images/sigs/'.$licenceConfig->BC_SIGNATURE;
            $signatureWidth = 40; // Adjust this value as needed
            $centerX = (210 - $signatureWidth) / 2 + 60;
            $pdf->SetXY($centerX, $initialY);
            $pdf->Image($signatureBC, $pdf->GetX(), $pdf->GetY(), $signatureWidth, 0, 'PNG');

            // Add underline below Board Chairperson's signature
            $pdf->SetY($pdf->GetY() + 20); // Move Y position below the signature
            $pdf->SetX($centerX); // Set X position to center the underline
            $pdf->Cell($signatureWidth, 0, '', 'T', 0, 'C'); // Add centered underline

            // Add Board Chairperson's name
            $pdf->SetFont('Cambria', 'B', 16); // Set font to Cambria Bold 14
            $pdf->SetY($pdf->GetY() + 5); // Move Y position below the underline
            $pdf->SetX($centerX); // Set X position to align with the signature
            $pdf->Cell($signatureWidth, 0, $licenceConfig->BC_NAME, 0, 1, 'C'); // Add centered name

            // Add Board Chairperson's position
            $pdf->SetFont('Cambria', 'B', 16); // Set font to Cambria Regular 12
            $pdf->SetY($pdf->GetY() + 5); // Move Y position below the name
            $pdf->SetX($centerX); // Set X position to align with the signature
            $pdf->Cell($signatureWidth, 0, 'CHAIRPERSON', 0, 1, 'C');

            // Add new page
            $pdf->AddPage();

            //  Add Special conditions of the licence in the center of the new page
            $pdf->SetFont('Cambria', 'B', 12);
            $text = 'SPECIAL CONDITIONS OF THE LICENSE';
            $cellWidth = $pdf->GetStringWidth($text) + 6;
            $centerX = 15;
            $centerY = 70;
            $pdf->SetXY($centerX, $centerY);
            $pdf->Cell($cellWidth, $cellHeight, $text, 0, 1, 'L');

            // add line space
            $pdf->Ln(2);
            $cellHeight = 6;

            // Add Special conditions of the licence
            $pdf->SetFont('Cambria', '', 10);
            $text = 'By issuance of this practising licence, the '.$user->PersonnelCategory->name.' commits to adhere to the following conditions;';
            $cellWidth = 180;
            $centerX = 15;
            $pdf->SetX($centerX);
            $pdf->MultiCell($cellWidth, $cellHeight, $text, 0, 'L');
            $pdf->Ln(3);

            // add list of conditions
            $pdf->SetFont('Cambria', '', 10);
            $text = '1. That you will pay annual retention fees by 31st March.';
            $cellWidth = 180;
            $centerX = 15;
            $pdf->SetX($centerX);
            $pdf->MultiCell($cellWidth, $cellHeight, $text, 0, 'L');
            $pdf->Ln(3);

            $text = '2. That you will inform the Board every time you change jobs as licenses for pharmaceutical premises are issued on the condition that there is a registered pharmacy personnel.';
            $cellWidth = 180;
            $centerX = 15;
            $pdf->SetX($centerX);
            $pdf->MultiCell($cellWidth, $cellHeight, $text, 0, 'L');
            $pdf->Ln(3);

            $text = '3. That you will always have this practising license when discharging your professional duties as '.$user->PersonnelCategory->name.'.';
            $cellWidth = 180;
            $centerX = 15;
            $pdf->SetX($centerX);
            $pdf->MultiCell($cellWidth, $cellHeight, $text, 0, 'L');
            $pdf->Ln(3);

            $text = '4. That you will always wear a white coat every time you are on duty.';
            $cellWidth = 180;
            $centerX = 15;
            $pdf->SetX($centerX);
            $pdf->MultiCell($cellWidth, $cellHeight, $text, 0, 'L');
            $pdf->Ln(3);

            // Add acceptance statement
            $text = 'I hereby undertake a commitment to practice pharmacy in line with the conditions above and any relevant requirements as stipulated in the PMRA Act No. 9 of 2019, Regulations and Code of Ethics for Pharmacy Personnel.';
            $cellWidth = 180;
            $centerX = 15;
            $pdf->SetX($centerX);
            $pdf->MultiCell($cellWidth, $cellHeight, $text, 0, 'L');
            $pdf->Ln(10);


            $text = 'I accept that practicing pharmacy in contravention of these conditions is an offence.';
            $cellWidth = 180; // Set cell width to a fixed value less than the page width
            $centerX = 15;
            $pdf->SetX($centerX);
            $pdf->MultiCell($cellWidth, $cellHeight, $text, 0, 'L');

            $pdf->Output(dest: 'I', name: 'practising_license_for_'.$user->name.'.pdf');
        }
        else{
            die('Licence does not exist');
        }

    }
}

