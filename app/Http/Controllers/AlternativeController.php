<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\Criteria;
use App\Imports\AlternativesImport;
use App\Exports\AlternativesTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class AlternativeController extends Controller
{
    public function index()
    {
        $alternatives = Alternative::with('alternativeValues')->get();
        $criterias = Criteria::all();
        return view('alternatives.index', compact('alternatives', 'criterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required|array',
        ]);

        $alternative = Alternative::create(['name' => $request->name]);

        foreach ($request->values as $criteria_id => $value) {
            AlternativeValue::create([
                'alternative_id' => $alternative->id,
                'criteria_id' => $criteria_id,
                'value' => $value ?? 0,
            ]);
        }

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit(Alternative $alternative)
    {
        $criterias = Criteria::all();
        $alternative->load('alternativeValues');
        return view('alternatives.edit', compact('alternative', 'criterias'));
    }

    public function update(Request $request, Alternative $alternative)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required|array',
        ]);

        $alternative->update(['name' => $request->name]);

        foreach ($request->values as $criteria_id => $value) {
            AlternativeValue::updateOrCreate(
                ['alternative_id' => $alternative->id, 'criteria_id' => $criteria_id],
                ['value' => $value ?? 0]
            );
        }

        return redirect()->route('alternatives.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(Alternative $alternative)
    {
        $alternative->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file.required' => 'Pilih file Excel/CSV terlebih dahulu.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 10 MB.',
        ]);

        $import = new AlternativesImport();
        Excel::import($import, $request->file('file'));

        $msg = "Import berhasil! {$import->importedCount} data berhasil dimasukkan.";
        if ($import->skippedCount > 0) {
            $msg .= " {$import->skippedCount} baris dilewati (nama kosong).";
        }

        return redirect()->route('alternatives.index')->with('success', $msg);
    }

    public function downloadTemplate()
    {
        return Excel::download(new AlternativesTemplateExport(), 'template_alternatif.xlsx');
    }
}
