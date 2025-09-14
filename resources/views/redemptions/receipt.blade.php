@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('redemptions.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Redemptions
            </a>
        </div>

        <!-- Receipt Header -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-4">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 py-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-white">Redemption Receipt</h1>
                        <p class="text-green-100 text-sm">Transaction #{{ $redemption->id }}</p>
                    </div>
                    <div class="text-right text-white">
                        <p class="text-xs">{{ $redemption->created_at->format('M d, Y') }}</p>
                        <p class="text-xs">{{ $redemption->created_at->format('h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Receipt Content -->
            <div class="p-4">
                <!-- User Information -->
                <div class="mb-4">
                    <h2 class="text-md font-semibold text-gray-900 mb-2">Customer Information</h2>
                    <div class="bg-gray-50 rounded p-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-600">Name:</p>
                                <p class="font-medium text-gray-900 text-sm">{{ $redemption->user->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Email:</p>
                                <p class="font-medium text-gray-900 text-sm">{{ $redemption->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Redemption Items -->
                <div class="mb-4">
                    <h2 class="text-md font-semibold text-gray-900 mb-2">Redeemed Items</h2>
                    <div class="space-y-2">
                        @foreach($redemption->items as $item)
                            <div class="bg-gray-50 rounded p-3 flex justify-between items-center">
                                <div class="flex-1">
                                    <div class="font-medium text-sm text-gray-900">{{ $item->reward->name }}</div>
                                    @if($item->reward->description)
                                        <div class="text-xs text-gray-600">{{ $item->reward->description }}</div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->quantity }} × {{ $item->reward->points_req }} pts</div>
                                    <div class="text-sm font-bold text-blue-600">{{ $item->points_spent }} pts</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-gray-50 rounded p-4 mb-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-md font-semibold text-gray-900">Total Points Spent</h3>
                            <p class="text-xs text-gray-600">Points deducted from your account</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-red-600">{{ $redemption->total_points_spent }} pts</div>
                            <div class="text-xs text-gray-600">Remaining: {{ $redemption->user->total_points }} pts</div>
                        </div>
                    </div>
                </div>

                <!-- Important Information -->
                <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-2">
                            <h3 class="text-xs font-medium text-yellow-800">Important Information</h3>
                            <div class="mt-1 text-xs text-yellow-700">
                                <ul class="list-disc list-inside space-y-0.5">
                                    <li>Valid for <strong>30 days</strong> - expires {{ $redemption->created_at->addDays(30)->format('M d, Y') }}</li>
                                    <li>Present to admin office to claim items</li>
                                    <li>Keep this receipt safe</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-between items-center">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('redemptions.download-receipt', $redemption) }}" 
                           class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </a>
                        <button onclick="window.print()" 
                                class="inline-flex items-center px-3 py-2 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code for Verification (Optional) -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900">Receipt Verification</h3>
            </div>
            <div class="p-4 text-center">
                <div class="inline-block p-3 bg-gray-100 rounded">
                    <div class="w-24 h-24 bg-gray-300 rounded flex items-center justify-center">
                        <span class="text-gray-500 text-xs">QR Code</span>
                    </div>
                </div>
                <p class="mt-3 text-xs text-gray-600">
                    Receipt ID: <span class="font-mono font-medium">{{ $redemption->id }}</span>
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Scan at admin office for verification
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .container {
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .shadow-lg {
        box-shadow: none !important;
    }
    
    .bg-gradient-to-r {
        background: #10b981 !important;
    }
}
</style>
@endsection
