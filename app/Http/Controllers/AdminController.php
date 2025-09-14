<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Reward;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function settings()
    {
        $pointsPerBottle = Cache::get('points_per_bottle', 10); // Default 10 points per bottle
        
        return view('admin.settings', compact('pointsPerBottle'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'points_per_bottle' => 'required|integer|min:1|max:100'
        ]);

        Cache::put('points_per_bottle', $request->points_per_bottle, now()->addYear());

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully!');
    }

    public function users()
    {
        $users = User::withCount(['transactions', 'redemptions'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate statistics
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $totalRedemptions = \App\Models\Redemption::count();
        
        // Daily, monthly, yearly stats
        $todayTransactions = Transaction::whereDate('created_at', today())->count();
        $monthlyTransactions = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $yearlyTransactions = Transaction::whereYear('created_at', now()->year)->count();

        return view('admin.users', compact('users', 'totalUsers', 'totalTransactions', 'totalRedemptions', 'todayTransactions', 'monthlyTransactions', 'yearlyTransactions'));
    }

    public function transactions()
    {
        $transactions = Transaction::with(['user', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate statistics
        $totalTransactions = Transaction::count();
        $totalBottles = Transaction::sum('total_bottles');
        $totalPoints = Transaction::sum('total_points');
        
        // Daily, monthly, yearly stats
        $todayTransactions = Transaction::whereDate('created_at', today())->count();
        $monthlyTransactions = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $yearlyTransactions = Transaction::whereYear('created_at', now()->year)->count();

        // Recent activity
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.transactions', compact('transactions', 'totalTransactions', 'totalBottles', 'totalPoints', 'todayTransactions', 'monthlyTransactions', 'yearlyTransactions', 'recentTransactions'));
    }
}
