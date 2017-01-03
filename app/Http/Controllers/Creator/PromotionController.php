<?php

namespace App\Http\Controllers\Creator;

use Illuminate\Http\Request;

use App\Http\Requests;

class PromotionController extends Controller
{

    public function index($id)
    {
        return 'Genial, funciona! => ' . $id;
    }

}
