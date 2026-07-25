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
                $rawValue = $row[$colKey] ?? 0;

                // Auto-convert range like "10-20" to midpoint (15)
                $value = $this->parseValue($rawValue);

                AlternativeValue::updateOrCreate(
                    [
                        'alternative_id' => $alternative->id,
                        'criteria_id'    => $criteria->id,
                    ],
                    ['value' => $value]
                );
            }

            $this->importedCount++;
        }
    }

    /**
     * Parse a value that may be a range (e.g., "10-20") or a plain number.
     * Ranges are converted to their midpoint: (min + max) / 2.
     */
    private function parseValue($raw): float
    {
        $str = trim((string) $raw);

        // Remove thousands separator dots and replace comma decimals
        $str = str_replace('.', '', $str);
        $str = str_replace(',', '.', $str);

        // Detect range format: "10-20" or "10 - 20"
        if (preg_match('/^(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)$/', $str, $matches)) {
            $min = (float) $matches[1];
            $max = (float) $matches[2];
            return ($min + $max) / 2;
        }

        return is_numeric($str) ? (float) $str : 0;
    }
}
