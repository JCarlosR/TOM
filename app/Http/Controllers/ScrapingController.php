<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use Symfony\Component\DomCrawler\Crawler;

class ScrapingController extends Controller
{
    protected $contacts;

    public function example(Client $client)
    {
        for ($i=1; $i<=19; ++$i) {
            $offset = ($i-1)*20;
            $pageUrl = "http://www.ampidf.com.mx/es/directorioprofesionalesinmobiliarios/?Desplazamiento=$offset";

            $crawler = $client->request('GET', $pageUrl);
            $this->extractContactsFrom($crawler);
        }

        return $this->startDownloadCSV();
    }

    public function startDownloadCSV()
    {
        // create csv file
        $fileName = date('Y-m-d').'.csv'; // 2018-02-04.csv
        $filePath = public_path($fileName);
        $file = fopen($filePath, 'w');

        // write to the file
        /*$contacts = [];
        $contact1['name'] = 'Juan';
        $contact1['email'] = 'hola@programacionymas.com';
        $contacts[] = $contact1;
        $contact2['name'] = 'Carlos';
        $contact2['email'] = 'hola2@programacionymas.com';
        $contacts[] = $contact2;*/
        foreach ($this->contacts as $contact) {
            fputcsv($file, $contact);
        }

        // generate file download
        return response()->download($filePath);
    }

    public function extractContactsFrom(Crawler $crawler)
    {
        $inlineContactStyles = 'background-color: #DDEDC2;background-image: url("/images/BoxNoticia.jpg");background-position: center top; overflow:hidden; height:100%;margin-bottom:10px;';
        $crawler->filter("[style='$inlineContactStyles']")->each(function (Crawler $contactNode) {
            $divs = $contactNode->children()->filter('div');

            $sectionInfo = $divs->eq(0);
            $textInfo = $sectionInfo->text();
            $contact = $this->extractContactInfo($textInfo);

            $sectionPhones = $divs->eq(1);
            $phones = $sectionPhones->text();
            $contact['phones'] = $phones;

            $this->contacts[] = $contact;
        });
    }

    public function extractContactInfo($textInfo)
    {
        $contact = [];
        $parts = explode('Sección: ', $textInfo);
        $contact['name'] = $parts[0];
        $textInfo = $parts[1];
        $parts = explode('Email: ', $textInfo);
        $contact['section'] = $parts[0];
        $textInfo = $parts[1];
        $contact['email'] = substr($textInfo, 0, -19);
        return $contact;
    }
}
