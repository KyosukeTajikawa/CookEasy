<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Models\Recipe;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class QuizController extends Controller
{
    public function create(Recipe $recipe): View
    {
        return view('admin.quizzes.create', compact('recipe'));
    }

    public function store(StoreQuizRequest $request, Recipe $recipe): RedirectResponse
    {
        $recipe->quiz()->create($request->validated());

        return redirect()->route('admin.recipes.edit', $recipe)
            ->with('success', 'クイズを作成しました。');
    }

    public function edit(Recipe $recipe): View
    {
        $quiz = $recipe->quiz;

        abort_if(is_null($quiz), 404);

        return view('admin.quizzes.edit', compact('recipe', 'quiz'));
    }

    public function update(StoreQuizRequest $request, Recipe $recipe): RedirectResponse
    {
        $recipe->quiz->update($request->validated());

        return redirect()->route('admin.recipes.edit', $recipe)
            ->with('success', 'クイズを更新しました。');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        $recipe->quiz?->delete();

        return redirect()->route('admin.recipes.edit', $recipe)
            ->with('success', 'クイズを削除しました。');
    }
}
