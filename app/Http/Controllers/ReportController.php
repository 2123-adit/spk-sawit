<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\MooraService;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function pdf(MooraService $mooraService)
    {
        $result = $mooraService->calculate();
        
        $pdf = Pdf::loadView('report.pdf', compact('result'));
        
        return $pdf->stream('Laporan-SPK-Harga-Kelapa-Sawit.pdf');
    }
}
