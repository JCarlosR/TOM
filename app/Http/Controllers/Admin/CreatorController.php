<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Cell_DataType;

class CreatorController extends Controller
{
    public function index()
    {
        // only users that have at least one fan_page are returned
        $creators = User::has('fanPages')->get();

        return view('admin.creators.index')->with(compact('creators'));
    }

    public function excel()
    {

        Excel::create('Usuarios creadores', function($excel) {
            $excel->sheet('Datos', function($sheet) {

                // Header
                $sheet->mergeCells('A1:F1');
                $sheet->row(1, ['Datos principales, fan pages y promociones de los usuarios creadores']);
                $sheet->row(2, ['Nombre', 'E-mail', 'Fan pages', 'Registro', 'Participaciones restantes', 'Última participación']);

                // Data
                $creators = User::has('fanPages')->get();

                $i = 0;
                foreach ($creators as $creator) {
                    $row = [];
                    $row[0] = $creator->name;
                    $row[1] = $creator->email;
                    $row[2] = $creator->fanPagesCount;
                    $row[3] = $creator->created_at;
                    $row[4] = $creator->remaining_participations;
                    $row[5] = $creator->updated_at;

                    $sheet->appendRow($row);

                    $fbId = $creator->facebook_user_id;
                    $fbLink = 'https://www.facebook.com/app_scoped_user_id/' . $fbId;
                    $sheet->getCell('A'.($i+3))
                        // ->setValueExplicit($fbLink, PHPExcel_Cell_DataType::TYPE_STRING)
                        ->getHyperlink()
                        ->setUrl($fbLink)
                        ->setTooltip('Visitar perfil ' . $fbId);

                    $i += 1;
                }

            });
        })->export('xls');
    }
}
