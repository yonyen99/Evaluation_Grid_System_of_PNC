<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
     public function run(): void
    {
        $provinces = [
            'Banteay Meanchey',
            'Battambang',
            'Kampong Cham',
            'Kampong Chhnang',
            'Kampong Speu',
            'Kampong Thom',
            'Kampot',
            'Kandal',
            'Koh Kong',
            'Kratie',
            'Mondul Kiri',
            'Phnom Penh',
            'Preah Vihear',
            'Prey Veng',
            'Pursat',
            'Ratanak Kiri',
            'Siemreap',
            'Preah Sihanouk',
            'Stung Treng',
            'Svay Rieng',
            'Takeo',
            'Oddar Meanchey',
            'Kep',
            'Pailin',
            'Tboung Khmum',
        ];

        foreach ($provinces as $name) {
            Province::create(['name' => $name]);
        }
    }
}
