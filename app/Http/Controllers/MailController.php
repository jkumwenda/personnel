<?php

namespace App\Http\Controllers;

use App\Mail\SignUp;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail(User $user)
    {
        Mail::to($user->email)->send(new SignUp($user));
    }
}
