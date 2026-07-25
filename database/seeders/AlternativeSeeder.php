<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\Criteria;

class AlternativeSeeder extends Seeder
{
    public function run(): void
    {
        $c1 = Criteria::where('code', 'C1')->first()->id;
        $c2 = Criteria::where('code', 'C2')->first()->id;
        $c3 = Criteria::where('code', 'C3')->first()->id;
        $c4 = Criteria::where('code', 'C4')->first()->id;
        $c5 = Criteria::where('code', 'C5')->first()->id;
        $c6 = Criteria::where('code', 'C6')->first()->id;

        $data = [
            ['name' => 'A1', 'values' => [3, 93.03, 18.24, 13559.44, 5.03, 13105.10]],
            ['name' => 'A2', 'values' => [4, 93.03, 19.38, 13559.44, 5.44, 13105.10]],
            ['name' => 'A3', 'values' => [5, 93.03, 20.14, 13559.44, 5.53, 13105.10]],
            ['name' => 'A4', 'values' => [6, 93.03, 20.88, 13559.44, 5.53, 13105.10]],
            ['name' => 'A5', 'values' => [7, 93.03, 21.34, 13559.44, 4.80, 13105.10]],
            ['name' => 'A6', 'values' => [8, 93.03, 21.83, 13559.44, 5.25, 13105.10]],
            ['name' => 'A7', 'values' => [9, 93.03, 22.21, 13559.44, 5.10, 13105.10]],
            ['name' => 'A8', 'values' => [15, 93.03, 22.42, 13559.44, 5.11, 13105.10]],
            ['name' => 'A9', 'values' => [21, 93.03, 22.30, 13559.44, 5.28, 13105.10]],
            ['name' => 'A10', 'values' => [22, 93.03, 21.95, 13559.44, 5.39, 13105.10]],
            ['name' => 'A11', 'values' => [23, 93.03, 21.77, 13559.44, 5.17, 13105.10]],
            ['name' => 'A12', 'values' => [24, 93.03, 20.94, 13559.44, 5.19, 13105.10]],
            ['name' => 'A13', 'values' => [25, 93.03, 20.12, 13559.44, 5.27, 13105.10]],
            ['name' => 'A14', 'values' => [26, 93.03, 19.86, 13559.44, 5.34, 13105.10]],
            ['name' => 'A15', 'values' => [27, 93.03, 19.47, 13559.44, 5.37, 13105.10]],
            ['name' => 'A16', 'values' => [28, 93.03, 19.06, 13559.44, 5.40, 13105.10]],
            ['name' => 'A17', 'values' => [29, 93.03, 18.63, 13559.44, 5.44, 13105.10]],
            ['name' => 'A18', 'values' => [30, 93.03, 18.20, 13559.44, 5.48, 13105.10]],
        ];

        foreach ($data as $item) {
            $alternative = Alternative::create(['name' => $item['name']]);
            
            AlternativeValue::create(['alternative_id' => $alternative->id, 'criteria_id' => $c1, 'value' => $item['values'][0]]);
            AlternativeValue::create(['alternative_id' => $alternative->id, 'criteria_id' => $c2, 'value' => $item['values'][1]]);
            AlternativeValue::create(['alternative_id' => $alternative->id, 'criteria_id' => $c3, 'value' => $item['values'][2]]);
            AlternativeValue::create(['alternative_id' => $alternative->id, 'criteria_id' => $c4, 'value' => $item['values'][3]]);
            AlternativeValue::create(['alternative_id' => $alternative->id, 'criteria_id' => $c5, 'value' => $item['values'][4]]);
            AlternativeValue::create(['alternative_id' => $alternative->id, 'criteria_id' => $c6, 'value' => $item['values'][5]]);
        }
    }
}
