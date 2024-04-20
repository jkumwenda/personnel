<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function index()
    {
        $user = User::with('roles')->find(auth()->user()->id);
        return view('users.account', compact('user'));
    }

    /**
     * Show the form for changing the user's password.
     *
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function showChangePasswordForm()
    {
        $user = User::find(auth()->user()->id);
        return view('auth.passwords.change_password', compact('user'));
    }

    /**
     * Change the user's password.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) use ($request) {
                    if (Hash::check($value, auth()->user()->password)) {
                        $fail(__('The new password must be different from the current password.'));
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'The current password is incorrect.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Log the user out
        Auth::logout();
        return redirect()->route('login')->with('success', 'Password changed successfully. Log in with your new password.');
    }

    /**
     * Update the user's profile image.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = User::find(auth()->user()->id);
        $profileImage = $request->file('profile_image');
        $profileImageName = time() . '.' . $profileImage->extension();
        $profileImage->move(public_path('images/profile'), $profileImageName);
        $user->profile_image = $profileImageName;
        $user->save();

        return redirect()->back()->with('success', 'Profile image updated successfully.');
    }
    public function updateProfileImageAdmin(Request $request)
    {
        $messages = [
            'profile_image.mimes' => 'The profile image must be a file of type: jpeg, png, jpg, gif, svg.',
            'profile_image.max' => 'The profile image may not be greater than 2048 kilobytes.',
        ];

        $validator = Validator::make($request->all(), [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $profileImage = $request->file('profile_image');
        if ($profileImage->getError() != 0) {
            $errorCode = $profileImage->getError();
            $errorMessage = $this->getUploadErrorMessage($errorCode);
            return redirect()->back()->with('error', $errorMessage);
        }

        $user = User::find($request->user_id);
        $profileImageName = time() . '.' . $profileImage->extension();
        $profileImage->move(public_path('images/profile'), $profileImageName);
        $user->profile_image = $profileImageName;
        $user->save();

        return redirect()->back()->with('success', 'Profile image updated successfully.');
    }
    private function getUploadErrorMessage($errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the maximum file size of 2MB.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
            default => 'Unknown upload error.',
        };
    }

}
