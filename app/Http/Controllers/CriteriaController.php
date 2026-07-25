<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Criteria;

class CriteriaController extends Controller
{
    public function index()
    {
        $criterias = Criteria::all();
        return view('criterias.index', compact('criterias'));
    }

    public function update(Request $request, Criteria $criteria)
    {
        $request->validate([
            'weight' => 'required|numeric|min:0|max:1',
            'type'   => 'required|in:benefit,cost',
        ]);

        $criteria->update([
            'weight' => $request->weight,
            'type'   => $request->type,
        ]);

        return redirect()->back()->with('success', 'Bobot kriteria "' . $criteria->name . '" berhasil diperbarui!');
    }
}
