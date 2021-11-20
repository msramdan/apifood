<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Food::create([
            'name' => 'Nasi Goreng',
            'price' => 10000,
            'rate' => 4,
            'types' => "Makanan",
            'picturePath' => "",

        ]);

        Food::create([
            'name' => 'Mie Goreng',
            'price' => 8000,
            'rate' => 4,
            'types' => "Makanan",
            'picturePath' => "",

        ]);

        Food::create([
            'name' => 'Jus Mangga',
            'price' => 5000,
            'rate' => 5,
            'types' => "Minuman",
            'picturePath' => "",

        ]);

        Food::create([
            'name' => 'Es Teh Manis',
            'price' => 3000,
            'rate' => 5,
            'types' => "Minuman",
            'picturePath' => "",

        ]);
    }
}
