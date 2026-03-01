<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ブックマークモデル
 *
 * updated_at は持たない（UPDATED_AT = null）
 * (user_id, recipe_id) にユニーク制約あり
 *
 * @property int $id
 * @property int $user_id
 * @property int $recipe_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read User $user
 * @property-read Recipe $recipe
 */
class Bookmark extends Model
{
    use SoftDeletes;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'recipe_id',
    ];

    /**
     * ブックマークしたユーザー
     *
     * @return BelongsTo<User, Bookmark>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ブックマークされたレシピ
     *
     * @return BelongsTo<Recipe, Bookmark>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
