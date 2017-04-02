<?php

namespace App\Http\Controllers\Creator;

use App\FanPage;
use App\Participation;
use App\Promotion;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LeadController extends Controller
{
    public function index()
    {
        $fan_page_ids = FanPage::where('user_id', auth()->user()->id)->pluck('id');
        $promotion_ids = Promotion::whereIn('fan_page_id', $fan_page_ids)->pluck('id');
        $participations = Participation::whereIn('promotion_id', $promotion_ids)->get();
        return view('panel.leads.index')->with(compact('participations'));
    }
}
