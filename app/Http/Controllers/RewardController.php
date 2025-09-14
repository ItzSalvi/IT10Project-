<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::where('status', true)
            ->where('stock', '>', 0)
            ->orderBy('points_req', 'asc')
            ->paginate(12);

        $userPoints = Auth::user()->total_points;

        return view('rewards.index', compact('rewards', 'userPoints'));
    }

    public function create()
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        
        return view('rewards.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'points_req' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
        ]);

        Reward::create([
            'name' => $request->name,
            'description' => $request->description,
            'points_req' => $request->points_req,
            'stock' => $request->stock,
            'status' => true,
        ]);

        return redirect()->route('rewards.index')
            ->with('success', 'Reward created successfully!');
    }

    public function show(Reward $reward)
    {
        $userPoints = Auth::user()->total_points;
        return view('rewards.show', compact('reward', 'userPoints'));
    }

    public function edit(Reward $reward)
    {
        return view('rewards.edit', compact('reward'));
    }

    public function update(Request $request, Reward $reward)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'points_req' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'status' => 'boolean',
        ]);

        $reward->update([
            'name' => $request->name,
            'description' => $request->description,
            'points_req' => $request->points_req,
            'stock' => $request->stock,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('rewards.index')
            ->with('success', 'Reward updated successfully!');
    }

    public function destroy(Reward $reward)
    {
        $reward->delete();
        return redirect()->route('rewards.index')
            ->with('success', 'Reward deleted successfully!');
    }
}