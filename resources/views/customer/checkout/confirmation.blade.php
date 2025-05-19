{{-- resources/views/customer/checkout/confirmation.blade.php --}}
<x-app-layout> {{-- User is logged in --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-lg p-8 text-center">
            <svg class="mx-auto h-16 w-16 text-green-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Thank You for Your Order!</h1>
            <p class="text-gray-700 dark:text-gray-300 mb-2">Your order <strong class="text-gray-900 dark:text-white">#{{ $order->id }}</strong> has been placed successfully.</p>
            <p class="text-gray-600 dark:text-gray-400 mb-6">We've received your order and will start processing it soon. You can track its status in your order history.</p>

            <div class="text-left bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg mb-8">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Order Summary</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Restaurant:</strong> {{ $order->restaurant->name }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Payment Method:</strong> Cash on Delivery</p>
            </div>

            <div class="space-y-3 sm:space-y-0 sm:flex sm:justify-center sm:space-x-4">
                <a href="{{ route('customer.orders.show', $order->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    View Order Details
                </a>
                <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 shadow-sm text-base font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</x-app-layout>