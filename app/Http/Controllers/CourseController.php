<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\AuditTrail;
use App\Models\Course;
use App\Models\PersonnelCategory;
use App\Models\RegisteredPersonnel;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();
        return view('exams.courses')->with('courses', $courses);
    }

    public function subjects()
    {
        $courses = Course::all();
        $personnelCategories = PersonnelCategory::with('courses')->get();

        // Load courses that are not assigned to each personnel category
        $personnelCategories->each(function ($personnelCategory) {
            $personnelCategory->coursesNotAssigned = Course::whereDoesntHave('personnelCategories', function ($query) use ($personnelCategory) {
                $query->where('personnel_category_id', $personnelCategory->id);
            })->get();
        });

        return view('exams.subjects', compact('courses','personnelCategories'));
    }
    public function registeredSubjects()
    {
        // Check if user has applied before
        $application = Application::where('user_id', auth()->user()->id)->first();
        if ($application == null) {
            return redirect()->route('applications.apply')->with('error', 'You have not applied for any application yet. Please apply first.');
        }

        $registeredPersonnel = RegisteredPersonnel::where('user_id', auth()->user()->id)->first();
        $subjects = PersonnelCategory::find(auth()->user()->personnel_category_id)->courses;
        return view('personnel.subjects', compact('subjects', 'registeredPersonnel'));

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
        $course = new Course();
        $request->validate([
            'name' => 'required|unique:courses|max:255',
            'code' => 'required|unique:courses|max:255',
        ]);

        $data = $request->all();

        $course->name = $data['name'];
        $course->code = $data['code'];

        // Audit created course
        AuditTrail::create([
            'action' => 'created course with name ' . $data['name'],
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
        ]);

        $course->save();

        return redirect()->back()->with('success', 'Subject created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $course = Course::find($id);
        return view('exams.edit_course')->with('course', $course);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $course = Course::find($request->id);
        $request->validate([
            'name' => 'required|max:255|unique:courses,name,' . $course->id,
            'code' => 'required|max:255|unique:courses,code,' . $course->id,
        ]);

        $data = $request->all();

        $course->name = $data['name'];
        $course->code = $data['code'];

        // Audit updated course
        AuditTrail::create([
            'action' => 'updated course from ' . $course->name . ' to ' . $data['name']. ' with code ' . $course->code . ' to ' . $data['code']. ' with id ' . $course->id,
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
        ]);

        $course->save();

        return redirect('exams/subjects')->with('success', 'Subject updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course = Course::find($id);
        // Audit deleted course
        AuditTrail::create([
            'action' => 'deleted course with id ' . $id,
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
        ]);

        $course->delete();

        return redirect()->back()->with('success', 'Subject deleted successfully');
    }

    public function assignSubjectToCategory(Request $request)
    {
        $courseId = $request->course_id;
        $personnelCategoryIds = $request->personnel_category_id;

        if (!empty($personnelCategoryIds)) {
            foreach($personnelCategoryIds as $personnelCategoryId) {
                $personnelCategory = PersonnelCategory::find($personnelCategoryId);
                $personnelCategory->courses()->attach($courseId);

                // Audit assigned course to personnel category
                AuditTrail::create([
                    'action' => 'assigned course with id ' . $courseId . ' to personnel category with id ' . $personnelCategoryId,
                    'user_id' => auth()->user()->id,
                    'username' => auth()->user()->name,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'url' => request()->url(),
                ]);
            }

            return redirect()->back()->with('success', 'Subject assigned successfully');
        }
        else{
            return redirect()->back()->with('error', 'Please select a category');
        }
    }

    public function unassignSubjectFromCategory($course_id, $personnel_category_id)
    {
        $personnelCategory = PersonnelCategory::find($personnel_category_id);
        $personnelCategory->courses()->detach($course_id);
        // Audit unassigned course from personnel category
        AuditTrail::create([
            'action' => 'unassigned course with id ' . $course_id . ' from personnel category with id ' . $personnel_category_id,
            'user_id' => auth()->user()->id,
            'username' => auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
        ]);
        return redirect()->back()->with('success', 'Subject unassigned successfully');
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $applications = Application::where('application_id', 'like', '%' . $search . '%')->get();
        $userid = $applications->pluck('user_id');
        $personnel_category_ids = $applications->pluck('personnel_category_id');
        $courses = collect();

        foreach ($personnel_category_ids as $id) {
            $category = PersonnelCategory::find($id);
            $courses = $courses->concat($category->courses);
        }
//        $courses = PersonnelCategory::find($personnel_category_id)->courses;
        return response()->json($courses);
    }
}
