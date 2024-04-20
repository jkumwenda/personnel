<?php

namespace App\Http\Controllers;

use App\Mail\ApplicationMail;
use App\Models\Application;
use App\Models\PersonnelCategory;
use App\Models\Screen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('personnel.applications');
    }
    public function listView()
    {
        $applications = Application::where('application_status', 'pending')->with('personnelCategory')->get();
        return view('applications.index')->with('applications', $applications);
    }
    public function detailView($id)
    {
        $application = Application::find($id);
        $user = User::find($application->user_id);
        return view('applications.screen', compact('application', 'user'));
    }

    public function apply()
    {
        // check if user has applied before
        $application = Application::where('user_id', auth()->user()->id)->first();
        return view('personnel.apply', compact('application'));
    }

    public function list()
    {
        // get list of applications where user id is auth user id and with personnel categories
        $applications = Application::where('user_id', auth()->user()->id)->with('personnelCategory')->get();
        return view('personnel.application_list', compact('applications'));
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
        $request->validate([
            'training' => 'required|string',
            'present_employer' => 'nullable|string',
            'present_employer_address' => 'nullable|string',
            'academic_qualifications.*.acq' => 'required|string',
            'academic_qualifications.*.acd' => 'required|string',
            'academic_qualifications.*.aci' => 'required|string',
            'professional_qualification' => 'nullable|array',
            'relevant_files' => 'nullable|array',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png',
            'proof_of_payment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png',

        ]);

        $personnel_category = PersonnelCategory::find(auth()->user()->personnel_category_id);

        $user_id = auth()->user()->id;

        $academicQualifications = [];
        $professionalQualifications = [];

        foreach ($request->input('acq') as $key => $acq) {
            $academicQualifications[] = [
                'acq' => $acq,
                'acd' => $request->input('acd')[$key],
                'aci' => $request->input('aci')[$key],
            ];
        }

        foreach ($request->input('prc') as $key => $prc) {
            $professionalQualifications[] = [
                'prc' => $prc,
                'prd' => $request->input('prd')[$key],
                'pri' => $request->input('pri')[$key],
            ];
        }

        $data = [
            'user_id' => $user_id,
            'personnel_category_id' => auth()->user()->personnel_category_id,
            'training' => $request->input('training'),
            'present_employer' => $request->input('present_employer'),
            'present_employer_address' => $request->input('present_employer_address'),
            'academic_qualification' => $academicQualifications,
            'professional_qualification' => $professionalQualifications,
            'application_status' => 'pending',
            'application_id' => 'PMRA/LE/'.$personnel_category->code.'/'. date('m') . '/' .date('y') . '/'.$user_id,
        ];

        $filePaths = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('public/uploads/'.$user_id.'/relevant_documents');
                $filePaths[] = $path;
            }
            $data['relevant_files'] = $filePaths;
        }

        // create and save application to database and redirect to application index page
        Application::create($data);
        /*send mail to user
        Mail::to(auth()->user()->email)->send(new ApplicationMail($data['application_id'], auth()->user(), 'application_sent'));*/
        return redirect()->route('applications.list')->with('success', 'Application submitted successfully. You will be notified once your application is reviewed and approved via your email. Thank you for applying.');

    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $application = Application::find($id);
        $user = User::find($application->user_id);
        $comment = Screen::where('application_id', $id)->first()->comment ?? null;
        return view('personnel.application_details', compact('application', 'comment', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $application = Application::find($id);
        return view('personnel.edit_application')->with('application', $application);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user_id = auth()->user()->id;
        $academicQualifications = [];
        $professionalQualifications = [];

        foreach ($request->input('acq') as $key => $acq) {
            $academicQualifications[] = [
                'acq' => $acq,
                'acd' => $request->input('acd')[$key],
                'aci' => $request->input('aci')[$key],
            ];
        }

        foreach ($request->input('prc') as $key => $prc) {
            $professionalQualifications[] = [
                'prc' => $prc,
                'prd' => $request->input('prd')[$key],
                'pri' => $request->input('pri')[$key],
            ];
        }

        $data = [
            'user_id' => $user_id,
            'personnel_category_id' => $request->input('personnel_category_id'),
            'training' => $request->input('training'),
            'present_employer' => $request->input('present_employer'),
            'present_employer_address' => $request->input('present_employer_address'),
            'academic_qualification' => $academicQualifications,
            'professional_qualification' => $professionalQualifications,
            'application_status' => 'pending',
        ];

        $filePaths = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('public/uploads/'.$user_id.'/relevant_documents');
                $filePaths[] = $path;
            }
            $data['relevant_files'] = $filePaths;
        }

        // update
        Screen::where('application_id', $id)->delete();
        Application::where('id', $id)->update($data);
        return redirect()->route('applications.list')->with('success', 'Application updated successfully');
    }

    public function cancelApplication($id)
    {
        $application = Application::find($id);
        $application->application_status = 'withdrawn';
        $application->save();
        return redirect()->back()->with('success', 'Application cancelled successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        //
    }
}
