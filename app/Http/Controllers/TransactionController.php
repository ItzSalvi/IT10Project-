<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Auth::user()->transactions()
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $pointsPerBottle = Cache::get('points_per_bottle', 10);
        return view('transactions.create', compact('pointsPerBottle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_bottles' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $pointsPerBottle = Cache::get('points_per_bottle', 10);
            $totalPoints = $request->total_bottles * $pointsPerBottle;

            $transaction = Auth::user()->transactions()->create([
                'total_bottles' => $request->total_bottles,
                'total_points' => $totalPoints,
            ]);

            $transaction->items()->create([
                'quantity' => $request->total_bottles,
                'points_per_bottle' => $pointsPerBottle,
            ]);

            // Update user's total points
            Auth::user()->increment('total_points', $totalPoints);

            DB::commit();

            return redirect()->route('transactions.index')
                ->with('success', 'Transaction recorded successfully! You earned ' . $totalPoints . ' points.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to record transaction. Please try again.');
        }
    }

    public function show(Transaction $transaction)
    {
        // Ensure user can only view their own transactions
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load('items');
        return view('transactions.show', compact('transaction'));
    }
}