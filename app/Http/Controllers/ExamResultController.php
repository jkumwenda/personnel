<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamNumber;
use App\Models\ExamResult;
use App\Models\PersonnelCategory;
use App\Models\User;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class ExamResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, $user_id, $exam_id)
    {
        $subject_ids = $request->input('subject_id');
        $grades = $request->input('grade');

        $user_exam_status = "passed";
        for ($i = 0; $i < count($subject_ids); $i++) {
            $examResult = new ExamResult;
            $examResult->user_id = $user_id;
            $examResult->exam_id = $exam_id;
            $examResult->course_id = $subject_ids[$i];
            $examResult->score = $grades[$i];
            if($grades[$i] >= 50) {
                $examResult->status = "pass";
            }
            else {
                $examResult->status = "fail";
                $user_exam_status = "failed";
            }

            $examResult->save();
            // Audit Trail
            AuditTrail::create([
                'action' => 'Entered exam result for user ' . $user_id . ' in exam ' . $exam_id,
                'user_id' => auth()->user()->id,
                'username' => auth()->user()->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->url(),
            ]);


        }

        // Update user exam status
        $user = User::find($user_id);
        $user->exam_status = $user_exam_status;
        $user->save();


        return redirect()->route('exams.upload-view.personnel', $exam_id)->with('success', 'Exam result added successfully');
    }

    /**
     * Bulk upload using PHPOffice Excel
     */
    public function bulkUpload(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,xlsx,xls|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Load the spreadsheet file
        $spreadsheet = IOFactory::load($request->file('file'));

        // Get the active worksheet
        $worksheet = $spreadsheet->getActiveSheet();

        $user_exam_statuses = [];
        $user_ids = [];

        // Iterate over the rows
        $counter = 1;
        foreach ($worksheet->getRowIterator() as $row) {
            // Skip the first row
            if ($counter == 1) {
                $counter++;
                continue;
            }
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

            $row_data = [];
            foreach ($cellIterator as $cell) {
                $row_data[] = $cell->getValue();
            }

            // Assuming the columns are exam_number, course_code, score in order
            $exam_number = $row_data[0];
            $course_code = $row_data[1];
            $score = $row_data[2];

            // Get user_id from exam_number
            $user_id = ExamNumber::where('exam_number', $exam_number)->first();
            if (!$user_id) {
                // Handle user not found
                continue;
            }
            // Initialize the exam status for this user as "passed" if it's not already set to "failed"
            if (!isset($user_exam_statuses[$user_id->user_id]) || $user_exam_statuses[$user_id->user_id] !== "failed") {
                $user_exam_statuses[$user_id->user_id] = "passed";
            }

            // Store user_id for updating user exam status
            if (!in_array($user_id->user_id, $user_ids)) {
                $user_ids[] = $user_id->user_id;
            }

            // Get course_id from course_code
            $course = Course::where('code', $course_code)->first();
            if (!$course) {
                // Handle course not found
                continue;
            }

            // check if all grades are less than 50
            if($score < 50){
                $user_exam_statuses[$user_id->user_id] = "failed";
            }


             // Assuming the columns are user_id, exam_id, course_id, score in order
            $examResult = new ExamResult;
            $examResult->user_id = $user_id->user_id;
            $examResult->exam_id = $user_id->exam_id;
            $examResult->course_id = $course->id;
            $examResult->score = $score;
            $examResult->status = $score >= 50 ? "pass" : "fail";

            $examResult->save();
            // Audit Trail
            AuditTrail::create([
                'action' => 'Bulk uploaded results in the exam with id' .$user_id->exam_id,
                'user_id' => auth()->user()->id,
                'username' => auth()->user()->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->url(),
            ]);


            $counter++;

        }

        // Update user exam status
        foreach ($user_exam_statuses as $user_id => $user_exam_status) {
            $user = User::find($user_id);
            $user->exam_status = $user_exam_status;
            $user->save();
        }

        return back()->with('message', 'Bulk upload successful');
    }
    /**
     * Display the specified resource.
     */
    public function resultsList($exam_id)
    {
        $exam_name = Exam::find($exam_id)->exam_name;
        $examResults = ExamNumber::with('user')->where('exam_id', $exam_id)->get();

        // GET RESULTS FOR EACH USER IN THIS EXAM AND CHECK IF HE PASSED OR NOT. CONDITION IS HE MUST HAVE ALL SCORE >=50 IN THIS EXAM
        foreach ($examResults as $examResult) {
            $results = ExamResult::where('user_id', $examResult->user_id)->where('exam_id', $exam_id)->get();
            $all_passed = true;
            foreach ($results as $result) {
                if ($result->score < 50) {

                    $all_passed = false;
                }
            }
            $examResult->all_passed = $all_passed;
        }


        return view('exams.results_list', compact('exam_name', 'examResults', 'exam_id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user_id, $exam_id)
    {
        $user = User::find($user_id);
        //dd($user);

        if($user->exam_status == 'failed'){
            $exam_number = ExamNumber::where('exam_id', $exam_id)->where('user_id', $user_id)->first();
            $results = ExamResult::where('user_id', $user_id)->where('exam_id', $exam_id)->get();

            dd($results);

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

            $grades = [];
            foreach ($exam_results as $result) {
                $grades[$result->course_id] = $result->score;
            }


            return view('exams.upload_results')->with('subjects', $subjects)->with('exam_number', $exam_number)->with('user', $user);

        }
        else{
            $subjects = PersonnelCategory::find($user->personnel_category_id)->courses;;
            $exam_results = ExamResult::where('exam_id', $exam_id)->where('user_id', $user_id)->get();
            $exam_number = ExamNumber::where('exam_id', $exam_id)->where('user_id', $user_id)->first();

            // Create an associative array with course_id as key and score as value
            $grades = [];
            foreach ($exam_results as $result) {
                $grades[$result->course_id] = $result->score;
            }

            return view('exams.edit_results')
                ->with('subjects', $subjects)
                ->with('grades', $grades)
                ->with('user', $user)
                ->with('exam_number', $exam_number);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id, $exam_id)
    {
        $subject_ids = $request->input('subject_id');
        $grades = $request->input('grade');

        $user_exam_status = "passed";
        for ($i = 0; $i < count($subject_ids); $i++) {
            $examResult = ExamResult::where('user_id', $user_id)->where('exam_id', $exam_id)->where('course_id', $subject_ids[$i])->first();
            $examResult->score = $grades[$i];
            if($grades[$i] >= 50) {
                $examResult->status = "pass";
            }
            else {
                $examResult->status = "fail";
                $user_exam_status = "failed";
            }

            $examResult->save();
        }

        // Update user exam status
        $user = User::find($user_id);
        $user->exam_status = $user_exam_status;
        $user->save();

        return redirect()->route('exams.upload-view.personnel', $exam_id)->with('success', 'Exam result updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamResult $examResult)
    {
        //
    }

    public function myresults()
    {
        $user_id = auth()->user()->id;
        $examCount = ExamNumber::where('user_id', $user_id)->with('exam')->count();

        if ($examCount > 1) {
            // The user has more than one exam number
            $results = ExamResult::where('user_id', $user_id)->get()->groupBy('exam_id');
            $exams = ExamNumber::where('user_id', $user_id)->with('exam')->get();

           $exam_names = [];

            foreach ($exams as $exam) {
                $exam_names[$exam->exam->id] = [
                    'name' => $exam->exam->exam_name,
                    'published' => $exam->exam->published
                ];
            }

            foreach ($results as $exam_id => $groupedResults) {
                foreach ($groupedResults as $result) {
                    $course = Course::find($result->course_id);
                    $result->course_name = $course->name;
                    $result->course_code = $course->code;
                }
                $results[$exam_id] = $groupedResults->sortBy('course_name');
            }

            // Initialize arrays to hold failed subjects and pass status for each exam
            $failed_subjects_per_exam = [];
            $all_passed_per_exam = [];

            // Iterate over each group of results (grouped by `exam_id`)
            foreach ($results as $exam_id => $groupedResults) {
                // Initialize variables for this exam
                $failed_subjects = [];
                $all_passed = true;

                // Iterate over the `ExamResult` records in this group
                foreach ($groupedResults as $result) {
                    // If the score is less than 50, mark as failed and add to failed subjects
                    if ($result->score < 50) {
                        $all_passed = false;
                        $failed_subjects[] = [
                            'name' => $result->course_name,
                            'code' => $result->course_code,
                            'score' => $result->score
                        ];
                    }
                }

                // Store the failed subjects and pass status for this exam
                $failed_subjects_per_exam[$exam_id] = $failed_subjects;
                $all_passed_per_exam[$exam_id] = $all_passed;
            }

            return view('exams.results', compact('results', 'exam_names', 'user_id', 'failed_subjects_per_exam', 'all_passed_per_exam', 'examCount'))->with('exam', []);

        } else {
            // The user has one or no exam number
            $results = ExamResult::where('user_id', $user_id)->get();
            $exam = ExamNumber::where('user_id', $user_id)->with('exam')->first();
            if ($exam == null) {
                return view('exams.results', compact('exam'));
            }

            $exam_name = $exam->exam->exam_name;
            $exam_is_published = $exam->exam->published;

            foreach ($results as $result) {
                $course = Course::find($result->course_id);
                $result->course_name = $course->name;
                $result->course_code = $course->code;
            }
            $results = $results->sortBy('course_name');

            // check if all scores are above 50
            $failed_subjects = [];
            $all_passed = true;
            foreach ($results as $result) {
                if ($result->score < 50) {
                    $all_passed = false;
                    $failed_subjects[] = [
                        'name' => $result->course_name,
                        'code' => $result->course_code,
                        'score' => $result->score
                    ];
                }
            }

            return view('exams.results', compact('results', 'all_passed', 'exam_name', 'exam_is_published', 'user_id', 'failed_subjects', 'examCount', 'exam'));
        }

    }

    public function viewGrades($user_id, $exam_id)
    {
        $username = User::find($user_id)->name;
       $results = ExamResult::where('user_id', $user_id)
            ->where('exam_id', $exam_id)
            ->join('courses', 'exam_results.course_id', '=', 'courses.id')
            ->orderBy('courses.name')
            ->get(['exam_results.*', 'courses.name as course_name', 'courses.code as course_code']);

        $exam = Exam::find($exam_id);
        $exam_name = $exam->exam_name;

        $examNumber = ExamNumber::where('user_id', $user_id)->where('exam_id', $exam_id)->first();
        $exam_number = $examNumber->exam_number;

        $all_passed = true;
        foreach ($results as $result) {
            if ($result->score < 50) {
                $all_passed = false;
            }
        }

        return view('exams.grades', compact('results', 'all_passed', 'exam_name', 'exam_number', 'username'));
    }


}


