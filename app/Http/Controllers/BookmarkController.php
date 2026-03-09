<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookmarkController extends Controller
{
    public function index(): View
    {
        $recipes = Recipe::published()
            ->whereHas('bookmarks', fn ($q) => $q->where('user_id', Auth::id()))
            ->with(['user', 'recipeImages' => fn ($q) => $q->where('is_thumbnail', true)])
            ->latest()
            ->paginate(12);

        return view('bookmarks.index', compact('recipes'));
    }

    public function store(Recipe $recipe): RedirectResponse
    {
        Auth::user()->bookmarks()->firstOrCreate(['recipe_id' => $recipe->id]);

        return back()->with('success', 'ブックマークに追加しました。');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        Auth::user()->bookmarks()->where('recipe_id', $recipe->id)->delete();

        return back()->with('success', 'ブックマークを解除しました。');
    }
}
