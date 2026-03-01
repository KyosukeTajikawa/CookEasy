<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * レシピモデル
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property int $cook_time 調理時間（分）
 * @property string $difficulty 超簡単|簡単|普通
 * @property string $status pending|published|rejected
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, RecipeImage> $recipeImages
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Ingredient> $ingredients
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Step> $steps
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Bookmark> $bookmarks
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Review> $reviews
 * @property-read Quiz|null $quiz
 */
class Recipe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'cook_time',
        'difficulty',
        'status',
    ];

    /**
     * モデルイベントの登録
     *
     * deleting: 論理削除時に子レコード（recipeImages / ingredients / steps / quiz）も論理削除する
     * restoring: 復元時に子レコードも復元する
     */
    protected static function booted(): void
    {
        static::deleting(function (Recipe $recipe) {
            $recipe->recipeImages()->each(fn ($m) => $m->delete());
            $recipe->ingredients()->each(fn ($m) => $m->delete());
            $recipe->steps()->each(fn ($m) => $m->delete());
            $recipe->quiz()?->delete();
        });

        static::restoring(function (Recipe $recipe) {
            $recipe->recipeImages()->withTrashed()->each(fn ($m) => $m->restore());
            $recipe->ingredients()->withTrashed()->each(fn ($m) => $m->restore());
            $recipe->steps()->withTrashed()->each(fn ($m) => $m->restore());
            $recipe->quiz()->withTrashed()->first()?->restore();
        });
    }

    /**
     * 公開済みレシピのみに絞り込むスコープ
     *
     * @param Builder<Recipe> $query
     * @return Builder<Recipe>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * レシピを投稿したユーザー
     *
     * @return BelongsTo<User, Recipe>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * このレシピに紐づく画像一覧
     *
     * @return HasMany<RecipeImage>
     */
    public function recipeImages(): HasMany
    {
        return $this->hasMany(RecipeImage::class);
    }

    /**
     * このレシピの材料一覧
     *
     * @return HasMany<Ingredient>
     */
    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }

    /**
     * このレシピの手順一覧
     *
     * @return HasMany<Step>
     */
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    /**
     * このレシピのブックマーク一覧
     *
     * @return HasMany<Bookmark>
     */
    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * このレシピのレビュー一覧
     *
     * @return HasMany<Review>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * このレシピに紐づくクイズ
     *
     * @return HasOne<Quiz>
     */
    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }
}
