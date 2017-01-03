<?php

namespace App\Http\Controllers;

use App\FanPage;
use App\Promotion;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;

use App\Http\Requests;
use Maatwebsite\Excel\Facades\Excel;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FanPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id, LaravelFacebookSdk $fb)
    {
        $fanPage = FanPage::find($id);
        if (! $fanPage)
            return redirect('/config')->with('warning', 'No se ha encontrado la página buscada !');;

        if ($fanPage->user_id != auth()->user()->id)
            return redirect('/config')->with('warning', 'Evite acciones de este tipo !');

        if (! $fanPage->picture_200) {
            $query = '/'.$fanPage->fan_page_id.'/picture?redirect=false&width=200&height=200';

            $token = session('fb_user_access_token');
            $fb->setDefaultAccessToken($token);
            try {
                $response = $fb->get($query);
            } catch (FacebookSDKException $e) {
                die($e->getMessage());
            }
            $graphNode = $response->getGraphNode();
            $fanPage->picture_200 = $graphNode->getField('url');
            $fanPage->save();
        }

        return view('panel.fan_page')->with(compact('fanPage'));
    }

    public function promotions($id)
    {
        $fanPage = FanPage::find($id);
        $promotions = $fanPage->promotions;
        return view('panel.promotion.index')->with(compact('fanPage', 'promotions'));
    }

    public function excel($id)
    {

        Excel::create('Participaciones', function($excel) use ($id) {
            $excel->sheet('Datos', function($sheet) use ($id) {

                // Header
                $sheet->mergeCells('A1:D1');
                $sheet->row(1, ['Relación de participaciones de la promoción #'.$id]);
                $sheet->row(2, ['Folio', 'Nombre del participante', 'Ticket', 'E-mail', '¿Ha ganado?']);

                // Data
                $promotion = Promotion::find($id);
                $participations = $promotion->participations;
                foreach ($participations as $participation) {
                    $row = [];
                    $row[0] = $participation->id;
                    $row[1] = $participation->user->name;
                    $row[2] = $participation->ticket;
                    $row[3] = $participation->user->email;
                    $row[4] = $participation->is_winner ? 'Este usuario ha ganado' : 'Este usuario NO ha ganado';
                    $sheet->appendRow($row);
                }

            });
        })->export('xls');
    }
}
