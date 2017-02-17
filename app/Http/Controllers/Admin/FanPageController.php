<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FanPageController extends Controller
{
    public function index($id)
    {
        // by user
        $creator = User::findOrFail($id);
        $fan_pages = $creator->fanPages;
        return view('admin.fan-pages.index')->with(compact('fan_pages', 'creator'));
    }

    public function logInByUserId($id)
    {
        Auth::loginUsingId($id);
        return redirect('/home');
    }
}
