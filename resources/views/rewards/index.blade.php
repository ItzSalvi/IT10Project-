@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Available Rewards</h1>
            <p class="mt-2 text-sm text-gray-600">Your current points: <span class="font-semibold text-blue-600">{{ $userPoints }}</span></p>
        </div>
        <div class="flex space-x-2">
            @if(Auth::user()->isAdmin())
                <button onclick="openAddRewardModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add Reward
                </button>
            @endif
            <a href="{{ route('redemptions.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Redeem Rewards
            </a>
        </div>
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

    @if($rewards->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rewards as $reward)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
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

                        <div class="flex items-center justify-between">
                            <div class="text-2xl font-bold text-blue-600">
                                {{ $reward->points_req }} pts
                            </div>
                            <div class="flex space-x-1">
                                <a href="{{ route('rewards.show', $reward) }}" 
                                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs font-medium py-1 px-2 rounded">
                                    View
                                </a>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('rewards.edit', $reward) }}" 
                                       class="bg-blue-500 hover:bg-blue-700 text-white text-xs font-medium py-1 px-2 rounded">
                                        Edit
                                    </a>
                                @endif
                                @if($userPoints >= $reward->points_req && $reward->isAvailable())
                                    <a href="{{ route('redemptions.create', ['reward' => $reward->id]) }}" 
                                       class="bg-green-500 hover:bg-green-700 text-white text-xs font-medium py-1 px-2 rounded">
                                        Redeem
                                    </a>
                                @elseif($userPoints < $reward->points_req)
                                    <span class="bg-gray-100 text-gray-500 text-xs font-medium py-1 px-2 rounded">
                                        Need {{ $reward->points_req - $userPoints }} more pts
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 text-xs font-medium py-1 px-2 rounded">
                                        Out of stock
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($rewards->hasPages())
            <div class="mt-8">
                {{ $rewards->links() }}
            </div>
        @endif
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

<!-- Add Reward Modal -->
<div id="addRewardModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-auto p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add New Reward</h3>
                <button onclick="closeAddRewardModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="addRewardForm" method="POST" action="{{ route('rewards.store') }}" class="space-y-4">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Reward Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-300 @enderror"
                           value="{{ old('name') }}"
                           placeholder="e.g., Eco Bag, Water Bottle">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" 
                              id="description" 
                              rows="2"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-300 @enderror"
                              placeholder="Describe the reward...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="points_req" class="block text-sm font-medium text-gray-700">Points Required</label>
                    <input type="number" 
                           name="points_req" 
                           id="points_req" 
                           min="1" 
                           required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('points_req') border-red-300 @enderror"
                           value="{{ old('points_req') }}"
                           placeholder="Points needed">
                    @error('points_req')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700">Initial Stock</label>
                    <input type="number" 
                           name="stock" 
                           id="stock" 
                           min="0" 
                           required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('stock') border-red-300 @enderror"
                           value="{{ old('stock') }}"
                           placeholder="Number of items">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           name="status" 
                           id="status" 
                           value="1"
                           {{ old('status', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="status" class="ml-2 block text-sm text-gray-900">
                        Active (available for redemption)
                    </label>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 pt-4">
                    <button type="button" 
                            onclick="closeAddRewardModal()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                        Create Reward
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddRewardModal() {
    document.getElementById('addRewardModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeAddRewardModal() {
    document.getElementById('addRewardModal').classList.add('hidden');
    document.body.style.overflow = 'auto'; // Restore scrolling
    // Clear form
    document.getElementById('addRewardForm').reset();
}

// Close modal when clicking outside
document.getElementById('addRewardModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddRewardModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddRewardModal();
    }
});
</script>
@endsection
