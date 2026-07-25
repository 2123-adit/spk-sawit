<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\MooraService;

class CalculationController extends Controller
{
    public function index(MooraService $mooraService)
    {
        $result = $mooraService->calculate();
        
        return view('calculation.index', compact('result'));
    }
}
