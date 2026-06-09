<?php

namespace Database\Seeders;

use App\Models\Brewer;
use Illuminate\Database\Seeder;

class BrewerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brewers = [
            'V60',
            'Switch Dripper',
            'B75',
            'Kalita Wave',
            'Origami Dripper',
            'Chemex',
            'AeroPress',
            'Clever Dripper',
            'Orea V3',
            'French Press',
            'Tricolate',
            'Kono Meimon',
            'Flat Bottom (Generic)'
        ];

        foreach ($brewers as $brewer) {
            Brewer::firstOrCreate([
                'name' => $brewer
            ]);
        }
    }
}
