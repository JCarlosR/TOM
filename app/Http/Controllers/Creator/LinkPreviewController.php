<?php

namespace App\Http\Controllers\Creator;

use Dusterio\LinkPreview\Client;
use Dusterio\LinkPreview\Exceptions\UnknownParserException;
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
        $previewClient = new Client($link);

        // Get previews from all available parsers
        // $previews = $previewClient->getPreviews();

        // Get a preview from specific parser
        try {
            $preview = $previewClient->getPreview('general');
            return $preview->toArray();
        } catch (UnknownParserException $e) {
            die($e->getMessage());
        }
    }
}
