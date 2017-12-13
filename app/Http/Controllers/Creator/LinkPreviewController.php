<?php

namespace App\Http\Controllers\Creator;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LinkPreviewController extends Controller
{

    public function fetch(Request $request)
    {
        $text = $request->input('text');
        if (!$text) // empty (?)
            return null;

        $link = firstLink($text);
        if (!$link)
            return null;

        // fetch

    }
}
