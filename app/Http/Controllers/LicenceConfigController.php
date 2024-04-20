<?php

namespace App\Http\Controllers;

use App\Models\LicenceConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LicenceConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $licenceConfigs = LicenceConfig::all();
        $licenceConfigs = $licenceConfigs->first();
        return view('configs.licence_config', compact('licenceConfigs'));
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
        $licenceConfig = LicenceConfig::first();

        $validatedData = Validator::make($request->all(),[
            'inspector_number' => 'required',
            'dg_name' => 'required',
            'bc_name' => 'required',
            'gd_signature' => 'image|mimes:png|max:2048',
            'bc_signature' => 'image|mimes:png|max:2048',
        ]);

        if($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData)->withInput();
        }

        $inspector_number = $request->inspector_number;
        $dg_name = $request->dg_name;
        $bc_name = $request->bc_name;

          if($request->hasFile('gd_signature')) {
            $gd_signature = $request->file('gd_signature');
            $gd_signature_name = time() . '_' . $gd_signature->getClientOriginalName();
            $gd_signature->move(public_path('images/sigs'), $gd_signature_name);

            $licenceConfig->DG_SIGNATURE = $gd_signature_name;
          }

        if($request->hasFile('bc_signature')) {
            $bc_signature = $request->file('bc_signature');
            $bc_signature_name = time() . '_' . $bc_signature->getClientOriginalName();
            $bc_signature->move(public_path('images/sigs'), $bc_signature_name);

            $licenceConfig->BC_SIGNATURE = $bc_signature_name;
        }

        $licenceConfig->INS = $inspector_number;
        $licenceConfig->DG_NAME = $dg_name;
        $licenceConfig->BC_NAME = $bc_name;
        $licenceConfig->save();

        return redirect()->back()->with('success', 'Licence Configurations Updated Successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(LicenceConfig $licenceConfig)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LicenceConfig $licenceConfig)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LicenceConfig $licenceConfig)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LicenceConfig $licenceConfig)
    {
        //
    }
}
