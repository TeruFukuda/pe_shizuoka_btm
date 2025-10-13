<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizQuestion;
use App\Models\QuizChoice;
use App\Models\User;

class QuizQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ユーザーID 1を取得（テスト用）
        $user = User::find(1);
        if (!$user) {
            $this->command->warn('ユーザーID 1が見つかりません。先にUserSeederを実行してください。');
            return;
        }

        $questions = [
            [
                'question' => '日本の世界遺産「姫路城」の別名は何でしょうか？',
                'choices' => [
                    ['text' => '白鷺城', 'is_correct' => true],
                    ['text' => '黒鷺城', 'is_correct' => false],
                    ['text' => '青鷺城', 'is_correct' => false],
                    ['text' => '赤鷺城', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'エジプトの世界遺産「ピラミッド」の中で最も大きいものは何でしょうか？',
                'choices' => [
                    ['text' => 'クフ王のピラミッド', 'is_correct' => true],
                    ['text' => 'カフラー王のピラミッド', 'is_correct' => false],
                    ['text' => 'メンカウラー王のピラミッド', 'is_correct' => false],
                    ['text' => 'スネフェル王のピラミッド', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'イタリアの世界遺産「コロッセオ」が建設された時代は？',
                'choices' => [
                    ['text' => 'ローマ帝国時代', 'is_correct' => true],
                    ['text' => 'ルネサンス時代', 'is_correct' => false],
                    ['text' => '中世時代', 'is_correct' => false],
                    ['text' => '古代ギリシャ時代', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'フランスの世界遺産「モン・サン・ミッシェル」は何と呼ばれているでしょうか？',
                'choices' => [
                    ['text' => '海のピラミッド', 'is_correct' => true],
                    ['text' => '空の城', 'is_correct' => false],
                    ['text' => '海の城', 'is_correct' => false],
                    ['text' => '雲の城', 'is_correct' => false],
                ]
            ],
            [
                'question' => '中国の世界遺産「万里の長城」の全長は約何キロメートルでしょうか？',
                'choices' => [
                    ['text' => '約21,000km', 'is_correct' => true],
                    ['text' => '約10,000km', 'is_correct' => false],
                    ['text' => '約5,000km', 'is_correct' => false],
                    ['text' => '約15,000km', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'インドの世界遺産「タージ・マハル」を建設した皇帝は誰でしょうか？',
                'choices' => [
                    ['text' => 'シャー・ジャハーン', 'is_correct' => true],
                    ['text' => 'アクバル大帝', 'is_correct' => false],
                    ['text' => 'アウラングゼーブ', 'is_correct' => false],
                    ['text' => 'バーブル', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'ペルーの世界遺産「マチュ・ピチュ」の標高は約何メートルでしょうか？',
                'choices' => [
                    ['text' => '約2,430m', 'is_correct' => true],
                    ['text' => '約1,500m', 'is_correct' => false],
                    ['text' => '約3,000m', 'is_correct' => false],
                    ['text' => '約4,000m', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'オーストラリアの世界遺産「シドニー・オペラハウス」の建築家は誰でしょうか？',
                'choices' => [
                    ['text' => 'ヨーン・ウッツォン', 'is_correct' => true],
                    ['text' => 'フランク・ロイド・ライト', 'is_correct' => false],
                    ['text' => 'ル・コルビュジエ', 'is_correct' => false],
                    ['text' => 'ミース・ファン・デル・ローエ', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'ブラジルの世界遺産「イグアスの滝」は何カ国にまたがっているでしょうか？',
                'choices' => [
                    ['text' => '2カ国（ブラジルとアルゼンチン）', 'is_correct' => true],
                    ['text' => '1カ国（ブラジルのみ）', 'is_correct' => false],
                    ['text' => '3カ国（ブラジル、アルゼンチン、パラグアイ）', 'is_correct' => false],
                    ['text' => '4カ国（ブラジル、アルゼンチン、パラグアイ、ウルグアイ）', 'is_correct' => false],
                ]
            ],
            [
                'question' => 'スペインの世界遺産「サグラダ・ファミリア」の建築家は誰でしょうか？',
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
                'user_id' => $user->id,
                'question' => $questionData['question'],
            ]);

            foreach ($questionData['choices'] as $choiceData) {
                QuizChoice::create([
                    'quiz_question_id' => $question->id,
                    'choice_text' => $choiceData['text'],
                    'is_correct' => $choiceData['is_correct'],
                ]);
            }
        }

        $this->command->info('世界遺産に関するクイズ問題を10問作成しました。');
    }
}
