<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeImage;
use App\Models\Step;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        // ユーザー作成
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        $user1 = User::firstOrCreate(
            ['email' => 'yamada@example.com'],
            ['name' => '山田太郎', 'password' => Hash::make('password'), 'role' => 'user']
        );

        $user2 = User::firstOrCreate(
            ['email' => 'suzuki@example.com'],
            ['name' => '鈴木花子', 'password' => Hash::make('password'), 'role' => 'user']
        );

        // ストレージディレクトリ確認
        Storage::disk('public')->makeDirectory('recipe_images');
        Storage::disk('public')->makeDirectory('step_images');

        // レシピデータ
        $recipesData = [
            [
                'user'        => $admin,
                'title'       => '簡単！卵かけごはん',
                'description' => '朝ごはんにぴったりの超簡単レシピ。卵と醤油だけで絶品の一品が完成します。',
                'cook_time'   => 3,
                'difficulty'  => '超簡単',
                'status'      => 'published',
                'color'       => [255, 200, 100],
                'ingredients' => [
                    ['name' => 'ごはん', 'quantity' => '1', 'unit' => '杯'],
                    ['name' => '卵', 'quantity' => '1', 'unit' => '個'],
                    ['name' => '醤油', 'quantity' => '小さじ1', 'unit' => ''],
                    ['name' => 'かつおぶし', 'quantity' => '適量', 'unit' => ''],
                ],
                'steps' => [
                    ['description' => '茶碗にごはんを盛ります。'],
                    ['description' => '卵を割り入れ、醤油をかけます。'],
                    ['description' => 'かつおぶしをのせて完成です。'],
                ],
            ],
            [
                'user'        => $admin,
                'title'       => 'レンジで簡単！麻婆豆腐',
                'description' => '電子レンジで本格的な麻婆豆腐が作れます。辛さは豆板醤の量で調整してください。',
                'cook_time'   => 10,
                'difficulty'  => '簡単',
                'status'      => 'published',
                'color'       => [220, 80, 60],
                'ingredients' => [
                    ['name' => '豆腐（絹）', 'quantity' => '1', 'unit' => '丁'],
                    ['name' => '豚ひき肉', 'quantity' => '100', 'unit' => 'g'],
                    ['name' => '豆板醤', 'quantity' => '小さじ1', 'unit' => ''],
                    ['name' => '醤油', 'quantity' => '大さじ1', 'unit' => ''],
                    ['name' => '鶏がらスープの素', 'quantity' => '小さじ1', 'unit' => ''],
                    ['name' => '片栗粉', 'quantity' => '大さじ1', 'unit' => ''],
                    ['name' => '水', 'quantity' => '150', 'unit' => 'ml'],
                ],
                'steps' => [
                    ['description' => '豆腐を2cm角に切り、耐熱容器に入れます。'],
                    ['description' => 'ひき肉・豆板醤・醤油・鶏がらスープの素・水を混ぜ合わせ、豆腐の上にかけます。'],
                    ['description' => 'ラップをして電子レンジで5分加熱します。'],
                    ['description' => '水溶き片栗粉を加えてよく混ぜ、さらに2分加熱して完成です。'],
                ],
            ],
            [
                'user'        => $admin,
                'title'       => '基本のチャーハン',
                'description' => 'パラパラチャーハンのコツは強火と素早い動作です。シンプルな材料で本格的な味わいに。',
                'cook_time'   => 15,
                'difficulty'  => '普通',
                'status'      => 'published',
                'color'       => [180, 150, 80],
                'ingredients' => [
                    ['name' => 'ごはん', 'quantity' => '2', 'unit' => '杯'],
                    ['name' => '卵', 'quantity' => '2', 'unit' => '個'],
                    ['name' => 'ネギ', 'quantity' => '1/4', 'unit' => '本'],
                    ['name' => 'チャーシュー', 'quantity' => '60', 'unit' => 'g'],
                    ['name' => '醤油', 'quantity' => '大さじ1', 'unit' => ''],
                    ['name' => 'サラダ油', 'quantity' => '大さじ1', 'unit' => ''],
                    ['name' => '塩・こしょう', 'quantity' => '適量', 'unit' => ''],
                ],
                'steps' => [
                    ['description' => 'ネギとチャーシューを細かく刻みます。'],
                    ['description' => 'フライパンを強火で熱し、油をひいて卵を炒り卵にします。'],
                    ['description' => '冷やごはんを加えてほぐしながら炒めます。'],
                    ['description' => 'チャーシューとネギを加え、醤油・塩こしょうで味を整えて完成です。'],
                ],
            ],
            [
                'user'        => $user1,
                'title'       => 'トマトと卵の炒め物',
                'description' => '中華料理の定番。甘酸っぱいトマトとふわふわ卵の相性が抜群です。',
                'cook_time'   => 8,
                'difficulty'  => '簡単',
                'status'      => 'published',
                'color'       => [230, 100, 80],
                'ingredients' => [
                    ['name' => 'トマト', 'quantity' => '2', 'unit' => '個'],
                    ['name' => '卵', 'quantity' => '3', 'unit' => '個'],
                    ['name' => '砂糖', 'quantity' => '小さじ1', 'unit' => ''],
                    ['name' => '塩', 'quantity' => '少々', 'unit' => ''],
                    ['name' => 'サラダ油', 'quantity' => '大さじ2', 'unit' => ''],
                ],
                'steps' => [
                    ['description' => 'トマトを一口大に切ります。卵は溶いて塩を混ぜておきます。'],
                    ['description' => '油を熱し、卵を入れてふんわり炒め、一度取り出します。'],
                    ['description' => 'トマトを炒め、砂糖を加えます。卵を戻して手早く混ぜ合わせ完成です。'],
                ],
            ],
            [
                'user'        => $user1,
                'title'       => 'バナナスムージー',
                'description' => '朝食やおやつに最適。材料を混ぜるだけで栄養満点のスムージーが完成します。',
                'cook_time'   => 5,
                'difficulty'  => '超簡単',
                'status'      => 'pending',
                'color'       => [255, 230, 100],
                'ingredients' => [
                    ['name' => 'バナナ', 'quantity' => '1', 'unit' => '本'],
                    ['name' => '牛乳', 'quantity' => '200', 'unit' => 'ml'],
                    ['name' => 'はちみつ', 'quantity' => '大さじ1', 'unit' => ''],
                ],
                'steps' => [
                    ['description' => 'バナナを適当な大きさに切ります。'],
                    ['description' => 'すべての材料をブレンダーに入れて撹拌します。'],
                    ['description' => 'グラスに注いで完成です。'],
                ],
            ],
            [
                'user'        => $user2,
                'title'       => 'キャベツの塩昆布和え',
                'description' => '包丁いらずで作れる超時短おつまみ。塩昆布の旨味がキャベツに染みて絶品です。',
                'cook_time'   => 5,
                'difficulty'  => '超簡単',
                'status'      => 'rejected',
                'color'       => [150, 200, 120],
                'ingredients' => [
                    ['name' => 'キャベツ', 'quantity' => '1/4', 'unit' => '個'],
                    ['name' => '塩昆布', 'quantity' => '10', 'unit' => 'g'],
                    ['name' => 'ごま油', 'quantity' => '小さじ1', 'unit' => ''],
                ],
                'steps' => [
                    ['description' => 'キャベツを手でちぎってポリ袋に入れます。'],
                    ['description' => '塩昆布・ごま油を加えて揉み込みます。'],
                    ['description' => '10分ほど置いて味を馴染ませたら完成です。'],
                ],
            ],
        ];

        foreach ($recipesData as $data) {
            $recipe = Recipe::create([
                'user_id'     => $data['user']->id,
                'title'       => $data['title'],
                'description' => $data['description'],
                'cook_time'   => $data['cook_time'],
                'difficulty'  => $data['difficulty'],
                'status'      => $data['status'],
            ]);

            // 画像生成・保存
            $this->createRecipeImage($recipe, $data['title'], $data['color']);

            // 食材
            foreach ($data['ingredients'] as $i => $ingredient) {
                Ingredient::create([
                    'recipe_id' => $recipe->id,
                    'name'      => $ingredient['name'],
                    'quantity'  => $ingredient['quantity'],
                    'unit'      => $ingredient['unit'],
                    'order'     => $i + 1,
                ]);
            }

            // 手順
            foreach ($data['steps'] as $i => $step) {
                Step::create([
                    'recipe_id'   => $recipe->id,
                    'order'       => $i + 1,
                    'description' => $step['description'],
                    'image_path'  => null,
                ]);
            }
        }

        // ブックマーク（user1 が published レシピをブックマーク）
        $published = Recipe::published()->take(3)->get();
        foreach ($published as $recipe) {
            Bookmark::firstOrCreate([
                'user_id'   => $user1->id,
                'recipe_id' => $recipe->id,
            ]);
        }
    }

    /**
     * GD でプレースホルダー画像を生成して storage に保存する
     */
    private function createRecipeImage(Recipe $recipe, string $title, array $rgb): void
    {
        $width  = 800;
        $height = 600;

        $image = imagecreatetruecolor($width, $height);

        $bg      = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        $dark    = imagecolorallocate($image, (int)($rgb[0] * 0.7), (int)($rgb[1] * 0.7), (int)($rgb[2] * 0.7));
        $white   = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $bg);

        // 装飾ライン
        imagefilledrectangle($image, 0, $height - 80, $width, $height, $dark);

        // タイトルテキスト（英字のみ描画可）
        $label = 'Recipe: ' . $recipe->id;
        imagestring($image, 5, 20, $height - 55, $label, $white);
        imagestring($image, 3, 20, $height - 30, 'CookEasy', $white);

        // 中央に円形装飾
        imageellipse($image, $width / 2, $height / 2 - 40, 200, 200, $dark);

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        $filename = 'recipe_images/' . uniqid('img_') . '.png';
        Storage::disk('public')->put($filename, $imageData);

        RecipeImage::create([
            'recipe_id'    => $recipe->id,
            'image_path'   => $filename,
            'order'        => 1,
            'is_thumbnail' => true,
        ]);
    }
}
