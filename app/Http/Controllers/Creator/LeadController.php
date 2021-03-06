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

        // by status
        $participations_1 = collect();
        $participations_2 = collect();
        $participations_3 = collect();
        $participations_4 = collect();

        foreach ($participations as $participation) {
            switch ($participation->status) {
                case 'A contactar': $participations_1->push($participation); break;
                case 'En progreso': $participations_2->push($participation); break;
                case 'Con venta': $participations_3->push($participation); break;
                case 'Sin venta': $participations_4->push($participation); break;

                default: $participations_1->push($participation); break;
            }
        }

        return view('panel.leads.index')->with(compact(
            'participations_1', 'participations_2', 'participations_3', 'participations_4'
        ));
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
                    $row[3] = $participation->promotion->fanPage->name;
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
