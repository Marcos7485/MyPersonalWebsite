<?php

namespace Database\Seeders;

use App\Models\cards;
use Illuminate\Database\Seeder;

class CardsSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'project' => 'iqathletic',
                'card' => 1,
                'image' => 'card1.png',
                'icon' => 'iqathletic/icon.png',
                'hover_text' => 'iQ Athletic',
                'component' => 'cards/iqathletic/Card_component_1',
                'descripcion' => 'Sistema de gestión para centros deportivos',
                'active' => true,
            ],
            [
                'project' => 'ecommerce',
                'card' => 2,
                'image' => null,
                'icon' => 'drs.webp',
                'hover_text' => 'Ecommerce',
                'component' => null,
                'descripcion' => 'Tienda online lista para vender',
                'active' => true,
            ],
        ];

        foreach ($rows as $row) {
            cards::query()->updateOrCreate(
                [
                    'project' => $row['project'],
                    'card' => $row['card'],
                ],
                $row,
            );
        }
    }
}
