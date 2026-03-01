<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 調理手順モデル
 *
 * created_at / updated_at は持たない（$timestamps = false）
 *
 * @property int $id
 * @property int $recipe_id
 * @property int $order 手順番号
 * @property string $description 手順の説明
 * @property string|null $image_path 手順画像のファイルパス
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Recipe $recipe
 */
class Step extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'recipe_id',
        'order',
        'description',
        'image_path',
    ];

    /**
     * この手順が属するレシピ
     *
     * @return BelongsTo<Recipe, Step>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
