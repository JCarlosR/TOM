<?php

namespace App\Http\Controllers\Creator;

use App\FanPage;
use App\Http\Controllers\Controller;
use App\Promotion;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;

use App\Http\Requests;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Cell_DataType;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FanPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $fanPages = auth()->user()->fanPages;
        return view('panel.fan_pages.index')->with(compact('fanPages'));
    }

    public function show($id, LaravelFacebookSdk $fb)
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

        return view('panel.fan_pages.show')->with(compact('fanPage'));
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
                $sheet->mergeCells('A1:F1');
                $sheet->row(1, ['Relación de participaciones de la promoción #'.$id]);
                $sheet->row(2, ['Folio', 'Nombre del participante', 'Ticket', 'E-mail', '¿Ha ganado?', 'Facebook ID']);

                // Data
                $promotion = Promotion::find($id);
                $participations = $promotion->participations;
                $i = 0;
                foreach ($participations as $participation) {
                    $row = [];
                    $row[0] = $participation->id;
                    $row[1] = $participation->user->name;
                    $row[2] = $participation->ticket;
                    $row[3] = $participation->user->email;
                    $row[4] = $participation->is_winner ? 'Este usuario ha ganado' : 'Este usuario NO ha ganado';
                    // $row[5] = $participation->user->facebook_user_id;
                    $sheet->appendRow($row);

                    $fbId = $participation->user->facebook_user_id;
                    $fbLink = 'https://www.facebook.com/app_scoped_user_id/' . $fbId;
                    $sheet->getCell('F'.($i+3))
                        ->setValueExplicit($fbLink, PHPExcel_Cell_DataType::TYPE_STRING)
                        ->getHyperlink()
                        ->setUrl($fbLink)
                        ->setTooltip('Visitar perfil ' . $fbId);

                    $i += 1;
                }

            });
        })->export('xls');
    }
}
