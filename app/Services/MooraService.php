<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\Criteria;

class MooraService
{
    public function calculate()
    {
        $criterias = Criteria::all();
        $alternatives = Alternative::with('alternativeValues')->get();

        if ($criterias->isEmpty() || $alternatives->isEmpty()) {
            return null;
        }

        // 1. Build Decision Matrix
        $matrix = [];
        $criteriaTypes = [];
        $criteriaWeights = [];

        foreach ($criterias as $criteria) {
            $criteriaTypes[$criteria->id] = $criteria->type;
            $criteriaWeights[$criteria->id] = $criteria->weight;
        }

        foreach ($alternatives as $alternative) {
            foreach ($alternative->alternativeValues as $value) {
                $matrix[$alternative->id][$value->criteria_id] = $value->value;
            }
        }

        // 2. Normalization
        $normalizedMatrix = [];
        foreach ($criterias as $criteria) {
            $sumSquares = 0;
            foreach ($alternatives as $alternative) {
                $val = $matrix[$alternative->id][$criteria->id] ?? 0;
                $sumSquares += pow($val, 2);
            }
            $denominator = sqrt($sumSquares);

            foreach ($alternatives as $alternative) {
                $val = $matrix[$alternative->id][$criteria->id] ?? 0;
                // Avoid division by zero
                $normalizedMatrix[$alternative->id][$criteria->id] = $denominator > 0 ? $val / $denominator : 0;
            }
        }

        // 3. Optimization (Preference Values)
        $preferences = [];
        foreach ($alternatives as $alternative) {
            $benefitSum = 0;
            $costSum = 0;

            foreach ($criterias as $criteria) {
                $normVal = $normalizedMatrix[$alternative->id][$criteria->id] ?? 0;
                $weightedVal = $normVal * $criteriaWeights[$criteria->id];

                if ($criteriaTypes[$criteria->id] === 'benefit') {
                    $benefitSum += $weightedVal;
                } else {
                    $costSum += $weightedVal;
                }
            }

            $yi = $benefitSum - $costSum;
            $preferences[$alternative->id] = [
                'alternative' => $alternative,
                'yi' => $yi
            ];
        }

        // 4. Ranking
        usort($preferences, function ($a, $b) {
            return $b['yi'] <=> $a['yi']; // Descending order
        });

        // Add rank
        $rank = 1;
        foreach ($preferences as &$pref) {
            $pref['rank'] = $rank++;
        }

        return [
            'criterias' => $criterias,
            'alternatives' => $alternatives,
            'matrix' => $matrix,
            'normalizedMatrix' => $normalizedMatrix,
            'preferences' => collect($preferences),
        ];
    }
}
