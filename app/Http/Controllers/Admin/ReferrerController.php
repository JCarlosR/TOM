<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReferrerController extends Controller
{
    public function index()
    {
        // The welcome mail is sent just to creators (participants receives other types of mails)
        $referrers = User::where('welcome_mail_sent', true)
            ->where('id', '<>', 1) // i'm the first user
            ->get();

        // Reject referrers with no referrals
        $referrers = $referrers->reject(function($referrer) {
            return $referrer->referrals_count == 0;
        });

        return view('admin.referrers.index')->with(compact('referrers'));
    }

    public function show($id)
    {
        $referrer = User::find($id);
        $referrals = User::where('referred_by', $referrer->id)->get();
        return view('admin.referrers.show')->with(compact('referrals'));
    }

}
