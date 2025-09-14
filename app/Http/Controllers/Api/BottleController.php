<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BottleController extends Controller
{
    /**
     * Check if a user is logged in
     */
    public function checkLogin($userId)
    {
        try {
            $user = User::find($userId);
            
            if (!$user) {
                return response()->json([
                    'logged_in' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Check if user has an active session (simplified check)
            // In a real implementation, you might want to check session tokens
            return response()->json([
                'logged_in' => true,
                'user_id' => $user->id,
                'user_name' => $user->full_name,
                'total_points' => $user->total_points ?? 0
            ]);

        } catch (\Exception $e) {
            Log::error('Login check failed: ' . $e->getMessage());
            return response()->json([
                'logged_in' => false,
                'message' => 'Error checking login status'
            ], 500);
        }
    }

    /**
     * Handle bottle insertion from Arduino
     */
    public function bottleInserted(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is required'
                ], 400);
            }

            $user = User::find($userId);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Get points per bottle from settings (you might want to store this in a config table)
            $pointsPerBottle = config('app.points_per_bottle', 10); // Default 10 points

            // Create a new transaction
            $transaction = Transaction::create([
                'user_id' => $userId,
                'total_bottles' => 1,
                'total_points' => $pointsPerBottle,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create transaction item
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'item_type' => 'bottle',
                'quantity' => 1,
                'points_per_item' => $pointsPerBottle,
                'total_points' => $pointsPerBottle
            ]);

            // Update user's total points
            $user->increment('total_points', $pointsPerBottle);

            Log::info("Bottle inserted for user {$userId}, awarded {$pointsPerBottle} points");

            return response()->json([
                'success' => true,
                'message' => 'Bottle recorded successfully',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'points_awarded' => $pointsPerBottle,
                    'user_total_points' => $user->fresh()->total_points
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Bottle insertion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error recording bottle insertion'
            ], 500);
        }
    }

    /**
     * Get current user session status (for Arduino connection page)
     */
    public function getSessionStatus(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            
            if (!$userId) {
                return response()->json([
                    'logged_in' => false,
                    'message' => 'User ID is required'
                ], 400);
            }

            $user = User::find($userId);
            
            if (!$user) {
                return response()->json([
                    'logged_in' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'logged_in' => true,
                'user_id' => $user->id,
                'user_name' => $user->full_name,
                'total_points' => $user->total_points ?? 0,
                'points_per_bottle' => config('app.points_per_bottle', 10)
            ]);

        } catch (\Exception $e) {
            Log::error('Session status check failed: ' . $e->getMessage());
            return response()->json([
                'logged_in' => false,
                'message' => 'Error checking session status'
            ], 500);
        }
    }
}


