<?php

namespace App\Http\Controllers\Creator;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TutorialController extends Controller
{
    public function index()
    {
        return view('panel.tutorial');
    }

    public function disable()
    {
        $user = auth()->user();
        $user->show_tutorial = false;
        $user->save();

        return redirect('/home');
    }
}
