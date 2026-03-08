<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Recipe;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            ...$request->safe()->except('images'),
            'status' => 'pending',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $recipe->recipeImages()->create([
                    'image_path'   => $file->store('recipe_images', 'public'),
                    'order'        => $index + 1,
                    'is_thumbnail' => $index === 0,
                ]);
            }
        }

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

        $recipe->update($request->safe()->except('images'));

        if ($request->hasFile('images')) {
            foreach ($recipe->recipeImages as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->forceDelete();
            }

            foreach ($request->file('images') as $index => $file) {
                $recipe->recipeImages()->create([
                    'image_path'   => $file->store('recipe_images', 'public'),
                    'order'        => $index + 1,
                    'is_thumbnail' => $index === 0,
                ]);
            }
        }

        return redirect()->route('recipes.show', $recipe)
            ->with('success', 'レシピを更新しました。');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        $this->authorize('delete', $recipe);

        foreach ($recipe->recipeImages as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $recipe->delete();

        return redirect()->route('recipes.index')
            ->with('success', 'レシピを削除しました。');
    }
}
