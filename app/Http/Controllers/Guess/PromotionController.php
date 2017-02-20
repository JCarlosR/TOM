<?php

namespace App\Http\Controllers\Guess;

use App\Promotion;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PromotionController extends Controller
{

    public function index()
    {
        $promotions = Promotion::all();
        return view('guess.promotions.index')->with(compact('promotions'));
    }

}
