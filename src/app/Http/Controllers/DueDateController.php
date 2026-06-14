<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class DueDateController extends Controller
{
        public function index()
        {
             $userId = Auth::id();

            $overdue = Todo::where('user_id', $userId)
                ->where('status', '!=', 'completed')
                ->whereNotNull('deadline')
                ->where('deadline', '<', now()->startOfDay())
                ->with('category')
                ->latest('deadline')
                ->get();

            $today = Todo::where('user_id', $userId)
                ->where('status', '!=', 'completed')
                ->whereNotNull('deadline')
                ->whereDate('deadline', today())
                ->with('category')
                ->latest('deadline')
                ->get();

            $tomorrow = Todo::where('user_id', $userId)
                ->where('status', '!=', 'completed')
                ->whereNotNull('deadline')
                ->whereDate('deadline', today()->addDay())
                ->with('category')
                ->latest('deadline')
                ->get();
                
            $later = Todo::where('user_id', $userId)
                ->where('status', '!=', 'completed')
                ->whereNotNull('deadline')
                ->where('deadline', '>', today()->addDays(1)->endOfDay())
                ->with('category')
                ->latest('deadline')
                ->get();

            return view('due-dates.index', compact('overdue', 'today', 'tomorrow','later'));
        
        }
}
