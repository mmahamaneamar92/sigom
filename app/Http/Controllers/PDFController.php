<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{


public function generatePDF()
{
    $data = [
        'title' => 'Titre du PDF',
        'content' => 'Contenu du PDF',
    ];

    $pdf = Pdf::loadView('nom-de-votre-vue', $data);

    return $pdf->download('nom-du-fichier.pdf');
}

}
