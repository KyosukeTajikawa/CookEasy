<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * レビューを削除できるか判定する
     *
     * 投稿者本人または管理者のみ許可。
     *
     * @param User $user
     * @param Review $review
     * @return bool
     */
    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->user_id || $user->isAdmin();
    }
}
