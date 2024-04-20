<?php

namespace App\Http\Controllers;

use App\Mail\ExamMail;
use App\Models\AuditTrail;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamNumber;
use App\Models\ExamResult;
use App\Models\License;
use App\Models\PersonnelCategory;
use App\Models\RegisteredPersonnel;
use App\Models\User;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpParser\Node\Scalar\String_;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exams = Exam::all()->sortByDesc('created_at');
        return view('exams.index', compact('exams'));
    }

    public function uploadView()
    {
        $exams = Exam::where('is_open', true)->get();
        return view('exams.upload')->with('exams', $exams);
    }
   public function uploadViewPersonnel($id)
    {
        $exam = Exam::find($id);
        $exam_numbers = ExamNumber::with('user')->where('exam_id', $id)->get();

        // Check if each user has exam results
        $users_has_results = [];
        foreach ($exam_numbers as $exam_number) {
            $users_has_results[$exam_number->user->id] = ExamResult::where('user_id', $exam_number->user->id)->where('exam_id', $id)->exists();
        }
        return view('exams.personnel_view')
            ->with('exam_numbers', $exam_numbers)
            ->with('exam_id', $id)
            ->with('users_has_results', $users_has_results)
            ->with('exam', $exam); // pass the exam object to the view
    }
    public function uploadViewPersonnelResults($exam_id, $user_id)
    {
        $user = User::find($user_id);

        if($user->exam_status == 'failed'){
            $exam_number = ExamNumber::where('exam_id', $exam_id)->where('user_id', $user_id)->first();
            $results = ExamResult::where('user_id', $user_id)->get();

            $failed_courses = [];
            foreach ($results as $result) {
                if ($result->score < 50) {
                    $failed_courses[] = $result->course_id; // assuming course_id represents the subject
                }
            }

            $subjects = PersonnelCategory::find($user->personnel_category_id)
                ->courses()
                ->whereIn('courses.id', $failed_courses)
                ->get();

            return view('exams.upload_results')->with('subjects', $subjects)->with('exam_number', $exam_number)->with('user', $user);
        }
        else{
            $subjects = PersonnelCategory::find($user->personnel_category_id)->courses;
            $exam_number = ExamNumber::where('exam_id', $exam_id)->where('user_id', $user_id)->first();
            return view('exams.upload_results')->with('subjects', $subjects)->with('exam_number', $exam_number)->with('user', $user);
        }
    }
    public function template($exam_id)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Get exam id of the latest exam which is open, dg_approved false, bc_approved false, sent_for_approval false and published false
//        $exam_id = Exam::where('is_open', true)->where('dg_approved', false)->where('bc_approved', false)->where('send_for_approval', false)->where('published', false)->latest()->first()->id;

        // Set the headers
        $sheet->setCellValue('A1', 'Exam Number');
        $sheet->setCellValue('B1', 'Course Code');
        $sheet->setCellValue('C1', 'Score');

        // Fetch the data
        $examNumbers = ExamNumber::where('exam_id', $exam_id)->with('user.personnelCategory.courses')->get();

        // Start from the second row as the first row is the header
        $row = 2;

        // Loop through the exam numbers
        foreach ($examNumbers as $examNumber) {
            $user = $examNumber->user;
            $previousResults = ExamResult::where('user_id', $user->id)->get();

            if ($previousResults->isEmpty()) {
                // No previous results, get all courses
                foreach ($user->personnelCategory->courses as $course) {
                    $sheet->setCellValue('A' . $row, $examNumber->exam_number);
                    $sheet->setCellValue('B' . $row, $course->code);
                    $row++;
                }
            } else {
                // There are previous results, get only the failed courses
                $failedCourses = $previousResults->filter(function ($result) {
                    return $result->score < 50;
                })->pluck('course_id')->toArray();

                foreach ($user->personnelCategory->courses as $course) {
                    if (in_array($course->id, $failedCourses)) {
                        $sheet->setCellValue('A' . $row, $examNumber->exam_number);
                        $sheet->setCellValue('B' . $row, $course->code);
                        $row++;
                    }
                }
            }
        }

        // Start output buffering
        ob_start();
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();

        // audit download of exam template
        AuditTrail::create([
            'action' => 'downloaded exam template',
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
        ]);

        // Return a response with a specific content type
        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment;filename="exam_template.xlsx"');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $exam = new Exam();
        $request->validate([
            'exam_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'is_open' => 'required|boolean',
        ]);

        $data = $request->all();

        $exam->exam_name = $data['exam_name'];
        $exam->start_date = $data['start_date'];
        $exam->end_date = $data['end_date'];
        $exam->is_open = $data['is_open'];
        $exam->save();

        // Audit created exam
        AuditTrail::create([
            'action' => 'created exam with name ' . $data['exam_name'],
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
        ]);

        // Get the recently created exam
        $exam = Exam::latest()->first();

        // Get all users who have exam_status failed
        $users = User::where('exam_status', 'failed')->get();

        // Set these users for registration
        foreach ($users as $user) {
            // Check if records already exist
            $registeredPersonnel = RegisteredPersonnel::where('user_id', $user->id)->where('exam_id', $exam->id)->first();
            if ($registeredPersonnel == null) {
                $registeredPersonnel = new RegisteredPersonnel();
                $registeredPersonnel->user_id = $user->id;
                $registeredPersonnel->exam_id = $exam->id;
                $registeredPersonnel->is_registered = 0; // 0 means 'not registered'
                $registeredPersonnel->save();
            }
        }

        return redirect()->route('exams.list')->with('success', 'Exam created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $exam = Exam::find($id);
        return view('exams.edit_exam')->with('exam', $exam);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $exam = Exam::find($id);
        $request->validate([
            'exam_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'is_open' => 'required|boolean',
        ]);

        $data = $request->all();

        $exam->exam_name = $data['exam_name'];
        $exam->start_date = $data['start_date'];
        $exam->end_date = $data['end_date'];
        $exam->is_open = $data['is_open'];

        // Audit updated exam
        AuditTrail::create([
            'action' => 'updated exam from ' . $exam->exam_name . ' to ' . $data['exam_name']. ' with id ' . $id,
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
        ]);

        $exam->save();

        return redirect()->route('exams.list')->with('success', 'Exam updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $exam = Exam::find($id);
        $exam->delete();
        // audit deleted exam
        AuditTrail::create([
            'action' => 'deleted exam with id ' . $id,
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
        ]);
        return redirect()->back()->with('success', 'Exam deleted successfully');
    }

    public function sendForApproval($id){
        $exam = Exam::find($id);
        $exam->send_for_approval = true;
        $exam->is_open = false;
        $exam->save();

        // Audit finalized exam
        AuditTrail::create([
            'action' => 'Sent exam with id ' . $id . ' for approval',
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
        ]);

        //Send am email to all users with role DG
        $users = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'DG');
        })->get();

       /* foreach ($users as $user) {
            Mail::to($user->email)->send(new ExamMail($user, $exam, '', 'exam_approval'));
        }*/

        return redirect()->back()->with('success', 'License exams has been sent for approval');
    }

    public function finalize($id){
        $exam = Exam::find($id);
        $exam->dg_approved = true;
        $exam->save();

        // Audit finalized exam
        AuditTrail::create([
            'action' => 'DG approved exam with id ' . $id,
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
        ]);

        //Send am email to all users with role BC
        $users = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'BC');
        })->get();

       /* foreach ($users as $user) {
            Mail::to($user->email)->send(new ExamMail($user, $exam, '', 'exam_dg_approve'));
        }*/

        return redirect()->back()->with('success', 'License exams approved successfully');
    }
    /**
     * Publish the specified exam.
     */
    public function publish($id){

        $exam = Exam::find($id);
        $exam->bc_approved = true;
        $exam->published = true;
        $exam->published_date = now();
        $exam->save();

         //Audit published exam
        AuditTrail::create([
            'action' => 'BC approved and published exam with id ' . $id,
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
        ]);

        //Get all users from User Model who are pharmacy assistants or pharmacy technicians and have exam numbers for this Exam
        $users = User::where('exam_status', 'passed')
            ->whereIn('personnel_category_id', [1, 2, 3])
            ->whereHas('examNumber', function ($query) use ($id) {
                $query->where('exam_id', $id);
            })
            ->get();

        foreach ($users as $user) {

            // Get the last record for the License model
            $last_license = License::orderBy('id', 'desc')->first();

            // Increment the id
            $license_id = is_null($last_license) ? 1 : $last_license->id + 1;

            // Format the id to have leading zeros
            $formatted_license_id = str_pad($license_id, 4, '0', STR_PAD_LEFT);


            // Get the personnel category code
            $personnel_category_code = PersonnelCategory::where('id', $user->personnel_category_id)->first()->code;

            // Generate the registration number
            $registration_number = 'PMRA/' .$personnel_category_code.'/' . $formatted_license_id;

            $url = route('license.original', ['user_id' => $user->id]);

            $writer = new PngWriter();
            $qrCode = QrCode::create($url)
                ->setEncoding(new Encoding('UTF-8'))
                ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
                ->setSize(300)
                ->setMargin(10)
                ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
                ->setForegroundColor(new Color(0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255));

            $result = $writer->write($qrCode);
            $directory = public_path('qr_codes/');

            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            $result->saveToFile(public_path('qr_codes/'.$user->id.'.png'));
            $dataUri = $result->getDataUri();
            $license = new License();
            $license->user_id = $user->id;
            $license->registration_number = $registration_number;
            $license->effective_date = now();
            $license->expiry_date = now()->addYear()->startOfYear()->addMonths(2)->endOfMonth();
            $license->is_revoked = false;
            $license->qr_code = '/qr_codes/'.$user->id.'.png';
            $license->data_url = $dataUri;
            $license->save();
        }

        // close an exam
        $exam->is_open = false;
        $exam->save();

        // Send email and or SMS to all users who have exam numbers for this exam
        $all_users = User::whereIn('personnel_category_id', [1, 2, 3])
            ->whereHas('examNumber', function ($query) use ($id) {
                $query->where('exam_id', $id);
            })
            ->get();
       /* foreach ($all_users as $user) {
            Mail::to($user->email)->send(new ExamMail($user, $exam, '', 'exam_publish'));
        }*/

        return redirect()->back()->with('success', 'Exam published successfully');
    }
}
