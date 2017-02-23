<?php

namespace App\Http\Controllers\Guess;

use App\Promotion;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{

    public function index(Request $request)
    {
        // SELECT category, COUNT(1) FROM fan_pages GROUP BY category ORDER BY category ASC
        $categories = DB::table('promotions')
            ->join('fan_pages', 'promotions.fan_page_id', '=', 'fan_pages.id')
            ->select(DB::raw('fan_pages.category as name, count(1) as count'))
            // ->where('status', '<>', 1)
            ->groupBy('fan_pages.category')
            ->get();
        // This count represents the number of fan pages in each category
        // Not the number of promotions

        // dd($categories);


        /*$promotions = DB::table('promotions')
            ->join('participations', 'promotions.id', '=', 'participations.promotion_id')
            ->select('promotions.id as id, count(1) as count')
            ->groupBy('promotions.id')
            ->get();*/

        // dd($promotions);

        $promotions = Promotion::active()->get();
        $promotions = $promotions->sortByDesc('participations_count');

        $promotions = $promotions->reject(function($promotion) {
            return $promotion->participations_count == 0;
        });

        // Add additional fields
        foreach ($promotions as $promotion) {
            $fanPage = $promotion->fanPage;
            $promotion->fanPageId = $fanPage->fan_page_id;
            $promotion->fanPageName = $fanPage->name;
            $promotion->fanPageCategory = $fanPage->category;
            $promotion->imagePath = $promotion->image_path;

            unset($promotion->fanPage);
            unset($promotion->fan_page_id);
        }

        // Pagination logic
        $promotions = $this->paginate($request, $promotions);
        // dd($promotions);
        /*foreach ($promotions as $promotion) {
            dd($promotion);
        }*/

        return view('guess.promotions.index')->with(compact('promotions', 'categories'));
    }

    public function paginate(Request $request, $items)
    {
        $page = $request->get('page', 1); // Get the ?page=1 from the url
        $perPage = 15; // Number of items per page
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(
            array_slice($items->toArray(), $offset, $perPage, true), // Only grab the items we need
            count($items), // Total items
            $perPage, // Items per page
            $page, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // We need this so we can keep all old query parameters from the url
        );
    }

}
