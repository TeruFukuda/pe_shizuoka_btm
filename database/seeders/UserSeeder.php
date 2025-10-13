<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * テスト用ユーザーを作成
     */
    public function run(): void
    {
        // 管理者ユーザー
        User::create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 一般ユーザー1
        User::create([
            'name' => 'テストユーザー1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 一般ユーザー2
        User::create([
            'name' => 'テストユーザー2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 一般ユーザー3
        User::create([
            'name' => 'テストユーザー3',
            'email' => 'user3@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 一般ユーザー4
        User::create([
            'name' => 'テストユーザー4',
            'email' => 'user4@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
