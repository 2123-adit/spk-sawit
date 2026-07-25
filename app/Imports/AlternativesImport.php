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
        // Track names already used to handle duplicates
        $nameCount = [];

        foreach ($rows as $row) {
            // Skip if name is empty
            if (empty($row['nama_alternatif'])) {
                $this->skippedCount++;
                continue;
            }

            $baseName = trim($row['nama_alternatif']);

            // Make name unique: if "3 Tahun" appears more than once,
            // name them "3 Tahun", "3 Tahun (2)", "3 Tahun (3)", etc.
            if (!isset($nameCount[$baseName])) {
                $nameCount[$baseName] = 1;
                $finalName = $baseName;
            } else {
                $nameCount[$baseName]++;
                $finalName = $baseName . ' (' . $nameCount[$baseName] . ')';
            }

            // Always create a NEW alternative for each row
            $alternative = Alternative::create(['name' => $finalName]);

            // Insert values for each criteria
            foreach ($this->criterias as $criteria) {
                $colKey = strtolower($criteria->code); // e.g., 'c1', 'c2'
                $rawValue = $row[$colKey] ?? 0;

                // Auto-convert range like "10-20" to midpoint (15)
                $value = $this->parseValue($rawValue);

                AlternativeValue::create([
                    'alternative_id' => $alternative->id,
                    'criteria_id'    => $criteria->id,
                    'value'          => $value,
                ]);
            }

            $this->importedCount++;
        }
    }

    /**
     * Parse a value that may be a range (e.g., "10-20") or a plain number.
     * Supports both Western decimal format (18.24) and Indonesian format (18,24).
     * Ranges are converted to their midpoint: (min + max) / 2.
     */
    private function parseValue($raw): float
    {
        $str = trim((string) $raw);

        // STEP 1: Handle range format FIRST (before any dot stripping)
        // e.g., "10-20" or "10.5-20.5"
        if (preg_match('/^(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)$/', $str, $matches)) {
            $min = (float) $matches[1];
            $max = (float) $matches[2];
            return ($min + $max) / 2;
        }

        // STEP 2: If it's already a valid Western decimal number (e.g., "18.24", "93.76", "22.3")
        // return it directly WITHOUT stripping the dot (dot is decimal separator, NOT thousands)
        if (is_numeric($str) && strpos($str, '.') !== false) {
            return (float) $str;
        }

        // STEP 3: Indonesian format "15.444,71" → remove thousands dots, replace comma with dot
        $str = str_replace('.', '', $str);
        $str = str_replace(',', '.', $str);

        return is_numeric($str) ? (float) $str : 0;
    }
}
