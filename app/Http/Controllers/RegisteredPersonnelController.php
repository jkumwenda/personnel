<?php

namespace App\Http\Controllers;

use App\Mail\ExamMail;
use App\Models\Application;
use App\Models\Exam;
use App\Models\ExamNumber;
use App\Models\ExamResult;
use App\Models\PersonnelCategory;
use App\Models\RegisteredPersonnel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisteredPersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index($user_id)
{
    if(auth()->user()->exam_status !== "failed") {
        // check if user has applied before
        $application = Application::where('user_id', auth()->user()->id)->first();
        if ($application == null) {
            return redirect()->route('applications.apply')->with('error', 'You have not applied for any application yet. Please apply first.');
        }

        $registeredPersonnel = RegisteredPersonnel::where('user_id', $user_id)->first();

        $application_is_approved = Application::where('user_id', $user_id)->first()->application_status;

        if ($registeredPersonnel == null) {
            return redirect()->route('applications.list')->with('error', 'Your application is still under review. Please wait for approval to register for exam.');
        }

        $exam = Exam::find($registeredPersonnel->exam_id)->latest()->first();
        $subjects = PersonnelCategory::find(auth()->user()->personnel_category_id)->courses;

        // Get the exam number
        $exam_number = ExamNumber::where('user_id', auth()->user()->id)->where('exam_id', $registeredPersonnel->exam_id)->first();

        return view('personnel.register_exam', compact('registeredPersonnel', 'application_is_approved', 'exam', 'subjects', 'exam_number'));
    }
    else {
        // check if user has applied before
        $application = Application::where('user_id', auth()->user()->id)->first();
        if ($application == null) {
            return redirect()->route('applications.apply')->with('error', 'You have not applied for any application yet. Please apply first.');
        }
        $registeredPersonnel = RegisteredPersonnel::where('user_id', $user_id)->latest()->first();
        $exam = Exam::where('id', $registeredPersonnel->exam_id)->latest()->first();
        $results = ExamResult::where('user_id', $user_id)->get();
        $failed_courses = [];
        foreach ($results as $result) {
            if ($result->score < 50) {
                $failed_courses[] = $result->course_id; // assuming course_id represents the subject
            }
        }

        $subjects = PersonnelCategory::find(auth()->user()->personnel_category_id)
            ->courses()
            ->whereIn('courses.id', $failed_courses)
            ->get();

        $application_is_approved = Application::where('user_id', $user_id)->first()->application_status;

        // Get the exam number
        $exam_number = ExamNumber::where('user_id', auth()->user()->id)->where('exam_id', $registeredPersonnel->exam_id)->first();

        return view('personnel.register_exam', compact('registeredPersonnel', 'application_is_approved', 'exam', 'subjects', 'exam_number'));
    }
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
        $user_id = $request->user_id;
        $exam_id = $request->exam_id;

        $registeredPersonnel = RegisteredPersonnel::where('user_id', $user_id)->where('exam_id', $exam_id)->first();
        $registeredPersonnel->update([
            'is_registered' => 1, // 1 means 'registered'
        ]);

        // check if exam number exists for the given user and exam id
        $exam_number = ExamNumber::where('user_id', $user_id)->where('exam_id', $exam_id)->first();
        if (!$exam_number) {
            // Get the last exam number for the given personnel category and exam id
            $last_exam_number = ExamNumber::where('personnel_category_id', auth()->user()->personnel_category_id)->where('exam_id', $exam_id)->max('sequential_number');

            // Increment the sequential number
            $sequential_number = $last_exam_number + 1;
            $formatted_sequential_number = str_pad($sequential_number, 3, '0', STR_PAD_LEFT);

            $personnel_category_code = PersonnelCategory::where('id', auth()->user()->personnel_category_id)->first()->code;

            $exam_number = 'PMRA/' .$personnel_category_code.'/' . $exam_id . '/' . $formatted_sequential_number;
            ExamNumber::create([
                'exam_number' => $exam_number,
                'user_id' => $user_id,
                'exam_id' => $exam_id,
                'personnel_category_id' => auth()->user()->personnel_category_id,
                'sequential_number' => $sequential_number
            ]);

            // Send email to applicant
            $user = User::find($user_id);
            $exam = Exam::find($exam_id);

           // Mail::to($user->email)->send(new ExamMail($user, $exam, $exam_number, 'exam_register'));
        }
        return redirect()->route('home')->with('success', 'Exam registration successful. Your exam number is ' .$exam_number. '. Please Keep this number, you will use it when writing your exams. An email has been sent to you with exam details.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RegisteredPersonnel $registeredPersonnel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegisteredPersonnel $registeredPersonnel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegisteredPersonnel $registeredPersonnel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegisteredPersonnel $registeredPersonnel)
    {
        //
    }
}
