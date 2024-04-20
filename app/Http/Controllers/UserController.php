<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->orderBy('id', 'asc')->get();
        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
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
            'firstname' => 'required|string|max:255',
            'email' => 'required|unique:users|string|max:255',
            'password' => 'required|string|max:255',
            'role' => 'required|string|max:255',
        ]);

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        if ($lastname === null) {
            $name = $firstname;
        } else {
            $name = $firstname . ' ' . $lastname;
        }
        $email = $request->email;
        $password = $request->password;

        $role = $request->role;

        $user = User::create([
            'first_name' => $firstname,
            'last_name' => $lastname,
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        // if a user is created successfully, assign a role to user
        if ($user) {
            $user->assignRole($role);
            return redirect()->route('users.index')->with('success', 'User created successfully');
        }
        else {
            return redirect()->back()->with('error', 'User not created');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        $user = User::with('roles')->find($user_id);
        return view('users.view', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //display user edit form
        $user = User::find($id);
        return view('users.edit_profile', compact('user'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'email' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'postal_address' => 'required|string|max:255',
            'physical_address' => 'required|string|max:255',
        ]);

        $user = User::find($id);
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->postal_address = $request->postal_address;
        $user->physical_address = $request->physical_address;
        $user->save();

        return redirect()->route('user.account')->with('success', 'Profile updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
