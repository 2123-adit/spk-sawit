<?php

namespace App\Imports;

use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\Criteria;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class AlternativesImport implements ToCollection, WithHeadingRow
{
    private $criterias;
    public int $importedCount = 0;
    public int $skippedCount = 0;

    public function __construct()
    {
        // Load all criterias once for performance
        $this->criterias = Criteria::all();
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip if name is empty
            if (empty($row['nama_alternatif'])) {
                $this->skippedCount++;
                continue;
            }

            // Create or find the alternative by name
            $alternative = Alternative::firstOrCreate(
                ['name' => $row['nama_alternatif']]
            );

            // Insert values for each criteria
            foreach ($this->criterias as $criteria) {
                $colKey = strtolower($criteria->code); // e.g., 'c1', 'c2'
                $value = $row[$colKey] ?? 0;

                AlternativeValue::updateOrCreate(
                    [
                        'alternative_id' => $alternative->id,
                        'criteria_id'    => $criteria->id,
                    ],
                    ['value' => is_numeric($value) ? $value : 0]
                );
            }

            $this->importedCount++;
        }
    }
}
