<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Review;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'pending'   => Recipe::where('status', 'pending')->count(),
            'published' => Recipe::where('status', 'published')->count(),
            'users'     => User::count(),
            'reviews'   => Review::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
