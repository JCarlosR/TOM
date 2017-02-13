<?php

namespace App\Http\Controllers\Admin;

use App\FanPage;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PromotionController extends Controller
{
    public function index($id)
    {
        $fan_page = FanPage::findorFail($id);
        $promotions = $fan_page->promotions;
        return view('admin.promotions.index')->with(compact('fan_page', 'promotions'));
    }
}
