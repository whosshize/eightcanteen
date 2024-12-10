<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            // Mang Ijul
            ['name' => 'Jus Strawberry', 'price' => 5000, 'booth_id' => 1],
            ['name' => 'Pop Ice', 'price' => 5000, 'booth_id' => 1],
            ['name' => 'Good Day', 'price' => 5000, 'booth_id' => 1],

            // Mul
            ['name' => 'Somay', 'price' => 1000, 'booth_id' => 2],
            ['name' => 'Batagor', 'price' => 1000, 'booth_id' => 2],
            ['name' => 'Kentang', 'price' => 1000, 'booth_id' => 2],

            // Bubur
            ['name' => 'Bubur', 'price' => 8000, 'booth_id' => 3],
            ['name' => 'Sate', 'price' => 2000, 'booth_id' => 3],

            // Roti Bakar
            ['name' => 'Coklat', 'price' => 7000, 'booth_id' => 4],
            ['name' => 'Coklat Tiramisu', 'price' => 10000, 'booth_id' => 4],

            // Pecel
            ['name' => 'Pecel Lele', 'price' => 10000, 'booth_id' => 5],
            ['name' => 'Pecel Ayam', 'price' => 14000, 'booth_id' => 5],

            // Pakde
            ['name' => 'Es Teh', 'price' => 5000, 'booth_id' => 6],
            ['name' => 'Es Jeruk', 'price' => 5000, 'booth_id' => 6],
            ['name' => 'Es Susu', 'price' => 5000, 'booth_id' => 6],

            // Bude Gorengan
            ['name' => 'Gorengan', 'price' => 2000, 'booth_id' => 7],
            ['name' => 'Nasi Goreng', 'price' => 10000, 'booth_id' => 7],

            // Ketoprak
            ['name' => 'Ketoprak', 'price' => 10000, 'booth_id' => 8],
            ['name' => 'Lontong Sayur', 'price' => 10000, 'booth_id' => 8],

            // Kebab
            ['name' => 'Kebab Telor', 'price' => 5000, 'booth_id' => 9],
            ['name' => 'Kebab Jumbo', 'price' => 15000, 'booth_id' => 9],
            ['name' => 'Burger', 'price' => 10000, 'booth_id' => 9],

            // Rames
            ['name' => 'Nasi + Telor Balado', 'price' => 10000, 'booth_id' => 10],
            ['name' => 'Nasi + Ikan', 'price' => 10000, 'booth_id' => 10],
            ['name' => 'Nasi + Ayam', 'price' => 10000, 'booth_id' => 10],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
