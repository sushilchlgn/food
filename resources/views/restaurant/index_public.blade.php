{{-- resources/views/restaurants/index_public.blade.php --}}
<x-guest-layout> {{-- Or x-app-layout if you want the nav bar for guests too --}}

    {{-- New Navigation Bar --}}
    <nav class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo/Brand Name -->
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}">
                            {{-- Replace with your logo or text --}}
                            <span class="font-bold text-xl text-red-600 dark:text-red-500">FoodieApp</span>
                        </a>
                    </div>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-2 md:space-x-4">
                    <a href="{{ route('home') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 {{ request()->routeIs('home') ? 'text-red-600 dark:text-red-400 border-b-2 border-red-500' : '' }}">
                        Restaurants
                    </a>

                    {{-- Cart Link - Ensure you have a 'cart.index' route defined --}}
                    <a href="{{ route('cart.index') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 {{ request()->routeIs('cart.index') ? 'text-red-600 dark:text-red-400 border-b-2 border-red-500' : '' }}">
                        Cart
                        {{-- Example for cart item count (requires backend logic):
                        @if(Cart::count() > 0)
                            <span class="ml-1 inline-block py-0.5 px-1.5 leading-none text-xs font-semibold text-white bg-red-500 rounded-full">{{ Cart::count() }}</span>
                        @endif
                        --}}
                    </a>

                    @guest {{-- Show Login/Register only if guest --}}
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400">
                                Login
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400">
                                Register
                            </a>
                        @endif
                    @else {{-- Show something else if user is authenticated (e.g., Dashboard, Logout) --}}
                        <a href="{{ route('dashboard') }}" {{-- Assuming you have a dashboard route --}}
                           class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();"
                               class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400">
                                Logout
                            </a>
                        </form>
                    @endguest
                </div>

                <!-- Mobile Menu Button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" id="mobile-menu-button" class="bg-white dark:bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red-500" aria-controls="mobile-menu-items" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        {{-- Icon when menu is closed. --}}
                        <svg class="block h-6 w-6" id="menu-icon-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        {{-- Icon when menu is open. --}}
                        <svg class="hidden h-6 w-6" id="menu-icon-open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="sm:hidden hidden" id="mobile-menu-items">
            <div class="pt-2 pb-3 space-y-1 px-2">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700 {{ request()->routeIs('home') ? 'bg-red-50 dark:bg-gray-900 text-red-600 dark:text-red-400' : '' }}">Restaurants</a>
                <a href="{{ route('cart.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700 {{ request()->routeIs('cart.index') ? 'bg-red-50 dark:bg-gray-900 text-red-600 dark:text-red-400' : '' }}">Cart</a>
                @guest
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700">Login</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700">Register</a>
                    @endif
                @else
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); this.closest('form').submit();"
                           class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Logout
                        </a>
                    </form>
                @endguest
            </div>
        </div>
        {{-- Simple JavaScript for Mobile Menu Toggle --}}
        <script>
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu-items');
            const menuIconClosed = document.getElementById('menu-icon-closed');
            const menuIconOpen = document.getElementById('menu-icon-open');

            if (menuButton && mobileMenu && menuIconClosed && menuIconOpen) {
                menuButton.addEventListener('click', () => {
                    const expanded = menuButton.getAttribute('aria-expanded') === 'true' || false;
                    menuButton.setAttribute('aria-expanded', !expanded);
                    mobileMenu.classList.toggle('hidden');
                    menuIconClosed.classList.toggle('hidden');
                    menuIconOpen.classList.toggle('hidden');
                });
            }
        </script>
    </nav>

    {{-- Main Content Area --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <h1 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-200 mb-8">
            Order Your Favorite Food
        </h1>

        {{-- Optional Search Bar --}}
        <form action="{{ route('home') }}" method="GET" class="mb-8 max-w-xl mx-auto">
            <div class="flex">
                <input type="text" name="search" placeholder="Search restaurants, cuisines..."
                       value="{{ request('search') }}"
                       class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-l-md shadow-sm">
                <button type="submit"
                        class="px-6 py-2 bg-red-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Search
                </button>
            </div>
        </form>

        @if (isset($restaurants) && $restaurants->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($restaurants as $restaurant)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300 ease-in-out">
                        <a href="{{ route('restaurants.show', $restaurant->id) }}">
                            @if($restaurant->image_url)
                                <img src="{{ asset('storage/' . $restaurant->image_url) }}" alt="{{ $restaurant->name }}" class="w-full h-48 object-cover">
                            @else
                                {{-- Consider replacing this SVG if it's not rendering correctly or use a more standard placeholder --}}
                                <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10m0-10L12 3l8 4M20 7v10m0-10L12 3l-8 4m12 14H4a2 2 0 01-2-2V6a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2h-4M8 21V11m8 10V11M12 21V3"></path></svg> {{-- Modified SVG path for a generic building/storefront icon --}}
                                </div>
                            @endif
                        </a>
                        <div class="p-6">
                            <h3 class="font-semibold text-xl text-gray-900 dark:text-white mb-1">
                                <a href="{{ route('restaurants.show', $restaurant->id) }}" class="hover:text-red-600 dark:hover:text-red-400">
                                    {{ $restaurant->name }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $restaurant->cuisine_type }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-300 mb-3 h-10 overflow-hidden">{{ Str::limit($restaurant->description, 80) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $restaurant->address }}</span>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('restaurants.show', $restaurant->id) }}" class="w-full text-center inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    View Menu
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-12">
                {{ $restaurants->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
               <p class="text-xl text-gray-500 dark:text-gray-400">No restaurants found matching your criteria.</p>
               @if(request('search'))
                   <a href="{{ route('home') }}" class="mt-4 inline-block text-red-600 hover:underline">Clear Search</a>
               @endif
            </div>
        @endif
    </div>
</x-guest-layout>