<?php

namespace App\Providers;

use App\Models\Recipe;
use App\Models\Review;
use App\Policies\RecipePolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * アプリケーションサービスを登録する
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * アプリケーションサービスをブートストラップする
     *
     * Policy を明示登録する。
     *
     * @return void
     */
    public function boot(): void
    {
        Gate::policy(Recipe::class, RecipePolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
    }
}
