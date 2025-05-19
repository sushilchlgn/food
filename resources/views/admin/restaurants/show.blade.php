<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Restaurant Details: ') }} {{ $restaurant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium">Restaurant Information</h3>
                            <p><strong>Name:</strong> {{ $restaurant->name }}</p>
                            <p><strong>Owner:</strong> {{ $restaurant->owner->name ?? 'N/A' }} ({{ $restaurant->owner->email ?? 'N/A' }})</p>
                            <p><strong>Description:</strong> {{ $restaurant->description ?: 'N/A' }}</p>
                            <p><strong>Address:</strong> {{ $restaurant->address }}</p>
                            <p><strong>Phone:</strong> {{ $restaurant->phone_number ?: 'N/A' }}</p>
                            <p><strong>Cuisine:</strong> {{ $restaurant->cuisine_type ?: 'N/A' }}</p>
                            <p><strong>Status:</strong>
                                @if ($restaurant->is_approved)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending / Rejected</span>
                                @endif
                            </p>
                            <p><strong>Open for Orders:</strong> {{ $restaurant->is_open ? 'Yes' : 'No' }}</p>
                            @if ($restaurant->image_url)
                                <p><strong>Image:</strong></p>
                                <img src="{{ asset('storage/' . $restaurant->image_url) }}" alt="{{ $restaurant->name }}" class="mt-2 h-48 w-auto object-cover rounded">
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-medium">Menu Items ({{ $restaurant->menuItems->count() }})</h3>
                            @if($restaurant->menuItems->isNotEmpty())
                                <ul class="list-disc list-inside mt-2">
                                    @foreach ($restaurant->menuItems as $item)
                                        <li>{{ $item->name }} - ${{ number_format($item->price, 2) }} ({{ $item->is_available ? 'Available' : 'Unavailable' }})</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No menu items added yet.</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6">
                        @if (!$restaurant->is_approved)
                            <form action="{{ route('admin.restaurants.approve', $restaurant) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <x-primary-button type="submit">Approve Restaurant</x-primary-button>
                            </form>
                        @else
                            <form action="{{ route('admin.restaurants.reject', $restaurant) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <x-danger-button type="submit">Unapprove Restaurant</x-danger-button>
                            </form>
                        @endif
                         <a href="{{ route('admin.restaurants.index') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>