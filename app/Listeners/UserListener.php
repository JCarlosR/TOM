<?php

namespace App\Listeners;

use App\Participation;
use App\User;
use Illuminate\Support\Facades\Mail;

class UserListener
{

    public function created_as_creator(User $user)
    {
        // Send welcome mail to the creator
        $data['user'] = $user;
        Mail::send('emails.user_welcome', $data, function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('Bienvenido a TomboFans');
        });

        $user->welcome_mail_sent = true;
        $user->save();
    }

}