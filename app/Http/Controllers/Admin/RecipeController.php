<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RecipeController extends Controller
{
    public function index(): View
    {
        $recipes = Recipe::with('user')
            ->orderByRaw("FIELD(status, 'pending', 'published', 'rejected')")
            ->latest()
            ->paginate(20);

        return view('admin.recipes.index', compact('recipes'));
    }

    public function create(): View
    {
        return view('admin.recipes.create');
    }

    public function store(StoreRecipeRequest $request): RedirectResponse
    {
        $recipe = Auth::user()->recipes()->create([
            ...$request->safe()->except('images'),
            'status' => 'published',
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

        return redirect()->route('admin.recipes.index')
            ->with('success', 'レシピを公開しました。');
    }

    public function edit(Recipe $recipe): View
    {
        return view('admin.recipes.edit', compact('recipe'));
    }

    public function update(UpdateRecipeRequest $request, Recipe $recipe): RedirectResponse
    {
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

        return redirect()->route('admin.recipes.index')
            ->with('success', 'レシピを更新しました。');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        foreach ($recipe->recipeImages as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $recipe->delete();

        return redirect()->route('admin.recipes.index')
            ->with('success', 'レシピを削除しました。');
    }

    public function approve(Recipe $recipe): RedirectResponse
    {
        $recipe->update(['status' => 'published']);

        return redirect()->route('admin.recipes.index')
            ->with('success', 'レシピを承認しました。');
    }

    public function reject(Recipe $recipe): RedirectResponse
    {
        $recipe->update(['status' => 'rejected']);

        return redirect()->route('admin.recipes.index')
            ->with('success', 'レシピを却下しました。');
    }
}
