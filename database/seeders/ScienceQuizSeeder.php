<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizQuestion;
use App\Models\QuizChoice;
use App\Models\User;
use App\Models\Genre;

class ScienceQuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 最初のユーザーを取得（テスト用）
        $user = User::first();
        if (!$user) {
            $this->command->warn('ユーザーが見つかりません。先にUserSeederを実行してください。');
            return;
        }

        // 科学ジャンルを取得
        $scienceGenre = Genre::where('name', '科学')->first();
        if (!$scienceGenre) {
            $this->command->warn('科学ジャンルが見つかりません。先にGenreSeederを実行してください。');
            return;
        }

        $questions = [
            [
                'question' => '水の化学式は？',
                'choices' => [
                    ['text' => 'H2O', 'is_correct' => true],
                    ['text' => 'CO2', 'is_correct' => false],
                    ['text' => 'O2', 'is_correct' => false],
                    ['text' => 'H2', 'is_correct' => false],
                ]
            ],
            [
                'question' => '光の速度は秒速約何キロメートルでしょうか？',
                'choices' => [
                    ['text' => '30万km', 'is_correct' => true],
                    ['text' => '3万km', 'is_correct' => false],
                    ['text' => '300万km', 'is_correct' => false],
                    ['text' => '3000万km', 'is_correct' => false],
                ]
            ],
            [
                'question' => '地球の重力加速度は約何m/s²でしょうか？',
                'choices' => [
                    ['text' => '9.8m/s²', 'is_correct' => true],
                    ['text' => '8.9m/s²', 'is_correct' => false],
                    ['text' => '10.8m/s²', 'is_correct' => false],
                    ['text' => '7.8m/s²', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'DNAの二重らせん構造を発見したのは誰でしょうか？',
                'choices' => [
                    ['text' => 'ワトソンとクリック', 'is_correct' => true],
                    ['text' => 'ダーウィンとメンデル', 'is_correct' => false],
                    ['text' => 'アインシュタインとニュートン', 'is_correct' => false],
                    ['text' => 'パスツールとコッホ', 'is_correct' => false],
                ]
            ],
            [
                'question' => '太陽系で最も大きな惑星は？',
                'choices' => [
                    ['text' => '木星', 'is_correct' => true],
                    ['text' => '土星', 'is_correct' => false],
                    ['text' => '海王星', 'is_correct' => false],
                    ['text' => '天王星', 'is_correct' => false],
                ]
            ],
            [
                'question' => '酸素の化学式は？',
                'choices' => [
                    ['text' => 'O2', 'is_correct' => true],
                    ['text' => 'O', 'is_correct' => false],
                    ['text' => 'O3', 'is_correct' => false],
                    ['text' => 'H2O', 'is_correct' => false],
                ]
            ],
            [
                'question' => '原子の中心にあるのは？',
                'choices' => [
                    ['text' => '原子核', 'is_correct' => true],
                    ['text' => '電子', 'is_correct' => false],
                    ['text' => '陽子', 'is_correct' => false],
                    ['text' => '中性子', 'is_correct' => false],
                ]
            ],
            [
                'question' => '植物が光合成で作るのは？',
                'choices' => [
                    ['text' => 'ブドウ糖と酸素', 'is_correct' => true],
                    ['text' => '二酸化炭素と水', 'is_correct' => false],
                    ['text' => 'タンパク質と脂質', 'is_correct' => false],
                    ['text' => 'アミノ酸とビタミン', 'is_correct' => false],
                ]
            ],
            [
                'question' => '地球の大気の主成分は？',
                'choices' => [
                    ['text' => '窒素', 'is_correct' => true],
                    ['text' => '酸素', 'is_correct' => false],
                    ['text' => '二酸化炭素', 'is_correct' => false],
                    ['text' => '水素', 'is_correct' => false],
                ]
            ],
            [
                'question' => '細胞の核にある遺伝情報を担う物質は？',
                'choices' => [
                    ['text' => 'DNA', 'is_correct' => true],
                    ['text' => 'RNA', 'is_correct' => false],
                    ['text' => 'タンパク質', 'is_correct' => false],
                    ['text' => '脂質', 'is_correct' => false],
                ]
            ]
        ];

        foreach ($questions as $questionData) {
            $question = QuizQuestion::create([
                'user_id' => $user->id,
                'question' => $questionData['question'],
                'genre_id' => $scienceGenre->id,
            ]);

            foreach ($questionData['choices'] as $choiceData) {
                QuizChoice::create([
                    'quiz_question_id' => $question->id,
                    'choice_text' => $choiceData['text'],
                    'is_correct' => $choiceData['is_correct'],
                ]);
            }
        }

        $this->command->info('科学に関するクイズ問題を10問作成しました。');
    }
}
