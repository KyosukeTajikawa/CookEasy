<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Recipe;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RecipeController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $difficulty = $request->input('difficulty');
        $cookTime   = $request->integer('cook_time') ?: null;
        $ingredient = $request->input('ingredient');

        $recipes = Recipe::published()
            ->filterByDifficulty($difficulty)
            ->filterByCookTime($cookTime)
            ->filterByIngredient($ingredient)
            ->with(['user', 'recipeImages' => fn ($q) => $q->where('is_thumbnail', true)])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('recipes.index', compact('recipes'));
    }

    public function show(Recipe $recipe): View
    {
        abort_unless($recipe->status === 'published', 404);

        $recipe->load(['user', 'recipeImages', 'ingredients' => fn ($q) => $q->orderBy('order'), 'steps' => fn ($q) => $q->orderBy('order'), 'reviews.user']);

        $isBookmarked = Auth::check()
            && $recipe->bookmarks()->where('user_id', Auth::id())->exists();

        return view('recipes.show', compact('recipe', 'isBookmarked'));
    }

    public function create(): View
    {
        return view('recipes.create');
    }

    public function store(StoreRecipeRequest $request): RedirectResponse
    {
        $recipe = Auth::user()->recipes()->create([
            ...$request->safe()->only(['title', 'description', 'cook_time', 'difficulty']),
            'status' => 'pending',
        ]);

        $this->saveImages($request, $recipe);
        $this->saveIngredients($request, $recipe);
        $this->saveSteps($request, $recipe);

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

        $recipe->update($request->safe()->only(['title', 'description', 'cook_time', 'difficulty']));

        if ($request->hasFile('images')) {
            foreach ($recipe->recipeImages as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->forceDelete();
            }
            $this->saveImages($request, $recipe);
        }

        foreach ($recipe->ingredients as $ingredient) {
            $ingredient->forceDelete();
        }
        foreach ($recipe->steps as $step) {
            if ($step->image_path) {
                Storage::disk('public')->delete($step->image_path);
            }
            $step->forceDelete();
        }
        $this->saveIngredients($request, $recipe);
        $this->saveSteps($request, $recipe);

        return redirect()->route('recipes.show', $recipe)
            ->with('success', 'レシピを更新しました。');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        $this->authorize('delete', $recipe);

        foreach ($recipe->recipeImages as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        foreach ($recipe->steps as $step) {
            if ($step->image_path) {
                Storage::disk('public')->delete($step->image_path);
            }
        }

        $recipe->delete();

        return redirect()->route('recipes.index')
            ->with('success', 'レシピを削除しました。');
    }

    private function saveImages($request, Recipe $recipe): void
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $recipe->recipeImages()->create([
                    'image_path'   => $file->store('recipe_images', 'public'),
                    'order'        => $index + 1,
                    'is_thumbnail' => $index === 0,
                ]);
            }
        }
    }

    private function saveIngredients($request, Recipe $recipe): void
    {
        $order = 1;
        foreach ($request->input('ingredients', []) as $ingredient) {
            if (empty($ingredient['name'])) {
                continue;
            }
            $recipe->ingredients()->create([
                'name'     => $ingredient['name'],
                'quantity' => $ingredient['quantity'] ?? '',
                'unit'     => $ingredient['unit'] ?? '',
                'order'    => $order++,
            ]);
        }
    }

    private function saveSteps($request, Recipe $recipe): void
    {
        $stepImages = $request->file('step_images', []);
        $order = 1;
        foreach ($request->input('steps', []) as $index => $step) {
            if (empty($step['description'])) {
                continue;
            }
            $imagePath = null;
            if (!empty($stepImages[$index])) {
                $imagePath = $stepImages[$index]->store('step_images', 'public');
            }
            $recipe->steps()->create([
                'description' => $step['description'],
                'image_path'  => $imagePath,
                'order'       => $order++,
            ]);
        }
    }
}
