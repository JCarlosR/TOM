<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Promotion;
use Illuminate\Http\Request;

use App\Http\Requests;
use Intervention\Image\Facades\Image;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function instructions($id)
    {
        $promotion = Promotion::find($id);
        if (! $promotion)
            return redirect('/');

        $promotionId = $id;
        $fanPageFbId = $promotion->fanPage->fan_page_id;

        return view('panel.instructions')->with(compact('promotionId', 'fanPageFbId'));
    }
}
