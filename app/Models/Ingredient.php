<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 材料モデル
 *
 * created_at / updated_at は持たない（$timestamps = false）
 *
 * @property int $id
 * @property int $recipe_id
 * @property string $name 材料名
 * @property string $quantity 分量（例: 200）
 * @property string $unit 単位（例: g / 個 / 大さじ）
 * @property int $order 表示順
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Recipe $recipe
 */
class Ingredient extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'recipe_id',
        'name',
        'quantity',
        'unit',
        'order',
    ];

    /**
     * この材料が属するレシピ
     *
     * @return BelongsTo<Recipe, Ingredient>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
