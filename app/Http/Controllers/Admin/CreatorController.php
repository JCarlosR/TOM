<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CreatorController extends Controller
{
    public function index()
    {
        // only users that have at least one fan_page are returned
        $creators = User::has('fanPages')->get();

        return view('admin.creators.index')->with(compact('creators'));
    }
}
