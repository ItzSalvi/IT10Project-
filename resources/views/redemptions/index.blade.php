@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">My Redemptions</h1>
        <a href="{{ route('redemptions.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Redeem Rewards
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if($redemptions->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($redemptions as $redemption)
                    <li>
                        <div class="hover:bg-gray-50 px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 bg-orange-100 rounded-full flex items-center justify-center">
                                            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            Redemption #{{ $redemption->id }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $redemption->created_at->format('M d, Y \a\t g:i A') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $redemption->items->count() }} {{ $redemption->items->count() == 1 ? 'item' : 'items' }} redeemed
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-orange-600">
                                            -{{ $redemption->total_points_spent }} points
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ ucfirst($redemption->status) }}
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <a href="{{ route('redemptions.receipt', $redemption) }}" 
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Receipt
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No redemptions</h3>
                <p class="mt-1 text-sm text-gray-500">Start redeeming rewards to see them here.</p>
                <div class="mt-6">
                    <a href="{{ route('redemptions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        Redeem Rewards
                    </a>
                </div>
            </div>
        @endif
    </div>

    @if($redemptions->hasPages())
        <div class="mt-6">
            {{ $redemptions->links() }}
        </div>
    @endif
</div>
@endsection
