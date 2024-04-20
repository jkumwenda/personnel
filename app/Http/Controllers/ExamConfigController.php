<?php

namespace App\Http\Controllers;

use App\Models\ExamConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $examConfig = ExamConfig::first();
        return view('configs.exam_config', compact('examConfig'));
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
            $examConfig = ExamConfig::firstOrCreate([]);
            $validatedData = Validator::make($request->all(),[
                'northern_region' => 'required|string',
                'central_region' => 'required|string',
                'southern_region' => 'required|string',
            ]);

            if($validatedData->fails()) {
                return redirect()->back()->withErrors($validatedData)->withInput();
            }

            if (!empty($request->northern_region)) {
                $examConfig->northern_region = $request->northern_region;
            }
            if (!empty($request->central_region)) {
                $examConfig->central_region = $request->central_region;
            }
            if (!empty($request->southern_region)) {
                $examConfig->southern_region = $request->southern_region;
            }

            $examConfig->save();
            return redirect()->back()->with('success', 'Exam Configurations updated successfully.');
        }

    /**
     * Display the specified resource.
     */
    public function show(ExamConfig $examConfig)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExamConfig $examConfig)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExamConfig $examConfig)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamConfig $examConfig)
    {
        //
    }
}
