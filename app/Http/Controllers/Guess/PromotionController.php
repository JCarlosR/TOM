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
        $categories = $this->getFanPageCategories();

        $promotions = Promotion::active()->get();
        $promotions = $this->sortFilterFormatAndPaginate($request, $promotions);

        return view('guess.promotions.index')->with(compact('promotions', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        if (! $query)
            return redirect('/promotions');

        $categories = $this->getFanPageCategories();

        $promotions = Promotion::active()
                        ->where('description', 'like', '%' . $query . '%')
                        ->get();

        $promotions = $this->sortFilterFormatAndPaginate($request, $promotions);
        return view('guess.promotions.index')->with(compact('promotions', 'categories', 'query'));
    }

    public function sortFilterFormatAndPaginate(Request $request, $promotions) {
        $promotions = $promotions->sortByDesc('participations_count');

        $promotions = $promotions->reject(function($promotion) {
            return $promotion->participations_count == 0;
        });

        // Add additional fields
        foreach ($promotions as $promotion) {
            $fanPage = $promotion->fanPage;
            $promotion->description = mb_strimwidth($promotion->description, 0, 77, " ...");
            $promotion->fanPageId = $fanPage->fan_page_id;
            $promotion->fanPageName = $fanPage->name;
            $promotion->fanPageCategory = $fanPage->category;
            $promotion->imagePath = $promotion->image_path;

            unset($promotion->fanPage);
            unset($promotion->fan_page_id);
        }

        // Pagination logic
        return $this->paginate($request, $promotions);
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

    public function getFanPageCategories()
    {
        return DB::table('promotions')
            ->join('fan_pages', 'promotions.fan_page_id', '=', 'fan_pages.id')
            ->join('participations', 'promotions.id', '=', 'participations.promotion_id')
            ->select(DB::raw('fan_pages.category as name, count(1) as count'))
            // ->where('status', '<>', 1)
            ->groupBy('fan_pages.category')
            ->get();
    }
}
