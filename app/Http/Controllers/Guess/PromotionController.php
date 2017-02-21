<?php

namespace App\Http\Controllers\Guess;

use App\Promotion;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{

    public function index()
    {
        // SELECT category, COUNT(1) FROM fan_pages GROUP BY category ORDER BY category ASC
        $categories = DB::table('promotions')
            ->join('fan_pages', 'promotions.fan_page_id', '=', 'fan_pages.id')
            ->select(DB::raw('fan_pages.category as name, count(1) as count'))
            // ->where('status', '<>', 1)
            ->groupBy('fan_pages.category')
            ->get();

        // dd($categories);

        // This count represents the number of fan pages in each category
        // Not the number of promotions

        /*$promotions = DB::table('promotions')
            ->join('participations', 'promotions.id', '=', 'participations.promotion_id')
            ->select('promotions.id as id, count(1) as count')
            ->groupBy('promotions.id')
            ->get();*/

        // dd($promotions);

        $promotions = Promotion::all();
        $promotions->sortByDesc('participations_count');
        
        return view('guess.promotions.index')->with(compact('promotions', 'categories'));
    }

}
