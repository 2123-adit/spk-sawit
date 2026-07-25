<?php

namespace App\Exports;

use App\Models\Criteria;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class AlternativesTemplateExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection(): Collection
    {
        // Return 3 example rows so user knows the format
        $criterias = Criteria::orderBy('id')->get();
        $example_values = [
            [10, 0.85, 21.5, 9500000, 4.2, 2800000],
            [15, 0.90, 22.0, 9800000, 4.5, 3000000],
            [8,  0.80, 20.5, 9200000, 3.9, 2600000],
        ];

        $rows = collect();
        foreach ($example_values as $i => $vals) {
            $row = ['Contoh Alternatif ' . ($i + 1)];
            foreach ($criterias as $j => $c) {
                $row[] = $vals[$j] ?? 0;
            }
            $rows->push($row);
        }

        return $rows;
    }

    public function headings(): array
    {
        $criterias = Criteria::orderBy('id')->get();
        $headers = ['nama_alternatif'];
        foreach ($criterias as $c) {
            $headers[] = strtolower($c->code); // c1, c2, c3...
        }
        return $headers;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF3B82F6']],
            ],
        ];
    }
}
