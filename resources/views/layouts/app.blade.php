{{-- Example: resources/views/layouts/navigation.blade.php (Breeze) --}}
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}"> {{-- Or route('home') --}}
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    {{-- ADD YOUR PUBLIC RESTAURANT LISTING LINK HERE --}}
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Restaurants') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown or Guest Links -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    {{-- Cart Link for Authenticated Users --}}
                    <a href="{{ route('cart.index') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 mr-3">
                        Cart {{-- You can add a cart icon here --}}
                        {{-- Optionally, show cart count: e.g., ({{ Cart::count() }}) --}}
                    </a>

                    <x-dropdown align="right" width="48">
                        {{-- ... (existing dropdown for authenticated users) ... --}}
                    </x-dropdown>
                @else
                    {{-- Cart Link for Guests --}}
                    <a href="{{ route('cart.index') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 mr-3">
                        Cart
                    </a>

                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                    @endif
                @endauth
            </div>
            {{-- ... (rest of the navigation, including hamburger menu) ... --}}
        </div>
    </div>
    {{-- ... (rest of the navigation, including responsive navigation menu) ... --}}
</nav>  