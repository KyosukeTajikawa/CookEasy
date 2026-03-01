<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * レビューモデル
 *
 * (user_id, recipe_id) にユニーク制約あり（1ユーザー1レシピにつき1件）
 *
 * @property int $id
 * @property int $user_id
 * @property int $recipe_id
 * @property int $rating 評価（1〜5）
 * @property string $comment レビューコメント
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read User $user
 * @property-read Recipe $recipe
 */
class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'recipe_id',
        'rating',
        'comment',
    ];

    /**
     * レビューを投稿したユーザー
     *
     * @return BelongsTo<User, Review>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * レビュー対象のレシピ
     *
     * @return BelongsTo<Recipe, Review>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
