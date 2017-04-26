<?php

namespace App\Http\Controllers\Creator;

use App\FanPage;
use App\Participation;
use App\Promotion;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class LeadController extends Controller
{
    public function getParticipationsByCreatorId($id)
    {
        $fan_page_ids = FanPage::where('user_id', $id)->pluck('id');
        $promotion_ids = Promotion::whereIn('fan_page_id', $fan_page_ids)->pluck('id');
        return Participation::whereIn('promotion_id', $promotion_ids)->get();
    }

    public function index()
    {
        $participations = $this->getParticipationsByCreatorId(auth()->user()->id);
        return view('panel.leads.index')->with(compact('participations'));
    }

    public function excel()
    {
        $participations = $this->getParticipationsByCreatorId(auth()->user()->id);

        Excel::create('Mis potenciales clientes', function ($excel) use ($participations) {
            $excel->sheet('Datos', function ($sheet) use ($participations) {

                // Header
                $sheet->mergeCells('A1:J1');
                $sheet->row(1, ['Datos principales de mis potenciales clientes']);
                $sheet->row(2, [
                    'Nombre', 'E-mail', 'Ubicación', 'Fan pages',
                    'Promoción', 'Resultado', 'Fecha',
                    'Calificación', 'Notas', 'Status'
                ]);

                foreach ($participations as $key => $participation) {
                    $row = [];
                    $row[0] = $participation->user->name;
                    $row[1] = $participation->user->email;
                    $row[2] = $participation->user->location_name;
                    $row[3] = 'Visitar';
                    $row[4] = $participation->promotion->description;
                    $row[5] = $participation->is_winner ? 'Ganó' : 'Perdió';
                    $row[6] = $participation->created_at;
                    $row[7] = $participation->stars . ' estrellas';
                    $row[8] = $participation->notes;
                    $row[9] = $participation->status;

                    $sheet->appendRow($row);

                    // Fan page link
                    $fanPageLink = 'https://www.fb.com/' . $participation->promotion->fanPage->fan_page_id;
                    $sheet->getCell('D'.($key+3))
                        ->getHyperlink()->setUrl($fanPageLink);

                    // Promotion link
                    $sheet->getCell('E'.($key+3))
                        ->getHyperlink()->setUrl($participation->promotion->fullLink);
                }

            });
        })->export('xls');
    }
}
