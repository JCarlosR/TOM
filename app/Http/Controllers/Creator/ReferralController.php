<?php

namespace App\Http\Controllers\Creator;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReferralController extends Controller
{
    public function index()
    {
        $creators = User::where('referred_by', auth()->user()->id)->get();
        return view('panel.referral.index')->with(compact('creators'));
    }
}
