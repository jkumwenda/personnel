<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Exam;
use App\Models\ExamNumber;
use App\Models\RegisteredPersonnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if(auth()->user()->hasRole('personnel')){
            // check if user has applied before
            $application = Application::where('user_id', auth()->user()->id)->first();
            if ($application == null) {
                return view('home');
            }
            else{
//                $userApplication = auth()->user()->application()->first();
//                $status = $userApplication->application_status;
                $registeredPersonnel = RegisteredPersonnel::where('user_id', auth()->user()->id)->latest()->first();
                $exam = Exam::where('id', $registeredPersonnel->exam_id)->latest()->first();
                $exam_number = ExamNumber::where('user_id', auth()->user()->id)->where('exam_id', $registeredPersonnel->exam_id)->first();
                return view('home', compact('exam', 'exam_number'));
            }
            return view('home');
        }
        return view('home');
    }
}
