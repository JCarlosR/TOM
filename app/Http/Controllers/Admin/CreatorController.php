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
        $creators = User::where('welcome_mail_sent', true)->get(); // User::has('fanPages')->get();

        return view('admin.creators.index')->with(compact('creators'));
    }

    public function excel()
    {

        Excel::create('Usuarios creadores', function($excel) {
            $excel->sheet('Datos', function($sheet) {

                // Header
                $sheet->mergeCells('A1:F1');
                $sheet->row(1, ['Datos principales, fan pages y promociones de los usuarios creadores']);
                $sheet->row(2, [
                    'Nombre', 'E-mail', 'Fan pages', 'Registro', 'Créditos', 'Última participación', 'Publicaciones realizadas',
                    'ID Fan page', 'Fan page', 'Categoría',
                    'Promoción', 'Vigencia', 'Ganar cada', 'Participaciones'
                ]);

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
                    $row[6] = $creator->scheduledPosts()->whereStatus('Enviado')->count();

                    $fbId = $creator->facebook_user_id;
                    $fbProfile = 'https://www.facebook.com/app_scoped_user_id/' . $fbId;

                    foreach ($creator->fanPages as $fanPage) {
                        $row[7] = $fanPage->fan_page_id;
                        $row[8] = $fanPage->name;
                        $row[9] = $fanPage->category;

                        $promotions = $fanPage->promotions;
                        if (sizeof($promotions) == 0)
                        {
                            $row[10] = '0';
                            $row[11] = '0';
                            $row[12] = '0';
                            $row[13] = '0';

                            $sheet->appendRow($row);

                            // Creator fb profile
                            $sheet->getCell('A'.($i+3))
                                // ->setValueExplicit($fbProfile, PHPExcel_Cell_DataType::TYPE_STRING)
                                ->getHyperlink()->setUrl($fbProfile);

                            // Fan page link
                            $fanPageLink = 'https://www.facebook.com/' . $fanPage->fan_page_id;
                            $sheet->getCell('G'.($i+3))
                                ->setValueExplicit($fanPageLink, PHPExcel_Cell_DataType::TYPE_STRING)
                                ->getHyperlink()->setUrl($fanPageLink);

                            $i += 1;
                        } else {
                            foreach ($fanPage->promotions as $promotion) {
                                $row[10] = $promotion->description;
                                $row[11] = $promotion->end_date;
                                $row[12] = $promotion->attempts;
                                $row[13] = $promotion->participations_count;

                                $sheet->appendRow($row);

                                // Creator fb profile
                                $sheet->getCell('A'.($i+3))
                                    // ->setValueExplicit($fbProfile, PHPExcel_Cell_DataType::TYPE_STRING)
                                    ->getHyperlink()->setUrl($fbProfile);

                                // Fan page link
                                $fanPageLink = 'https://www.facebook.com/' . $fanPage->fan_page_id;
                                $sheet->getCell('G'.($i+3))
                                    ->setValueExplicit($fanPageLink, PHPExcel_Cell_DataType::TYPE_STRING)
                                    ->getHyperlink()->setUrl($fanPageLink);

                                // Promotion link
                                $promotionLink = url('promotion/'.$promotion->id);
                                $sheet->getCell('J'.($i+3))
                                    // ->setValueExplicit($promotionLink, PHPExcel_Cell_DataType::TYPE_STRING)
                                    ->getHyperlink()->setUrl($promotionLink);

                                $i += 1;
                            }
                        }

                    }

                }

            });
        })->export('xls');
    }
}
