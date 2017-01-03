<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Promotion;
use Illuminate\Http\Request;

use App\Http\Requests;

class PromotionController extends Controller
{

    public function show($id)
    {
        $promotion = Promotion::findOrFail($id);
        return view('panel.promotion.edit')->with(compact('promotion'));
    }

}
