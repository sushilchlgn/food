{{-- resources/views/restaurants/show_public.blade.php --}}
<x-guest-layout> {{-- Or x-app-layout --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Restaurant Details Section --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-8">
            <div class="p-6 md:p-8">
                {{-- Flex container for side-by-side layout on md screens and up --}}
                <div class="md:flex md:space-x-8 items-start">
                    @if($restaurant->image_url)
                        <div class="md:w-1/3 lg:w-2/5 flex-shrink-0"> {{-- Image column --}}
                            <img src="{{ asset('storage/' . $restaurant->image_url) }}" alt="{{ $restaurant->name }}" class="w-full h-64 sm:h-72 md:h-80 lg:h-96 object-cover rounded-lg shadow-md mb-6 md:mb-0">
                        </div>
                    @endif
                    <div class="flex-grow"> {{-- Details column --}}
                        <h1 class="text-3xl {{ $restaurant->image_url ? 'md:text-3xl' : 'md:text-4xl' }} lg:text-4xl font-bold text-gray-900 dark:text-white">{{ $restaurant->name }}</h1>
                        <p class="text-md text-gray-600 dark:text-gray-400 mt-1">{{ $restaurant->cuisine_type }}</p>
                        <p class="mt-3 text-gray-700 dark:text-gray-300 leading-relaxed">{{ $restaurant->description }}</p>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400 space-y-1">
                            <p><span class="font-semibold">Address:</span> {{ $restaurant->address }}</p>
                            <p><span class="font-semibold">Phone:</span> {{ $restaurant->phone_number }}</p>
                            {{-- Add opening hours, ratings etc. if you have them --}}
                            {{-- Example:
                            @if($restaurant->opening_hours)
                                <p><span class="font-semibold">Hours:</span> {{ $restaurant->opening_hours }}</p>
                            @endif
                            --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Menu</h2>

        @if ($menuItemsByCategory->count() > 0)
            @foreach ($menuItemsByCategory as $category => $items)
                <div class="mb-10">
                    <h3 class="text-xl md:text-2xl font-bold text-gray-700 dark:text-gray-300 mb-4 pb-2 border-b border-gray-300 dark:border-gray-700 capitalize">
                        {{ $category ?: 'Other Items' }}
                    </h3>
                    {{-- Consider adding xl:grid-cols-4 if your cards are not too wide --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($items as $menuItem)
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg flex flex-col">
                                @if($menuItem->image_url)
                                    <img src="{{ asset('storage/' . $menuItem->image_url) }}" alt="{{ $menuItem->name }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-t-lg">
                                        {{-- Using a more generic food icon SVG --}}
                                        <svg class="w-10 h-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-5 flex flex-col flex-grow">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $menuItem->name }}</h4>
                                    @if($menuItem->description)
                                        {{-- Slightly taller description area on md, auto height on lg with a max-height --}}
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 flex-grow h-16 md:h-20 lg:h-auto lg:max-h-28 overflow-y-auto custom-scrollbar">
                                            {{ $menuItem->description }}
                                        </p>
                                    @endif
                                    <p class="text-xl font-bold text-gray-700 dark:text-gray-300 mt-3">${{ number_format($menuItem->price, 2) }}</p>

                                    <form action="{{ route('cart.add', $menuItem->id) }}" method="POST" class="mt-4">
                                        @csrf
                                        <div class="flex items-center mb-2">
                                            <label for="quantity-{{$menuItem->id}}" class="sr-only">Quantity</label>
                                            <input type="number" id="quantity-{{$menuItem->id}}" name="quantity" value="1" min="1" class="w-16 px-2 py-1 text-center border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        </div>
                                        <button type="submit" class="w-full text-center inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Add to Cart
                                        </button>
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
                    <p class="text-center text-gray-500">This restaurant currently has no menu items listed.</p>
                </div>
            </div>
        @endif
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 font-medium">
                ← Back to All Restaurants
            </a>
        </div>
    </div>
    {{-- Optional: Add custom scrollbar styles if you use overflow-y-auto frequently --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1; /* cool-gray-300 */
            border-radius: 3px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4b5563; /* gray-600 */
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af; /* cool-gray-400 */
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6b7280; /* gray-500 */
        }
    </style>
</x-guest-layout>