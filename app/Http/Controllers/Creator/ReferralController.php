<?php

namespace App\Http\Controllers\Creator;

use App\FanPage;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Cell_DataType;

class ReferralController extends Controller
{
    public function index()
    {
        $creators = User::where('referred_by', auth()->user()->id)->get();
        return view('panel.referral.index')->with(compact('creators'));
    }

    public function fanPages($id)
    {
        // by user
        $referral = User::findOrFail($id);
        $fan_pages = $referral->fanPages;
        return view('panel.referral.fan-pages')->with(compact('fan_pages', 'referral'));
    }

    public function promotions($id)
    {
        $fan_page = FanPage::findorFail($id);
        $promotions = $fan_page->promotions;
        return view('panel.referral.promotions')->with(compact('fan_page', 'promotions'));
    }

    public function excel()
    {
        Excel::create('Mis referidos', function($excel) {
            $excel->sheet('Datos', function($sheet) {

                // Header
                $sheet->mergeCells('A1:F1');
                $sheet->row(1, ['Datos principales, fan pages y promociones de mis referidos']);
                $sheet->row(2, [
                    'Nombre', 'E-mail', 'Fan pages', 'Registro', 'Participaciones restantes', 'Última participación',
                    'ID Fan page', 'Fan page', 'Categoría',
                    'Promoción', 'Vigencia', 'Ganar cada', 'Participaciones'
                ]);

                // Data
                $creators = User::where('referred_by', auth()->user()->id)->get();

                $i = 0;
                foreach ($creators as $creator) {
                    $row = [];
                    $row[0] = $creator->name;
                    $row[1] = $creator->email;
                    $row[2] = $creator->fanPagesCount;
                    $row[3] = $creator->created_at;
                    $row[4] = $creator->remaining_participations;
                    $row[5] = $creator->updated_at;

                    $fbId = $creator->facebook_user_id;
                    $fbProfile = 'https://www.facebook.com/app_scoped_user_id/' . $fbId;

                    foreach ($creator->fanPages as $fanPage) {
                        $row[6] = $fanPage->fan_page_id;
                        $row[7] = $fanPage->name;
                        $row[8] = $fanPage->category;

                        $promotions = $fanPage->promotions;
                        if (sizeof($promotions) == 0)
                        {
                            $row[9] = '0';
                            $row[10] = '0';
                            $row[11] = '0';
                            $row[12] = '0';

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
                                $row[9] = $promotion->description;
                                $row[10] = $promotion->end_date;
                                $row[11] = $promotion->attempts;
                                $row[12] = $promotion->participations_count;

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
