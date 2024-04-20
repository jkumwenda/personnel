<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('configs.roles')->with('roles', $roles);
    }

    public function showPermissions($id)
    {
        $role = Role::findById($id);
        $permissions = $role->permissions;
        return view('configs.permissions')->with('role', $role)->with('permissions', $permissions);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // get all permissions
        $permissions = Permission::all();
        return view('configs.add_role', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate form
        $request->validate([
            'name' => 'required|unique:roles|max:255',
            'permissions' => 'required',
        ]);


        // create role
        $role = Role::create(['name' => $request->input('name')]);

        $permissionNames = $request->input('permissions');
        $permissionIds = Permission::whereIn('name', $permissionNames)->pluck('id');

        // assign permissions
        $role->syncPermissions($permissionIds);

        // redirect to roles page
        return redirect()->route('configs.roles')->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::findById($id);
        $permissions = Permission::all();
        return view('configs.edit_role', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // validate form
        $request->validate([
            'name' => 'required|max:255|unique:roles,name,' . $id,
            'permissions' => 'required',
        ]);

        // retrieve the role
        $role = Role::findById($id);

        // get permissions from request
        $permissionNames = $request->input('permissions');

        // convert permission names to IDs
        $permissionIds = Permission::whereIn('name', $permissionNames)->pluck('id');

        // sync permissions
        $role->syncPermissions($permissionIds);

        // redirect to roles page
        return redirect()->route('configs.roles')->with('success', 'Role updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
