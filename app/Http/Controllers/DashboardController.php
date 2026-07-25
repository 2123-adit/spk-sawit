<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Services\MooraService;

class DashboardController extends Controller
{
    public function index(MooraService $mooraService)
    {
        $criteriaCount = Criteria::count();
        $alternativeCount = Alternative::count();
        
        $result = $mooraService->calculate();
        $top5 = [];
        $bestAlternative = null;

        if ($result && isset($result['preferences'])) {
            $bestAlternative = $result['preferences']->first()['alternative']->name;
            $top5 = $result['preferences']->take(5);
        }

        return view('dashboard', compact('criteriaCount', 'alternativeCount', 'top5', 'bestAlternative', 'result'));
    }
}
