{{-- resources/views/restaurant/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Restaurant Owner Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Welcome, {{ $user->name }}!</h3>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                         <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($restaurant)
                        <p class="mt-4">Your Restaurant: <strong>{{ $restaurant->name }}</strong></p>
                        <p>Status:
                            @if ($restaurant->is_approved)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Approved
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending Admin Approval / Rejected
                                </span>
                            @endif
                        </p>
                        <p>Currently Accepting Orders: {{ $restaurant->is_open ? 'Yes' : 'No' }}</p>

                        <div class="mt-6 space-y-2">
                            <p>
                                <a href="{{ route('restaurant.profile.edit') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Edit Restaurant Profile</a>
                            </p>
                            @if ($restaurant->is_approved)
                            <p>
                                <a href="{{ route('restaurant.menu-items.index') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Manage Menu</a>
                            </p>
                            <p>
                                <a href="{{ route('restaurant.orders.index') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">View My Restaurant Orders</a>
                            </p>
                            @else
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                You will be able to manage your menu and orders once your restaurant is approved by an administrator.
                            </p>
                            @endif
                        </div>
                    @else
                        <p class="mt-4">You have not set up your restaurant profile yet.</p>
                        <p class="mt-2">
                            <a href="{{ route('restaurant.profile.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Create Your Restaurant Profile
                            </a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>