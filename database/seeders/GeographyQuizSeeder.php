<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizQuestion;
use App\Models\QuizChoice;
use App\Models\User;
use App\Models\Genre;

class GeographyQuizSeeder extends Seeder
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

        // 地理ジャンルを取得
        $geographyGenre = Genre::where('name', '地理')->first();
        if (!$geographyGenre) {
            $this->command->warn('地理ジャンルが見つかりません。先にGenreSeederを実行してください。');
            return;
        }

        $questions = [
            [
                'question' => '世界で最も高い山は？',
                'choices' => [
                    ['text' => 'エベレスト', 'is_correct' => true],
                    ['text' => 'K2', 'is_correct' => false],
                    ['text' => 'キリマンジャロ', 'is_correct' => false],
                    ['text' => '富士山', 'is_correct' => false],
                ]
            ],
            [
                'question' => '世界で最も長い川は？',
                'choices' => [
                    ['text' => 'ナイル川', 'is_correct' => true],
                    ['text' => 'アマゾン川', 'is_correct' => false],
                    ['text' => 'ミシシッピ川', 'is_correct' => false],
                    ['text' => '長江', 'is_correct' => false],
                ]
            ],
            [
                'question' => '世界で最も大きな大陸は？',
                'choices' => [
                    ['text' => 'アジア大陸', 'is_correct' => true],
                    ['text' => 'アフリカ大陸', 'is_correct' => false],
                    ['text' => '北アメリカ大陸', 'is_correct' => false],
                    ['text' => '南アメリカ大陸', 'is_correct' => false],
                ]
            ],
            [
                'question' => '日本の最北端の都道府県は？',
                'choices' => [
                    ['text' => '北海道', 'is_correct' => true],
                    ['text' => '青森県', 'is_correct' => false],
                    ['text' => '岩手県', 'is_correct' => false],
                    ['text' => '秋田県', 'is_correct' => false],
                ]
            ],
            [
                'question' => '世界で最も人口の多い国は？',
                'choices' => [
                    ['text' => '中国', 'is_correct' => true],
                    ['text' => 'インド', 'is_correct' => false],
                    ['text' => 'アメリカ', 'is_correct' => false],
                    ['text' => 'インドネシア', 'is_correct' => false],
                ]
            ],
            [
                'question' => '世界で最も大きな湖は？',
                'choices' => [
                    ['text' => 'カスピ海', 'is_correct' => true],
                    ['text' => 'スペリオル湖', 'is_correct' => false],
                    ['text' => 'ヴィクトリア湖', 'is_correct' => false],
                    ['text' => 'バイカル湖', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'アフリカ大陸で最も高い山は？',
                'choices' => [
                    ['text' => 'キリマンジャロ', 'is_correct' => true],
                    ['text' => 'ケニア山', 'is_correct' => false],
                    ['text' => 'ルウェンゾリ山', 'is_correct' => false],
                    ['text' => 'アトラス山脈', 'is_correct' => false],
                ]
            ],
            [
                'question' => '世界で最も深い海溝は？',
                'choices' => [
                    ['text' => 'マリアナ海溝', 'is_correct' => true],
                    ['text' => 'トンガ海溝', 'is_correct' => false],
                    ['text' => 'フィリピン海溝', 'is_correct' => false],
                    ['text' => '日本海溝', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'ヨーロッパで最も長い川は？',
                'choices' => [
                    ['text' => 'ヴォルガ川', 'is_correct' => true],
                    ['text' => 'ドナウ川', 'is_correct' => false],
                    ['text' => 'ライン川', 'is_correct' => false],
                    ['text' => 'セーヌ川', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'オーストラリアの首都は？',
                'choices' => [
                    ['text' => 'キャンベラ', 'is_correct' => true],
                    ['text' => 'シドニー', 'is_correct' => false],
                    ['text' => 'メルボルン', 'is_correct' => false],
                    ['text' => 'ブリスベン', 'is_correct' => false],
                ]
            ]
        ];

        foreach ($questions as $questionData) {
            $question = QuizQuestion::create([
                'user_id' => $user->id,
                'question' => $questionData['question'],
                'genre_id' => $geographyGenre->id,
            ]);

            foreach ($questionData['choices'] as $choiceData) {
                QuizChoice::create([
                    'quiz_question_id' => $question->id,
                    'choice_text' => $choiceData['text'],
                    'is_correct' => $choiceData['is_correct'],
                ]);
            }
        }

        $this->command->info('地理に関するクイズ問題を10問作成しました。');
    }
}
