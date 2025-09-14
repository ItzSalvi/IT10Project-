@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Redeem Rewards</h1>
            <p class="mt-2 text-sm text-gray-600">Your current points: <span class="font-semibold text-blue-600">{{ $userPoints }}</span></p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($rewards->count() > 0)
            <form method="POST" action="{{ route('redemptions.store') }}" id="redemption-form">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($rewards as $reward)
                        <div class="bg-white rounded-lg shadow-md border-2 border-gray-200 hover:border-blue-300 transition-colors duration-200">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $reward->name }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $reward->stock }} in stock
                                    </span>
                                </div>
                                
                                @if($reward->description)
                                    <p class="text-sm text-gray-600 mb-4">{{ $reward->description }}</p>
                                @endif

                                <div class="flex items-center justify-between mb-4">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ $reward->points_req }} pts
                                    </div>
                                    @if($userPoints >= $reward->points_req && $reward->isAvailable())
                                        <div class="text-green-600 text-sm font-medium">
                                            ✓ Available
                                        </div>
                                    @elseif($userPoints < $reward->points_req)
                                        <div class="text-red-600 text-sm font-medium">
                                            Need {{ $reward->points_req - $userPoints }} more pts
                                        </div>
                                    @else
                                        <div class="text-gray-500 text-sm font-medium">
                                            Out of stock
                                        </div>
                                    @endif
                                </div>

                                @if($userPoints >= $reward->points_req && $reward->isAvailable())
                                    <div class="flex items-center space-x-2">
                                        <label for="reward_{{ $reward->id }}" class="text-sm font-medium text-gray-700">
                                            Quantity:
                                        </label>
                                        <input type="number" 
                                               id="reward_{{ $reward->id }}" 
                                               name="rewards[{{ $reward->id }}][quantity]" 
                                               min="0" 
                                               max="{{ $reward->stock }}"
                                               value="0"
                                               class="w-20 px-2 py-1 border border-gray-300 rounded-md text-sm quantity-input"
                                               data-reward-id="{{ $reward->id }}"
                                               data-points-per-item="{{ $reward->points_req }}"
                                               onchange="updateTotal()">
                                        <input type="hidden" name="rewards[{{ $reward->id }}][reward_id]" value="{{ $reward->id }}">
                                    </div>
                                @else
                                    <div class="text-gray-400 text-sm">
                                        Cannot redeem this reward
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Redemption Summary
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Your current points: <span class="font-semibold">{{ $userPoints }}</span></p>
                                <p>Total points to spend: <span class="font-semibold" id="total-points">0</span></p>
                                <p>Points remaining after redemption: <span class="font-semibold" id="remaining-points">{{ $userPoints }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('redemptions.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </a>
                    <button type="submit" 
                            id="submit-btn"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        Redeem Selected Items
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No rewards available</h3>
                <p class="mt-1 text-sm text-gray-500">Check back later for new rewards.</p>
            </div>
        @endif
    </div>
</div>

<script>
function updateTotal() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    let totalPoints = 0;
    let hasSelection = false;

    quantityInputs.forEach(input => {
        const quantity = parseInt(input.value) || 0;
        const pointsPerItem = parseInt(input.dataset.pointsPerItem);
        const pointsForThisItem = quantity * pointsPerItem;
        totalPoints += pointsForThisItem;
        
        if (quantity > 0) {
            hasSelection = true;
        }
    });

    document.getElementById('total-points').textContent = totalPoints;
    document.getElementById('remaining-points').textContent = {{ $userPoints }} - totalPoints;
    
    const submitBtn = document.getElementById('submit-btn');
    if (hasSelection && totalPoints <= {{ $userPoints }}) {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Redeem Selected Items';
    } else if (totalPoints > {{ $userPoints }}) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Insufficient Points';
    } else {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Select Items to Redeem';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', updateTotal);

// Handle form submission
document.getElementById('redemption-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Always prevent default first
    
    const quantityInputs = document.querySelectorAll('.quantity-input');
    let hasValidSelection = false;
    let totalPoints = 0;
    let selectedItems = [];
    
    quantityInputs.forEach(input => {
        const quantity = parseInt(input.value) || 0;
        if (quantity > 0) {
            hasValidSelection = true;
            const pointsPerItem = parseInt(input.dataset.pointsPerItem);
            const pointsForThisItem = quantity * pointsPerItem;
            totalPoints += pointsForThisItem;
            
            // Find the reward name from the card
            const rewardCard = input.closest('.bg-white');
            const rewardName = rewardCard.querySelector('h3').textContent.trim();
            selectedItems.push(`${rewardName} (${quantity} × ${pointsPerItem} pts = ${pointsForThisItem} pts)`);
        }
    });
    
    if (!hasValidSelection) {
        alert('Please select at least one item to redeem.');
        return false;
    }
    
    // Show confirmation dialog
    const confirmationMessage = `Are you sure you want to redeem these items?\n\n${selectedItems.join('\n')}\n\nTotal Points: ${totalPoints} points\n\nThis action cannot be undone.`;
    
    if (confirm(confirmationMessage)) {
        // User confirmed, submit the form
        this.submit();
    }
    // If user cancels, do nothing (form won't be submitted)
});
</script>
@endsection
