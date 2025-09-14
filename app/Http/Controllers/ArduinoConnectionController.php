<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ArduinoConnectionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeSessions = $this->getUserActiveSessions($user->id);
        
        return view('arduino.connection', compact('activeSessions'));
    }

    public function connect(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string|max:255'
        ]);

        $user = Auth::user();
        $deviceId = $request->device_id;

        // Store device session
        Cache::put("arduino_session_{$deviceId}", [
            'user_id' => $user->id,
            'authenticated_at' => now(),
            'last_activity' => now()
        ], now()->addHours(2));

        return redirect()->route('arduino.connection')->with('success', 
            "Device '{$deviceId}' connected successfully! You can now use your Arduino to detect bottles.");
    }

    public function disconnect(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string|max:255'
        ]);

        $deviceId = $request->device_id;
        Cache::forget("arduino_session_{$deviceId}");

        return redirect()->route('arduino.connection')->with('success', 
            "Device '{$deviceId}' disconnected successfully!");
    }

    private function getUserActiveSessions($userId)
    {
        $sessions = [];
        
        // This is a simplified approach. In production, you might want to store
        // device sessions in a database for better tracking
        $cacheKeys = Cache::get('arduino_sessions', []);
        
        foreach ($cacheKeys as $key) {
            $session = Cache::get($key);
            if ($session && $session['user_id'] == $userId) {
                $deviceId = str_replace('arduino_session_', '', $key);
                $sessions[] = [
                    'device_id' => $deviceId,
                    'authenticated_at' => $session['authenticated_at'],
                    'last_activity' => $session['last_activity']
                ];
            }
        }

        return $sessions;
    }
}
