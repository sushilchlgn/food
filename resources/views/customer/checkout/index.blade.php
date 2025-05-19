{{-- resources/views/customer/checkout/index.blade.php --}}
<x-app-layout> {{-- User is logged in here --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-200 mb-8">Checkout</h1>

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Order Summary --}}
            <div class="md:col-span-1 order-2 md:order-1 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Order Summary</h2>
                @if(!empty($processedCartItems))
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($processedCartItems as $item)
                        <li class="py-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($item['subtotal'], 2) }}</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div class="border-t border-gray-200 dark:border-gray-700 mt-4 pt-4">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-900 dark:text-white">Total</span>
                            <span class="font-bold text-xl text-gray-900 dark:text-white">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400">Your cart is empty.</p>
                @endif
            </div>

            {{-- Delivery Details Form --}}
            <div class="md:col-span-2 order-1 md:order-2 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Delivery Details</h2>
                <form action="{{ route('checkout.placeOrder') }}" method="POST">
                    @csrf

                    <div>
                        <x-input-label for="delivery_address" :value="__('Delivery Address')" />
                        <textarea id="delivery_address" name="delivery_address" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('delivery_address', $user->address ?? '') }}</textarea>
                        <x-input-error :messages="$errors->get('delivery_address')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="phone_number" :value="__('Contact Phone Number')" />
                        <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number', $user->phone_number ?? '')" required />
                        <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="special_instructions" :value="__('Special Instructions (Optional)')" />
                        <textarea id="special_instructions" name="special_instructions" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('special_instructions') }}</textarea>
                        <x-input-error :messages="$errors->get('special_instructions')" class="mt-2" />
                    </div>

                    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                         <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Payment Method: Cash on Delivery</p>
                        <x-primary-button class="w-full justify-center text-lg py-3 bg-green-600 hover:bg-green-700">
                            {{ __('Place Order') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>