@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Welcome back, {{ Auth::user()->full_name }}!</h1>
        @if(Auth::user()->isAdmin())
            <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Admin Dashboard - Manage system settings and monitor user activity</p>
        @else
            <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Manage your bottle recycling and rewards</p>
        @endif
    </div>

    @if(Auth::user()->isAdmin())
        <!-- Admin System Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold">Total Users</h2>
                        <p class="text-3xl font-bold mt-2">{{ \App\Models\User::count() }}</p>
                        <p class="text-blue-100 mt-1">Registered users</p>
                    </div>
                    <div class="text-right">
                        <svg class="h-12 w-12 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold">Total Transactions</h2>
                        <p class="text-3xl font-bold mt-2">{{ \App\Models\Transaction::count() }}</p>
                        <p class="text-blue-100 mt-1">Bottle deposits</p>
                    </div>
                    <div class="text-right">
                        <svg class="h-12 w-12 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold">Active Rewards</h2>
                        <p class="text-3xl font-bold mt-2">{{ \App\Models\Reward::where('status', true)->count() }}</p>
                        <p class="text-blue-100 mt-1">Available rewards</p>
                    </div>
                    <div class="text-right">
                        <svg class="h-12 w-12 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- User Points Summary -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">Your Points</h2>
                    <p class="text-4xl font-bold mt-2">{{ Auth::user()->total_points }}</p>
                    <p class="text-blue-100 mt-1">Total points earned from recycling</p>
                </div>
                <div class="text-right">
                    <svg class="h-16 w-16 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                </div>
            </div>
        </div>
    @endif

    @if(Auth::user()->isAdmin())
        <!-- Admin Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Manage Rewards</h3>
                        <p class="text-sm text-gray-600">Add, edit, and manage rewards</p>
                        <a href="{{ route('rewards.index') }}" class="mt-2 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Manage
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">View Users</h3>
                        <p class="text-sm text-gray-600">Monitor user activity and data</p>
                        <a href="{{ route('admin.users') }}" class="mt-2 inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                            View Users
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">System Settings</h3>
                        <p class="text-sm text-gray-600">Configure system parameters</p>
                        <a href="{{ route('admin.settings') }}" class="mt-2 inline-block bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- User Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Add Transaction</h3>
                        <p class="text-sm text-gray-600">Record bottle deposits</p>
                        <a href="{{ route('transactions.create') }}" class="mt-2 inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Add Now
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">View Rewards</h3>
                        <p class="text-sm text-gray-600">Browse available rewards</p>
                        <a href="{{ route('rewards.index') }}" class="mt-2 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                            View All
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Redeem Rewards</h3>
                        <p class="text-sm text-gray-600">Use your points</p>
                        <a href="{{ route('redemptions.create') }}" class="mt-2 inline-block bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Redeem Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @if(Auth::user()->isAdmin())
            <!-- Admin: Recent User Transactions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent User Transactions</h3>
                <div class="space-y-3">
                    @php
                        $recentTransactions = \App\Models\Transaction::with('user')->latest()->limit(3)->get();
                    @endphp
                    @if($recentTransactions->count() > 0)
                        @foreach($recentTransactions as $transaction)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $transaction->user->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $transaction->total_bottles }} bottles • {{ $transaction->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-green-600">+{{ $transaction->total_points }} pts</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-sm">No transactions yet</p>
                    @endif
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.transactions') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View all transactions →
                    </a>
                </div>
            </div>

            <!-- Admin: New Users -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">New Users</h3>
                <div class="space-y-3">
                    @php
                        $newUsers = \App\Models\User::latest()->limit(3)->get();
                    @endphp
                    @if($newUsers->count() > 0)
                        @foreach($newUsers as $user)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->full_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }} • {{ $user->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-blue-600">{{ $user->total_points }} pts</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-sm">No users yet</p>
                    @endif
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View all users →
                    </a>
                </div>
            </div>
        @else
            <!-- User: Recent Transactions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Transactions</h3>
                <div class="space-y-3">
                    @php
                        $recentTransactions = Auth::user()->transactions()->latest()->limit(3)->get();
                    @endphp
                    @if($recentTransactions->count() > 0)
                        @foreach($recentTransactions as $transaction)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $transaction->total_bottles }} bottles</p>
                                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-green-600">+{{ $transaction->total_points }} pts</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-sm">No transactions yet</p>
                    @endif
                </div>
                <div class="mt-4">
                    <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View all transactions →
                    </a>
                </div>
            </div>

            <!-- User: Recent Redemptions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Redemptions</h3>
                <div class="space-y-3">
                    @php
                        $recentRedemptions = Auth::user()->redemptions()->latest()->limit(3)->get();
                    @endphp
                    @if($recentRedemptions->count() > 0)
                        @foreach($recentRedemptions as $redemption)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="h-4 w-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Redemption #{{ $redemption->id }}</p>
                                        <p class="text-xs text-gray-500">{{ $redemption->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-orange-600">-{{ $redemption->total_points_spent }} pts</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-sm">No redemptions yet</p>
                    @endif
                </div>
                <div class="mt-4">
                    <a href="{{ route('redemptions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View all redemptions →
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
