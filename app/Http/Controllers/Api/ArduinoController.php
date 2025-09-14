<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArduinoController extends Controller
{
    /**
     * Authenticate Arduino device with user session
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'device_id' => 'required|string|max:255'
        ]);

        $user = User::find($request->user_id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Store device session
        Cache::put("arduino_session_{$request->device_id}", [
            'user_id' => $user->id,
            'authenticated_at' => now(),
            'last_activity' => now()
        ], now()->addHours(2)); // 2 hour session

        Log::info("Arduino device authenticated", [
            'device_id' => $request->device_id,
            'user_id' => $user->id,
            'user_name' => $user->full_name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device authenticated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->full_name,
                'current_points' => $user->total_points
            ],
            'points_per_bottle' => Cache::get('points_per_bottle', 10)
        ]);
    }

    /**
     * Record bottle detection from Arduino
     */
    public function detectBottle(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string|max:255',
            'bottle_count' => 'required|integer|min:1|max:10' // Max 10 bottles per detection
        ]);

        // Check if device is authenticated
        $session = Cache::get("arduino_session_{$request->device_id}");
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Device not authenticated. Please authenticate first.'
            ], 401);
        }

        $user = User::find($session['user_id']);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Update last activity
        $session['last_activity'] = now();
        Cache::put("arduino_session_{$request->device_id}", $session, now()->addHours(2));

        $pointsPerBottle = Cache::get('points_per_bottle', 10);
        $totalPoints = $request->bottle_count * $pointsPerBottle;

        DB::beginTransaction();
        try {
            // Create transaction
            $transaction = $user->transactions()->create([
                'total_bottles' => $request->bottle_count,
                'total_points' => $totalPoints,
            ]);

            // Create transaction item
            $transaction->items()->create([
                'quantity' => $request->bottle_count,
                'points_per_bottle' => $pointsPerBottle,
            ]);

            // Update user's total points
            $user->increment('total_points', $totalPoints);

            DB::commit();

            Log::info("Bottle detection recorded", [
                'device_id' => $request->device_id,
                'user_id' => $user->id,
                'bottle_count' => $request->bottle_count,
                'points_earned' => $totalPoints,
                'transaction_id' => $transaction->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bottles detected and points awarded',
                'data' => [
                    'bottles_detected' => $request->bottle_count,
                    'points_earned' => $totalPoints,
                    'user_total_points' => $user->fresh()->total_points,
                    'transaction_id' => $transaction->id
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Failed to record bottle detection", [
                'device_id' => $request->device_id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to record bottle detection'
            ], 500);
        }
    }

    /**
     * Get current user session status
     */
    public function getSessionStatus(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string|max:255'
        ]);

        $session = Cache::get("arduino_session_{$request->device_id}");
        
        if (!$session) {
            return response()->json([
                'authenticated' => false,
                'message' => 'No active session'
            ]);
        }

        $user = User::find($session['user_id']);
        
        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->full_name,
                'current_points' => $user->total_points
            ],
            'session' => [
                'authenticated_at' => $session['authenticated_at'],
                'last_activity' => $session['last_activity']
            ],
            'points_per_bottle' => Cache::get('points_per_bottle', 10)
        ]);
    }

    /**
     * Logout Arduino device
     */
    public function logout(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string|max:255'
        ]);

        Cache::forget("arduino_session_{$request->device_id}");

        Log::info("Arduino device logged out", [
            'device_id' => $request->device_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device logged out successfully'
        ]);
    }
}
