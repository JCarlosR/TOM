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
        $categories = [
            'Entertainment Website',
            'Website',
            'Local Business',
            'Clothing (Brand)',
            'Movie',
            'Community',
            'Blogger',
            'Brand',
            'Consulting Agency',
            'School Sports Team',
            'Games/Toys',
            'Real Estate',
            'Kitchen/Cooking',
            'Advertising/Marketing Service',
            'Health/Beauty',
            'Personal Blog',
            'Medical Company',
            'Product/Service',
            'Fashion',
            'Local Service',
            'Company',
            'Event Planning Service',
            'Beauty',
            'Professional Service',
            'Public Figure'
        ];

        $promotions = Promotion::all();
        return view('guess.promotions.index')->with(compact('promotions', 'categories'));
    }

}
