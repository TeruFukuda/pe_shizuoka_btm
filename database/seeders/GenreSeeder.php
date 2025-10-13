<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            [
                'name' => '数学',
                'sort_order' => 1,
                'is_disabled' => false,
            ],
            [
                'name' => '歴史',
                'sort_order' => 2,
                'is_disabled' => false,
            ],
            [
                'name' => '地理',
                'sort_order' => 3,
                'is_disabled' => false,
            ],
            [
                'name' => '世界遺産',
                'sort_order' => 4,
                'is_disabled' => false,
            ],
            [
                'name' => '科学',
                'sort_order' => 5,
                'is_disabled' => false,
            ],
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
