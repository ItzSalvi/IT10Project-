@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Reward</h1>
            <p class="mt-2 text-sm text-gray-600">Update reward details</p>
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

        <form method="POST" action="{{ route('rewards.update', $reward) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                    Reward Name
                </label>
                <div class="mt-1">
                    <input type="text" 
                           name="name" 
                           id="name" 
                           required
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 @enderror"
                           value="{{ old('name', $reward->name) }}"
                           placeholder="e.g., Eco Bag, Water Bottle, etc.">
                </div>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">
                    Description
                </label>
                <div class="mt-1">
                    <textarea name="description" 
                              id="description" 
                              rows="3"
                              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('description') border-red-300 @enderror"
                              placeholder="Describe the reward...">{{ old('description', $reward->description) }}</textarea>
                </div>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="points_req" class="block text-sm font-medium text-gray-700">
                    Points Required
                </label>
                <div class="mt-1">
                    <input type="number" 
                           name="points_req" 
                           id="points_req" 
                           min="1" 
                           required
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('points_req') border-red-300 @enderror"
                           value="{{ old('points_req', $reward->points_req) }}"
                           placeholder="Points needed to redeem this reward">
                </div>
                @error('points_req')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700">
                    Current Stock
                </label>
                <div class="mt-1">
                    <input type="number" 
                           name="stock" 
                           id="stock" 
                           min="0" 
                           required
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('stock') border-red-300 @enderror"
                           value="{{ old('stock', $reward->stock) }}"
                           placeholder="Number of items available">
                </div>
                @error('stock')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" 
                       name="status" 
                       id="status" 
                       value="1"
                       {{ old('status', $reward->status) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="status" class="ml-2 block text-sm text-gray-900">
                    Active (available for redemption)
                </label>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('rewards.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancel
                </a>
                <div class="flex space-x-2">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update Reward
                    </button>
                    <a href="{{ route('rewards.index') }}" 
                       onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this reward?')) { document.getElementById('delete-form').submit(); }"
                       class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete
                    </a>
                </div>
            </div>
        </form>

        <form id="delete-form" action="{{ route('rewards.destroy', $reward) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection



