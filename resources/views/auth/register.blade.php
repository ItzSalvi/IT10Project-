<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 via-white to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Join Our Community</h2>
                <p class="mt-2 text-sm text-gray-600">Start your bottle recycling journey today</p>
            </div>

            <!-- Registration Form -->
            <div class="bg-white py-8 px-6 shadow-xl rounded-2xl border border-gray-100">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

                    <!-- Name Fields -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <!-- First Name -->
                        <div>
                        <label for="fname" class="block text-sm font-medium text-gray-700 mb-2">
                            First Name *
                        </label>
                        <input id="fname" 
                               name="fname" 
                               type="text" 
                               autocomplete="given-name" 
                               required 
                               value="{{ old('fname') }}"
                               class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('fname') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               placeholder="John">
                            @error('fname')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Middle Name -->
                        <div>
                        <label for="mname" class="block text-sm font-medium text-gray-700 mb-2">
                            Middle Name
                        </label>
                        <input id="mname" 
                               name="mname" 
                               type="text" 
                               autocomplete="additional-name" 
                               value="{{ old('mname') }}"
                               class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('mname') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               placeholder="Michael">
                            @error('mname')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
        <div>
                        <label for="lname" class="block text-sm font-medium text-gray-700 mb-2">
                            Last Name *
                        </label>
                        <input id="lname" 
                               name="lname" 
                               type="text" 
                               autocomplete="family-name" 
                               required 
                               value="{{ old('lname') }}"
                               class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('lname') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               placeholder="Doe">
                            @error('lname')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
        </div>

        <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address *
                        </label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email') }}"
                               class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               placeholder="john.doe@example.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
        </div>

        <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password *
                        </label>
                        <input id="password" 
                               name="password" 
                            type="password"
                               autocomplete="new-password" 
                               required
                               class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('password') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               placeholder="Create a strong password">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
        </div>

        <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password *
                        </label>
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                            type="password"
                               autocomplete="new-password" 
                               required
                               class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('password_confirmation') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror" 
                               placeholder="Confirm your password">
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Register Button -->
                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 transform hover:scale-105">
                            Create Account
                        </button>
        </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition duration-200">
                                Sign in here
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Benefits Info -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Join the Green Revolution</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Earn points for every bottle you recycle</li>
                                <li>Redeem points for eco-friendly rewards</li>
                                <li>Track your environmental impact</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
