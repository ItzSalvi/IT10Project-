@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('rewards.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                ← Back to Rewards
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $reward->name }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Reward details and redemption information
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Points Required
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="text-2xl font-bold text-blue-600">{{ $reward->points_req }} points</span>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Stock Available
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reward->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $reward->stock }} {{ $reward->stock == 1 ? 'item' : 'items' }} available
                            </span>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Status
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reward->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $reward->status ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                    @if($reward->description)
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Description
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $reward->description }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Your Points Status
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Your current points: <span class="font-semibold">{{ $userPoints }}</span></p>
                        @if($userPoints >= $reward->points_req)
                            <p class="text-green-600 font-semibold">✓ You have enough points to redeem this reward!</p>
                        @else
                            <p class="text-red-600 font-semibold">You need {{ $reward->points_req - $userPoints }} more points to redeem this reward.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <a href="{{ route('rewards.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Back to Rewards
            </a>
            
            @if($userPoints >= $reward->points_req && $reward->isAvailable())
                <a href="{{ route('redemptions.create', ['reward' => $reward->id]) }}" 
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Redeem This Reward
                </a>
            @elseif($userPoints < $reward->points_req)
                <span class="text-gray-500 text-sm">
                    Need {{ $reward->points_req - $userPoints }} more points
                </span>
            @else
                <span class="text-gray-500 text-sm">
                    This reward is currently unavailable
                </span>
            @endif
        </div>
    </div>
</div>
@endsection



