<?php

namespace App\Http\Controllers;

use App\Mail\ApplicationMail;
use App\Models\Application;
use App\Models\AuditTrail;
use App\Models\Exam;
use App\Models\ExamNumber;
use App\Models\PersonnelCategory;
use App\Models\RegisteredPersonnel;
use App\Models\Screen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ScreenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = Application::where('application_status', 'in-review')->get();
        return view('applications.approvals')->with('applications', $applications);
    }
    // get all applications with approved status
    public function approvedList()
    {
        $applications = Application::where('application_status', 'approved')->get();
        return view('applications.approved')->with('applications', $applications);
    }
    // get all applications with rejected status
    public function rejectedList()
    {
        $applications = Application::where('application_status', 'rejected')
            ->leftJoin('screens', 'applications.id', '=', 'screens.application_id')
            ->select('applications.*', 'screens.comment as screen_comment')
            ->get();

        return view('applications.rejected')->with('applications', $applications);
    }

    public function approveApplication($id)
    {
        $exam = Exam::where('is_open', true)->where('published', false)->latest()->first();
        if($exam == null) {
            return redirect()->back()->with('error', 'You cannot approve application without an open exam');
        }

        $exam_id = $exam->id;
        Application::where('id', $id)->update(['application_status' => 'approved']);

        // Audit approved application
        AuditTrail::create([
            'action' => 'approved application with id ' . $id,
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
        ]);

        Screen::where('application_id', $id)->update(['status' => 1]);
        $user_id = Application::where('id', $id)->first()->user_id;

        // Set user for registration
        $is_personnel_registered = RegisteredPersonnel::where('user_id', $user_id)->first();
        if (!$is_personnel_registered) {
            RegisteredPersonnel::create([
                'user_id' => $user_id,
                'exam_id' => $exam_id,
                'is_registered' => 0, // 0 means 'not registered yet'
            ]);
        }

        // Send email to applicant
        $user = User::find($user_id);
        $application_id = Application::where('id', $id)->first()->application_id;
        //Mail::to($user->email)->send(new ApplicationMail($application_id, $user, 'application_approved'));

        return redirect()->back()->with('success', 'Application approved');
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
        $status = $request->status;
        $comment = $request->comment ?? null;
        $application_id = $request->application_id;
        $screened_by = auth()->user()->id;

        if ($status == 1) {
            // update application status
            Application::where('id', $application_id)->update(['application_status' => 'in-review']);
            // Audit approved application
            AuditTrail::create([
                'action' => 'sent application for approval with id ' . $application_id,
                'user_id' => auth()->user()->id,
                'username' => auth()->user()->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->url(),
            ]);
        } else{
            // TODO: Send email to applicant

            // update application status
            Application::where('id', $application_id)->update(['application_status' => 'rejected']);
            // Audit rejected application
            AuditTrail::create([
                'action' => 'rejected application with id ' . $application_id,
                'user_id' => auth()->user()->id,
                'username' => auth()->user()->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->url(),
            ]);
            // Send email to applicant
            $user_id = Application::where('id', $application_id)->first()->user_id;
            $user = User::find($user_id);
            $app_id= Application::where('id', $application_id)->first()->application_id;
            //Mail::to($user->email)->send(new ApplicationMail($app_id, $user, 'application_rejected'));

        }

        $data = [
            'status' => $status,
            'comment' => $comment,
            'application_id' => $application_id,
            'screened_by' => $screened_by
        ];

        // Check if application has been screened before
        $screen = Screen::where('application_id', $application_id)->first();
        if ($screen) {
            $screen->update($data);
            // Audit
            AuditTrail::create([
                'action' => 'updated screen for application with id ' . $application_id,
                'user_id' => auth()->user()->id,
                'username' => auth()->user()->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->url(),
            ]);
            return redirect()->back()->with('success', 'Application updated');
        }
        else{
            Screen::create($data);
            // Audit
            AuditTrail::create([
                'action' => 'screened application with id ' . $application_id,
                'user_id' => auth()->user()->id,
                'username' => auth()->user()->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->url(),
            ]);
            if ($status == 1) {
                return redirect()->back()->with('success', 'Application sent for approval');
            }
            else{
                return redirect()->back()->with('success', 'Application rejected');
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Screen $screen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Screen $screen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Screen $screen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Application::where('id', $id)->update(['application_status' => 'pending']);
        Screen::where('application_id', $id)->delete();
        return redirect()->back()->with('success', 'Application rolled back');
    }
}
