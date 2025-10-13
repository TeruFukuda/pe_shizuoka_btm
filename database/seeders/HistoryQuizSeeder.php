<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizQuestion;
use App\Models\QuizChoice;
use App\Models\User;
use App\Models\Genre;

class HistoryQuizSeeder extends Seeder
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

        // 歴史ジャンルを取得
        $historyGenre = Genre::where('name', '歴史')->first();
        if (!$historyGenre) {
            $this->command->warn('歴史ジャンルが見つかりません。先にGenreSeederを実行してください。');
            return;
        }

        $questions = [
            [
                'question' => '江戸幕府を開いた人物は誰でしょうか？',
                'choices' => [
                    ['text' => '徳川家康', 'is_correct' => true],
                    ['text' => '豊臣秀吉', 'is_correct' => false],
                    ['text' => '織田信長', 'is_correct' => false],
                    ['text' => '足利義満', 'is_correct' => false],
                ]
            ],
            [
                'question' => '第二次世界大戦が終結した年は？',
                'choices' => [
                    ['text' => '1945年', 'is_correct' => true],
                    ['text' => '1944年', 'is_correct' => false],
                    ['text' => '1946年', 'is_correct' => false],
                    ['text' => '1943年', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'フランス革命が始まった年は？',
                'choices' => [
                    ['text' => '1789年', 'is_correct' => true],
                    ['text' => '1799年', 'is_correct' => false],
                    ['text' => '1779年', 'is_correct' => false],
                    ['text' => '1790年', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'アメリカ独立宣言が発表された年は？',
                'choices' => [
                    ['text' => '1776年', 'is_correct' => true],
                    ['text' => '1775年', 'is_correct' => false],
                    ['text' => '1777年', 'is_correct' => false],
                    ['text' => '1778年', 'is_correct' => false],
                ]
            ],
            [
                'question' => '明治維新が始まった年は？',
                'choices' => [
                    ['text' => '1868年', 'is_correct' => true],
                    ['text' => '1867年', 'is_correct' => false],
                    ['text' => '1869年', 'is_correct' => false],
                    ['text' => '1870年', 'is_correct' => false],
                ]
            ],
            [
                'question' => '織田信長が本能寺の変で討たれた年は？',
                'choices' => [
                    ['text' => '1582年', 'is_correct' => true],
                    ['text' => '1580年', 'is_correct' => false],
                    ['text' => '1584年', 'is_correct' => false],
                    ['text' => '1586年', 'is_correct' => false],
                ]
            ],
            [
                'question' => '関ヶ原の戦いが行われた年は？',
                'choices' => [
                    ['text' => '1600年', 'is_correct' => true],
                    ['text' => '1598年', 'is_correct' => false],
                    ['text' => '1602年', 'is_correct' => false],
                    ['text' => '1604年', 'is_correct' => false],
                ]
            ],
            [
                'question' => '大化の改新が始まった年は？',
                'choices' => [
                    ['text' => '645年', 'is_correct' => true],
                    ['text' => '643年', 'is_correct' => false],
                    ['text' => '647年', 'is_correct' => false],
                    ['text' => '649年', 'is_correct' => false],
                ]
            ],
            [
                'question' => '源頼朝が鎌倉幕府を開いた年は？',
                'choices' => [
                    ['text' => '1192年', 'is_correct' => true],
                    ['text' => '1190年', 'is_correct' => false],
                    ['text' => '1194年', 'is_correct' => false],
                    ['text' => '1196年', 'is_correct' => false],
                ]
            ],
            [
                'question' => '応仁の乱が始まった年は？',
                'choices' => [
                    ['text' => '1467年', 'is_correct' => true],
                    ['text' => '1465年', 'is_correct' => false],
                    ['text' => '1469年', 'is_correct' => false],
                    ['text' => '1471年', 'is_correct' => false],
                ]
            ]
        ];

        foreach ($questions as $questionData) {
            $question = QuizQuestion::create([
                'user_id' => $user->id,
                'question' => $questionData['question'],
                'genre_id' => $historyGenre->id,
            ]);

            foreach ($questionData['choices'] as $choiceData) {
                QuizChoice::create([
                    'quiz_question_id' => $question->id,
                    'choice_text' => $choiceData['text'],
                    'is_correct' => $choiceData['is_correct'],
                ]);
            }
        }

        $this->command->info('歴史に関するクイズ問題を10問作成しました。');
    }
}
