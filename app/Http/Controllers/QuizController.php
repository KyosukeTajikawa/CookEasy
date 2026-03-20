<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Recipe $recipe): View
    {
        abort_unless($recipe->status === 'published', 404);

        $quiz = $recipe->quiz;

        abort_if(is_null($quiz), 404);

        return view('recipes.quiz', compact('recipe', 'quiz'));
    }

    public function answer(Request $request, Recipe $recipe): RedirectResponse
    {
        $quiz = $recipe->quiz;

        abort_if(is_null($quiz), 404);

        $request->validate([
            'answer' => ['required', 'string'],
        ]);

        $isCorrect = $request->answer === $quiz->answer;

        return redirect()->route('recipes.quiz.show', $recipe)
            ->with('quiz_result', $isCorrect ? 'correct' : 'incorrect')
            ->with('selected_answer', $request->answer);
    }
}
