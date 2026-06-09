<?php

namespace Database\Seeders;

use App\Enums\RoastLevel;
use App\Models\Coffee;
use App\Models\User;
use Illuminate\Database\Seeder;

class CoffeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();

        if ($user) {
            $coffees = [
                [
                    'name' => 'Ethiopia Yirgacheffe Kochere',
                    'roastery' => 'Space Roastery',
                    'roast_level' => RoastLevel::LIGHT->value,
                    'description' => 'Biji kopi dengan karakter floral yang kuat, aroma melati, dan sentuhan acidity citrus yang sangat cocok untuk seduhan V60.',
                ],
                [
                    'name' => 'Colombia Finca El Paraiso Anaerobic',
                    'roastery' => 'Fugol Coffee Roasters',
                    'roast_level' => RoastLevel::LIGHT->value,
                    'description' => 'Proses anaerobik dengan notes rasa strawberry, peach, dan rose tea yang sangat manis.',
                ],
                [
                    'name' => 'Gayo Wine Natural',
                    'roastery' => 'Klinik Kopi',
                    'roast_level' => RoastLevel::MEDIUM->value,
                    'description' => 'Memiliki fermentasi yang mengingatkan pada anggur merah, dengan body yang tebal dan keasaman buah tropis.',
                ],
                [
                    'name' => 'Brazil Cerrado House Blend',
                    'roastery' => 'Tanamera Coffee',
                    'roast_level' => RoastLevel::DARK->value,
                    'description' => 'Kopi klasik dengan rasa cokelat pekat, karamel, dan kacang panggang. Sangat rendah acidity.',
                ],
            ];

            foreach ($coffees as $coffee) {
                Coffee::firstOrCreate(
                    [
                        'name' => $coffee['name'],
                        'user_id' => $user->id,
                    ],
                    [
                        'roastery' => $coffee['roastery'],
                        'roast_level' => $coffee['roast_level'],
                        'description' => $coffee['description'],
                    ]
                );
            }
        }
    }
}
