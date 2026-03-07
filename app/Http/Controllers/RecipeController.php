<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Recipe;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RecipeController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $recipes = Recipe::published()
            ->with(['user', 'recipeImages' => fn ($q) => $q->where('is_thumbnail', true)])
            ->latest()
            ->paginate(12);

        return view('recipes.index', compact('recipes'));
    }

    public function show(Recipe $recipe): View
    {
        abort_unless($recipe->status === 'published', 404);

        $recipe->load(['user', 'recipeImages', 'ingredients' => fn ($q) => $q->orderBy('order'), 'steps' => fn ($q) => $q->orderBy('order'), 'reviews.user']);

        return view('recipes.show', compact('recipe'));
    }

    public function create(): View
    {
        return view('recipes.create');
    }

    public function store(StoreRecipeRequest $request): RedirectResponse
    {
        $recipe = Auth::user()->recipes()->create([
            ...$request->validated(),
            'status' => 'pending',
        ]);

        return redirect()->route('recipes.index')
            ->with('success', 'レシピを投稿しました。管理者の承認後に公開されます。');
    }

    public function edit(Recipe $recipe): View
    {
        $this->authorize('update', $recipe);

        $recipe->load(['ingredients' => fn ($q) => $q->orderBy('order'), 'steps' => fn ($q) => $q->orderBy('order')]);

        return view('recipes.edit', compact('recipe'));
    }

    public function update(UpdateRecipeRequest $request, Recipe $recipe): RedirectResponse
    {
        $this->authorize('update', $recipe);

        $recipe->update($request->validated());

        return redirect()->route('recipes.show', $recipe)
            ->with('success', 'レシピを更新しました。');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        $this->authorize('delete', $recipe);

        $recipe->delete();

        return redirect()->route('recipes.index')
            ->with('success', 'レシピを削除しました。');
    }
}
