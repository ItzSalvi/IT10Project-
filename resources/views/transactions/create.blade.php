@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Add New Transaction</h1>
            <p class="mt-2 text-sm text-gray-600">Record your bottle deposit to earn points</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('transactions.store') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="total_bottles" class="block text-sm font-medium text-gray-700">
                    Number of Bottles
                </label>
                <div class="mt-1">
                    <input type="number" 
                           name="total_bottles" 
                           id="total_bottles" 
                           min="1" 
                           required
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('total_bottles') border-red-300 @enderror"
                           value="{{ old('total_bottles') }}"
                           placeholder="Enter number of bottles">
                </div>
                @error('total_bottles')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Points per Bottle
                </label>
                <div class="mt-1">
                    <div class="bg-gray-100 border border-gray-300 rounded-md px-3 py-2 text-sm text-gray-600">
                        {{ $pointsPerBottle }} points per bottle (set by admin)
                    </div>
                </div>
                <p class="mt-1 text-sm text-gray-500">Points per bottle are configured by the administrator</p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            Points Calculation
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Total Points = Number of Bottles × Points per Bottle</p>
                            <p class="font-semibold" id="total-points-preview">Total: 0 points</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('transactions.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Record Transaction
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bottlesInput = document.getElementById('total_bottles');
    const totalPreview = document.getElementById('total-points-preview');
    const pointsPerBottle = {{ $pointsPerBottle }};

    function updateTotal() {
        const bottles = parseInt(bottlesInput.value) || 0;
        const total = bottles * pointsPerBottle;
        totalPreview.textContent = `Total: ${total} points`;
    }

    bottlesInput.addEventListener('input', updateTotal);
    updateTotal();
});
</script>
@endsection
