<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;

class RecipePolicy
{
    /**
     * レシピを編集できるか判定する
     *
     * 投稿者本人または管理者のみ許可。
     *
     * @param User $user
     * @param Recipe $recipe
     * @return bool
     */
    public function update(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id || $user->isAdmin();
    }

    /**
     * レシピを削除できるか判定する
     *
     * 投稿者本人または管理者のみ許可。
     *
     * @param User $user
     * @param Recipe $recipe
     * @return bool
     */
    public function delete(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id || $user->isAdmin();
    }
}
