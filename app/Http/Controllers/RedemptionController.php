<?php

namespace App\Http\Controllers;

use App\Models\Redemption;
use App\Models\RedemptionItem;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RedemptionController extends Controller
{
    public function index()
    {
        $redemptions = Auth::user()->redemptions()
            ->with('items.reward')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('redemptions.index', compact('redemptions'));
    }

    public function create()
    {
        $rewards = Reward::where('status', true)
            ->where('stock', '>', 0)
            ->orderBy('points_req', 'asc')
            ->get();

        $userPoints = Auth::user()->total_points;

        return view('redemptions.create', compact('rewards', 'userPoints'));
    }

    public function store(Request $request)
    {
        // Debug: Log the request data
        \Log::info('Redemption request data:', $request->all());
        
        // Filter out items with quantity 0
        $selectedRewards = collect($request->rewards)->filter(function ($item) {
            return isset($item['quantity']) && (int)$item['quantity'] > 0;
        });

        \Log::info('Selected rewards after filtering:', $selectedRewards->toArray());

        if ($selectedRewards->isEmpty()) {
            return back()->with('error', 'Please select at least one item to redeem.');
        }

        $user = Auth::user();
        $totalPointsSpent = 0;
        $redemptionItems = [];

        \Log::info('Starting redemption process for user: ' . $user->id);
        
        DB::beginTransaction();
        try {
            // Validate and calculate total points
            foreach ($selectedRewards as $item) {
                \Log::info('Processing reward: ' . $item['reward_id'] . ' with quantity: ' . $item['quantity']);
                
                $reward = Reward::findOrFail($item['reward_id']);
                
                if (!$reward->isAvailable()) {
                    throw new \Exception("Reward '{$reward->name}' is not available.");
                }

                if ($reward->stock < $item['quantity']) {
                    throw new \Exception("Not enough stock for '{$reward->name}'. Available: {$reward->stock}");
                }

                $pointsForThisItem = $reward->points_req * $item['quantity'];
                $totalPointsSpent += $pointsForThisItem;

                $redemptionItems[] = [
                    'reward_id' => $reward->id,
                    'quantity' => $item['quantity'],
                    'points_spent' => $pointsForThisItem,
                ];
            }

            \Log::info('Total points to spend: ' . $totalPointsSpent . ', User points: ' . $user->total_points);

            if ($user->total_points < $totalPointsSpent) {
                throw new \Exception("Insufficient points. You have {$user->total_points} points, but need {$totalPointsSpent} points.");
            }

            // Create redemption
            $redemption = $user->redemptions()->create([
                'total_points_spent' => $totalPointsSpent,
                'status' => 'completed',
            ]);

            // Create redemption items and update stock
            foreach ($redemptionItems as $item) {
                $redemption->items()->create($item);
                
                $reward = Reward::find($item['reward_id']);
                $reward->decrement('stock', $item['quantity']);
            }

            // Update user's total points
            $user->decrement('total_points', $totalPointsSpent);

            DB::commit();

            \Log::info('Redemption created successfully with ID: ' . $redemption->id);

            // Generate receipt and redirect to receipt page
            \Log::info('Redirecting to receipt page for redemption: ' . $redemption->id);
            return redirect()->route('redemptions.receipt', $redemption)
                ->with('success', "Redemption successful! You spent {$totalPointsSpent} points.");
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Redemption failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Redemption $redemption)
    {
        // Ensure user can only view their own redemptions
        if ($redemption->user_id !== Auth::id()) {
            abort(403);
        }

        $redemption->load('items.reward');
        return view('redemptions.show', compact('redemption'));
    }

    public function receipt(Redemption $redemption)
    {
        // Ensure user can only view their own redemptions
        if ($redemption->user_id !== Auth::id()) {
            abort(403);
        }

        $redemption->load('items.reward', 'user');
        return view('redemptions.receipt', compact('redemption'));
    }

    public function downloadReceipt(Redemption $redemption)
    {
        // Ensure user can only download their own redemptions
        if ($redemption->user_id !== Auth::id()) {
            abort(403);
        }

        $redemption->load('items.reward', 'user');
        
        // Generate PDF receipt
        $pdf = Pdf::loadView('redemptions.receipt-pdf', compact('redemption'));
        
        $filename = 'redemption_receipt_' . $redemption->id . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}