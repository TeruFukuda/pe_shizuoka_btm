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
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'full_name' => '管理者',
        ]);

        // 一般ユーザー
        User::create([
            'username' => 'user1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
            'full_name' => 'テストユーザー1',
        ]);

        User::create([
            'username' => 'user2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'),
            'full_name' => 'テストユーザー2',
        ]);
    }
}
