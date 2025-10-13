<?php

namespace Database\Seeders;

use App\Models\QuizQuestion;
use App\Models\QuizChoice;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class WorldHeritageQuizSeeder extends Seeder
{
    /**
     * 世界遺産に関するクイズ問題を作成
     */
    public function run(): void
    {
        // 世界遺産ジャンルを取得
        $genre = Genre::where('name', '世界遺産')->first();
        
        if (!$genre) {
            $this->command->error('世界遺産ジャンルが見つかりません。');
            return;
        }

        $questions = [
            [
                'question' => '日本の世界遺産で、1993年に初めて登録されたのはどれですか？',
                'choices' => [
                    ['text' => '姫路城', 'is_correct' => false],
                    ['text' => '法隆寺地域の仏教建造物', 'is_correct' => true],
                    ['text' => '古都京都の文化財', 'is_correct' => false],
                    ['text' => '白神山地', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'エジプトのピラミッドの中で最も大きいのはどれですか？',
                'choices' => [
                    ['text' => 'カフラー王のピラミッド', 'is_correct' => false],
                    ['text' => 'クフ王のピラミッド', 'is_correct' => true],
                    ['text' => 'メンカウラー王のピラミッド', 'is_correct' => false],
                    ['text' => 'ジェドエフラー王のピラミッド', 'is_correct' => false],
                ]
            ],
            [
                'question' => '中国の万里の長城の全長は約何キロメートルですか？',
                'choices' => [
                    ['text' => '約6,000km', 'is_correct' => false],
                    ['text' => '約8,000km', 'is_correct' => false],
                    ['text' => '約21,000km', 'is_correct' => true],
                    ['text' => '約15,000km', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'イタリアのコロッセオが建設された時代は？',
                'choices' => [
                    ['text' => '紀元前1世紀', 'is_correct' => false],
                    ['text' => '紀元1世紀', 'is_correct' => true],
                    ['text' => '紀元2世紀', 'is_correct' => false],
                    ['text' => '紀元3世紀', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'インドのタージ・マハルを建設した皇帝は？',
                'choices' => [
                    ['text' => 'アクバル大帝', 'is_correct' => false],
                    ['text' => 'シャー・ジャハーン', 'is_correct' => true],
                    ['text' => 'フマーユーン', 'is_correct' => false],
                    ['text' => 'アウラングゼーブ', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'ペルーのマチュ・ピチュが発見された年は？',
                'choices' => [
                    ['text' => '1911年', 'is_correct' => true],
                    ['text' => '1921年', 'is_correct' => false],
                    ['text' => '1931年', 'is_correct' => false],
                    ['text' => '1941年', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'フランスのモン・サン・ミッシェルは何と呼ばれていますか？',
                'choices' => [
                    ['text' => '海の城', 'is_correct' => false],
                    ['text' => '奇跡の山', 'is_correct' => false],
                    ['text' => '西洋の驚異', 'is_correct' => true],
                    ['text' => '空の城', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'オーストラリアのグレート・バリア・リーフの全長は約何キロメートルですか？',
                'choices' => [
                    ['text' => '約1,000km', 'is_correct' => false],
                    ['text' => '約2,000km', 'is_correct' => true],
                    ['text' => '約3,000km', 'is_correct' => false],
                    ['text' => '約4,000km', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'ブラジルのイグアスの滝は何カ国にまたがっていますか？',
                'choices' => [
                    ['text' => '2カ国', 'is_correct' => true],
                    ['text' => '3カ国', 'is_correct' => false],
                    ['text' => '4カ国', 'is_correct' => false],
                    ['text' => '5カ国', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'スペインのサグラダ・ファミリアの建築家は？',
                'choices' => [
                    ['text' => 'アントニ・ガウディ', 'is_correct' => true],
                    ['text' => 'パブロ・ピカソ', 'is_correct' => false],
                    ['text' => 'サルバドール・ダリ', 'is_correct' => false],
                    ['text' => 'フランシスコ・ゴヤ', 'is_correct' => false],
                ]
            ]
        ];

        foreach ($questions as $questionData) {
            $question = QuizQuestion::create([
                'user_id' => 2, // ユーザーID 2を出題者として設定
                'question' => $questionData['question'],
                'genre_id' => $genre->id,
            ]);

            foreach ($questionData['choices'] as $choiceData) {
                QuizChoice::create([
                    'quiz_question_id' => $question->id,
                    'choice_text' => $choiceData['text'],
                    'is_correct' => $choiceData['is_correct'],
                ]);
            }
        }
    }
}
