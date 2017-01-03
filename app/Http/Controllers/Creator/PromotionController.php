<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;

class PromotionController extends Controller
{

    public function index($id)
    {
        return 'Genial, funciona! => ' . $id;
    }

}
