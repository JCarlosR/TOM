<?php

namespace App\Http\Controllers\Api;

use App\Participation;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ParticipationController extends Controller
{
    public function getNotes($id)
    {
        $participation = Participation::find($id)->select('notes')->get();
        return $participation;
    }

    public function updateNotes($id, Request $request)
    {
        $participation = Participation::find($id);
        $participation->notes = $request->input('notes');
        $saved = $participation->save();

        $data['success'] = $saved;
        return $data;
    }

    public function updateStars($id, Request $request)
    {
        $participation = Participation::find($id);
        $participation->stars = $request->input('stars');
        $participation->save();
    }

    public function updateStatus($id, Request $request)
    {
        $participation = Participation::find($id);
        $participation->status = $request->input('status');
        $participation->save();
    }
}
