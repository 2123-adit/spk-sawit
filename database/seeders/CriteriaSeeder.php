<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Criteria;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criterias = [
            ['code' => 'C1', 'name' => 'Umur Tanaman', 'type' => 'benefit', 'weight' => 0.15],
            ['code' => 'C2', 'name' => 'Faktor K', 'type' => 'benefit', 'weight' => 0.15],
            ['code' => 'C3', 'name' => 'Rendemen CPO', 'type' => 'benefit', 'weight' => 0.25],
            ['code' => 'C4', 'name' => 'Harga CPO', 'type' => 'benefit', 'weight' => 0.25],
            ['code' => 'C5', 'name' => 'Rendemen Kernel', 'type' => 'benefit', 'weight' => 0.10],
            ['code' => 'C6', 'name' => 'Harga Kernel', 'type' => 'benefit', 'weight' => 0.10],
        ];

        foreach ($criterias as $c) {
            Criteria::create($c);
        }
    }
}
