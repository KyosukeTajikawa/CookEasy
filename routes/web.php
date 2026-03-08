<?php

use App\Http\Controllers\Admin\RecipeController as AdminRecipeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

// ゲスト（認証不要・固定パス）
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');

// ログインユーザー向けルート
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // レシピ CRUD
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

    // マイページ（14_mypage で Controller に置き換え）
    Route::get('/mypage/recipes', fn () => abort(501))->name('mypage.recipes');

    // ブックマーク（09_bookmarks で Controller に置き換え）
    Route::get('/bookmarks', fn () => abort(501))->name('bookmarks.index');
    Route::post('/bookmarks/{recipe}', fn () => abort(501))->name('bookmarks.store');
    Route::delete('/bookmarks/{recipe}', fn () => abort(501))->name('bookmarks.destroy');

    // レビュー（10_reviews で Controller に置き換え）
    Route::post('/recipes/{recipe}/reviews', fn () => abort(501))->name('reviews.store');
    Route::delete('/reviews/{review}', fn () => abort(501))->name('reviews.destroy');

    // クイズ回答（11_quizzes で Controller に置き換え）
    Route::post('/recipes/{recipe}/quiz/answer', fn () => abort(501))->name('quiz.answer');

    // プロフィール（Breeze デフォルト）
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 管理者向けルート（auth + admin）
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // レシピ管理
    Route::get('/recipes', [AdminRecipeController::class, 'index'])->name('recipes.index');
    Route::get('/recipes/create', [AdminRecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [AdminRecipeController::class, 'store'])->name('recipes.store');
    Route::get('/recipes/{recipe}/edit', [AdminRecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{recipe}', [AdminRecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [AdminRecipeController::class, 'destroy'])->name('recipes.destroy');
    Route::patch('/recipes/{recipe}/approve', [AdminRecipeController::class, 'approve'])->name('recipes.approve');
    Route::patch('/recipes/{recipe}/reject', [AdminRecipeController::class, 'reject'])->name('recipes.reject');

    // クイズ管理（11_quizzes で Controller に置き換え）
    Route::get('/recipes/{recipe}/quiz', fn () => abort(501))->name('recipes.quiz.show');
    Route::post('/recipes/{recipe}/quiz', fn () => abort(501))->name('recipes.quiz.store');
    Route::put('/recipes/{recipe}/quiz', fn () => abort(501))->name('recipes.quiz.update');
    Route::delete('/recipes/{recipe}/quiz', fn () => abort(501))->name('recipes.quiz.destroy');

    // レビュー管理（12_admin_panel で Controller に置き換え）
    Route::get('/reviews', fn () => abort(501))->name('reviews.index');
    Route::delete('/reviews/{review}', fn () => abort(501))->name('reviews.destroy');
});

// ゲスト（認証不要・ワイルドカード）※ 固定パスルートの後に定義
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
Route::get('/recipes/{recipe}/quiz', fn () => abort(501))->name('recipes.quiz.show');

require __DIR__.'/auth.php';
