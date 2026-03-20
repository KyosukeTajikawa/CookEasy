<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Recipe;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Recipe $recipe): RedirectResponse
    {
        $alreadyReviewed = $recipe->reviews()
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'このレシピにはすでにレビューを投稿しています。');
        }

        $recipe->reviews()->create([
            'user_id' => Auth::id(),
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'レビューを投稿しました。');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $this->authorize('delete', $review);

        $review->delete();

        return back()->with('success', 'レビューを削除しました。');
    }
}
