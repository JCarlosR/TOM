<?php

namespace App\Listeners;

use App\Participation;
use App\User;
use Illuminate\Support\Facades\Mail;

class UserListener
{

    public function created(User $user)
    {
        // Send welcome mail
        $data['user'] = $user;
        Mail::send('emails.user_welcome', $data, function ($m) use ($user) {
            $m->from('hola@tombofans.com', 'TomboFans');
            $m->to($user->email, $user->name)->subject('Bienvenido a TomboFans');
        });
    }

}