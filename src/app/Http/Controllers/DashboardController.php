<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $title = "Dashboard";
        $subtitle ="Overview";
        
        $category = Category::where('user_id', Auth::id())
        ->latest()
        ->get();

        return view('dashboard', compact('title', 'subtitle', 'category'));
    }
}
    