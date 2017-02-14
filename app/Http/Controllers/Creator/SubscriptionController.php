<?php

namespace App\Http\Controllers\Creator;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{


    public function index()
    {
        return view('panel.subscription.index');
    }
}
