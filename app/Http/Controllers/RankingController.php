<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MooraService;

class RankingController extends Controller
{
    public function index(MooraService $mooraService)
    {
        $result = $mooraService->calculate();
        return view('ranking.index', compact('result'));
    }
}
