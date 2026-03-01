<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * レシピ画像モデル
 *
 * @property int $id
 * @property int $recipe_id
 * @property string $image_path 画像ファイルパス
 * @property int $order 表示順
 * @property bool $is_thumbnail サムネイルかどうか
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Recipe $recipe
 */
class RecipeImage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'recipe_id',
        'image_path',
        'order',
        'is_thumbnail',
    ];

    /**
     * この画像が属するレシピ
     *
     * @return BelongsTo<Recipe, RecipeImage>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
