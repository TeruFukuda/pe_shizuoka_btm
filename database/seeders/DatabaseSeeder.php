<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 最初にユーザーを作成
        $this->call(UserSeeder::class);

        // ジャンルのシード
        $this->call(GenreSeeder::class);
        
        // 各ジャンルのクイズ問題のシード
        $this->call(MathQuizSeeder::class);
        $this->call(HistoryQuizSeeder::class);
        $this->call(GeographyQuizSeeder::class);
        $this->call(ScienceQuizSeeder::class);
        $this->call(WorldHeritageQuizSeeder::class);
    }
}
