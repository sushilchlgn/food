<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $restaurant->name }} - Menu
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($restaurant->image_url)
                        <img src="{{ asset('storage/' . $restaurant->image_url) }}" alt="{{ $restaurant->name }}" class="w-full h-64 object-cover rounded-md mb-4">
                    @endif
                    <h3 class="text-2xl font-bold">{{ $restaurant->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-400">{{ $restaurant->cuisine_type }}</p>
                    <p class="mt-2">{{ $restaurant->description }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $restaurant->address }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Phone: {{ $restaurant->phone_number }}</p>
                    {{-- Add opening hours, ratings etc. if you have them --}}
                </div>
            </div>

            <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Menu</h3>

            @if ($menuItemsByCategory->count() > 0)
                @foreach ($menuItemsByCategory as $category => $items)
                    <div class="mb-8">
                        <h4 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-3 capitalize">
                            {{ $category ?: 'Other Items' }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($items as $menuItem)
                                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                    <div class="p-6">
                                        @if($menuItem->image_url)
                                            <img src="{{ asset('storage/' . $menuItem->image_url) }}" alt="{{ $menuItem->name }}" class="w-full h-40 object-cover rounded-md mb-3">
                                        @else
                                            <div class="w-full h-40 bg-gray-200 dark:bg-gray-700 rounded-md mb-3 flex items-center justify-center">
                                                <span class="text-gray-400 dark:text-gray-500">No Image</span>
                                            </div>
                                        @endif
                                        <h5 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $menuItem->name }}</h5>
                                        @if($menuItem->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 h-12 overflow-y-auto">
                                                {{ $menuItem->description }}
                                            </p>
                                        @endif
                                        <p class="text-lg font-bold text-gray-700 dark:text-gray-300 mt-2">${{ number_format($menuItem->price, 2) }}</p>

                                        {{-- Add to Cart Form --}}
                                        <form action="{{ route('customer.cart.add', $menuItem->id) }}" method="POST" class="mt-3">
                                            @csrf
                                            {{-- You might want a quantity input here --}}
                                            {{-- <input type="hidden" name="quantity" value="1"> --}}
                                            <x-primary-button type="submit">
                                                Add to Cart
                                            </x-primary-button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p>This restaurant currently has no menu items available.</p>
                    </div>
                </div>
            @endif
            <div class="mt-6">
                <a href="{{ route('customer.home') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                    ← Back to Restaurants
                </a>
            </div>
        </div>
    </div>
</x-app-layout>