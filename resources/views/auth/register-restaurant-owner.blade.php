{{-- resources/views/auth/register-restaurant-owner.blade.php --}}
<x-guest-layout>
    <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-200 mb-6">
        Restaurant Owner Registration
    </h2>
    <form method="POST" action="{{ route('register.restaurant_owner') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Your Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- OPTIONAL: Add fields for initial restaurant details here --}}
        {{--
        <hr class="my-6 border-gray-300 dark:border-gray-600">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">Restaurant Details</h3>

        <!-- Restaurant Name -->
        <div class="mt-4">
            <x-input-label for="restaurant_name" :value="__('Restaurant Name')" />
            <x-text-input id="restaurant_name" class="block mt-1 w-full" type="text" name="restaurant_name" :value="old('restaurant_name')" required />
            <x-input-error :messages="$errors->get('restaurant_name')" class="mt-2" />
        </div>

        <!-- Restaurant Address -->
        <div class="mt-4">
            <x-input-label for="restaurant_address" :value="__('Restaurant Address')" />
            <x-text-input id="restaurant_address" class="block mt-1 w-full" type="text" name="restaurant_address" :value="old('restaurant_address')" required />
            <x-input-error :messages="$errors->get('restaurant_address')" class="mt-2" />
        </div>
        --}}


        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register as Restaurant Owner') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>