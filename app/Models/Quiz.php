<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * クイズモデル
 *
 * choices は JSON カラムで array にキャストされる
 *
 * @property int $id
 * @property int $recipe_id
 * @property string $question 問題文
 * @property array<int, string> $choices 選択肢の配列
 * @property string $answer 正解の選択肢
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Recipe $recipe
 */
class Quiz extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'recipe_id',
        'question',
        'choices',
        'answer',
    ];

    protected $casts = [
        'choices' => 'array',
    ];

    /**
     * このクイズが属するレシピ
     *
     * @return BelongsTo<Recipe, Quiz>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
