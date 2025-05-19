{{-- resources/views/restaurant/orders/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Details: #') }}{{ $order->id }} {{ __(' for ') }} {{ $restaurant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium">Order Information</h3>
                            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                            <p><strong>Customer:</strong> {{ $order->customer->name ?? 'N/A' }} ({{ $order->customer->email ?? 'N/A' }})</p>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i A') }}</p>
                            <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                            <p><strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                            <p><strong>Current Status:</strong>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @switch($order->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('confirmed') bg-blue-100 text-blue-800 @break
                                        @case('preparing') bg-indigo-100 text-indigo-800 @break
                                        @case('out_for_delivery') bg-purple-100 text-purple-800 @break
                                        @case('delivered') bg-green-100 text-green-800 @break
                                        @case('cancelled_by_customer')
                                        @case('cancelled_by_restaurant') bg-red-100 text-red-800 @break
                                        @default bg-gray-100 text-gray-800 @break
                                    @endswitch
                                ">
                                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </p>
                            <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                            @if($order->special_instructions)
                            <p><strong>Special Instructions:</strong> {{ $order->special_instructions }}</p>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-medium">Update Order Status</h3>
                            @if(!in_array($order->status, ['delivered', 'cancelled_by_customer', 'cancelled_by_restaurant']))
                            <form method="POST" action="{{ route('restaurant.orders.updateStatus', $order->id) }}" class="mt-2 space-y-3">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <x-input-label for="status" :value="__('New Status')" />
                                    <select name="status" id="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        {{-- Only show valid next states --}}
                                        @if($order->status === 'pending')
                                            <option value="confirmed">Confirm Order</option>
                                            <option value="cancelled_by_restaurant">Cancel Order</option>
                                        @elseif($order->status === 'confirmed')
                                            <option value="preparing">Start Preparing</option>
                                            <option value="cancelled_by_restaurant">Cancel Order</option>
                                        @elseif($order->status === 'preparing')
                                            <option value="out_for_delivery">Out for Delivery</option>
                                        @elseif($order->status === 'out_for_delivery')
                                            <option value="delivered">Delivered</option>
                                        @endif
                                    </select>
                                </div>
                                <x-primary-button>Update Status</x-primary-button>
                            </form>
                            @else
                                <p class="mt-2 text-sm text-gray-500">This order is finalized or cancelled and its status cannot be changed.</p>
                            @endif
                        </div>
                    </div>

                    <hr class="my-6 border-gray-300 dark:border-gray-700">

                    <h3 class="text-lg font-medium mb-2">Order Items</h3>
                    @if($order->items->count() > 0)
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($order->items as $item)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">{{ $item->menuItem->name ?? 'Item not found' }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <p class="text-sm">${{ number_format($item->price_at_order_time * $item->quantity, 2) }}
                                    <span class="text-xs text-gray-400 dark:text-gray-500">(${{ number_format($item->price_at_order_time, 2) }} each)</span>
                                </p>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No items found for this order (this should not happen).</p>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('restaurant.orders.index') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                            ← Back to All Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>