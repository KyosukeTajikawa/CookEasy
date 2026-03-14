<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index(): View
    {
        $recipes = Auth::user()
            ->recipes()
            ->with(['recipeImages' => fn ($q) => $q->where('is_thumbnail', true)])
            ->latest()
            ->get();

        return view('mypage.recipes', compact('recipes'));
    }
}
