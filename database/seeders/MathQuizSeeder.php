<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizQuestion;
use App\Models\QuizChoice;
use App\Models\User;
use App\Models\Genre;

class MathQuizSeeder extends Seeder
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

        // 数学ジャンルを取得
        $mathGenre = Genre::where('name', '数学')->first();
        if (!$mathGenre) {
            $this->command->warn('数学ジャンルが見つかりません。先にGenreSeederを実行してください。');
            return;
        }

        $questions = [
            [
                'question' => '二次方程式 x² - 5x + 6 = 0 の解を求めなさい。',
                'choices' => [
                    ['text' => 'x = 2, x = 3', 'is_correct' => true],
                    ['text' => 'x = 1, x = 6', 'is_correct' => false],
                    ['text' => 'x = -2, x = -3', 'is_correct' => false],
                    ['text' => 'x = 0, x = 5', 'is_correct' => false],
                ]
            ],
            [
                'question' => '三角形の内角の和は何度でしょうか？',
                'choices' => [
                    ['text' => '180度', 'is_correct' => true],
                    ['text' => '90度', 'is_correct' => false],
                    ['text' => '360度', 'is_correct' => false],
                    ['text' => '270度', 'is_correct' => false],
                ]
            ],
            [
                'question' => '円周率πの値は約何でしょうか？',
                'choices' => [
                    ['text' => '3.14', 'is_correct' => true],
                    ['text' => '2.71', 'is_correct' => false],
                    ['text' => '1.41', 'is_correct' => false],
                    ['text' => '4.13', 'is_correct' => false],
                ]
            ],
            [
                'question' => '関数 f(x) = 2x + 3 の x = 5 での値は？',
                'choices' => [
                    ['text' => '13', 'is_correct' => true],
                    ['text' => '10', 'is_correct' => false],
                    ['text' => '8', 'is_correct' => false],
                    ['text' => '15', 'is_correct' => false],
                ]
            ],
            [
                'question' => '√16 の値は？',
                'choices' => [
                    ['text' => '4', 'is_correct' => true],
                    ['text' => '8', 'is_correct' => false],
                    ['text' => '2', 'is_correct' => false],
                    ['text' => '16', 'is_correct' => false],
                ]
            ],
            [
                'question' => '2の3乗は？',
                'choices' => [
                    ['text' => '8', 'is_correct' => true],
                    ['text' => '6', 'is_correct' => false],
                    ['text' => '9', 'is_correct' => false],
                    ['text' => '4', 'is_correct' => false],
                ]
            ],
            [
                'question' => '平行四辺形の面積の公式は？',
                'choices' => [
                    ['text' => '底辺 × 高さ', 'is_correct' => true],
                    ['text' => '底辺 × 斜辺', 'is_correct' => false],
                    ['text' => '底辺 + 高さ', 'is_correct' => false],
                    ['text' => '底辺 ÷ 高さ', 'is_correct' => false],
                ]
            ],
            [
                'question' => '一次方程式 3x + 7 = 22 の解は？',
                'choices' => [
                    ['text' => 'x = 5', 'is_correct' => true],
                    ['text' => 'x = 3', 'is_correct' => false],
                    ['text' => 'x = 7', 'is_correct' => false],
                    ['text' => 'x = 9', 'is_correct' => false],
                ]
            ],
            [
                'question' => '円の面積の公式は？',
                'choices' => [
                    ['text' => 'πr²', 'is_correct' => true],
                    ['text' => '2πr', 'is_correct' => false],
                    ['text' => 'πd', 'is_correct' => false],
                    ['text' => 'r²', 'is_correct' => false],
                ]
            ],
            [
                'question' => '因数分解 x² - 9 の結果は？',
                'choices' => [
                    ['text' => '(x + 3)(x - 3)', 'is_correct' => true],
                    ['text' => '(x + 9)(x - 1)', 'is_correct' => false],
                    ['text' => '(x - 3)²', 'is_correct' => false],
                    ['text' => '(x + 3)²', 'is_correct' => false],
                ]
            ]
        ];

        foreach ($questions as $questionData) {
            $question = QuizQuestion::create([
                'user_id' => $user->id,
                'question' => $questionData['question'],
                'genre_id' => $mathGenre->id,
            ]);

            foreach ($questionData['choices'] as $choiceData) {
                QuizChoice::create([
                    'quiz_question_id' => $question->id,
                    'choice_text' => $choiceData['text'],
                    'is_correct' => $choiceData['is_correct'],
                ]);
            }
        }

        $this->command->info('数学に関するクイズ問題を10問作成しました。');
    }
}
