<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Restaurant Profile: ') }} {{ $restaurant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('restaurant.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Use PUT for updating the entire resource typically --}}

                        <!-- Restaurant Name -->
                        <div>
                            <x-input-label for="name" :value="__('Restaurant Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $restaurant->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description', $restaurant->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address', $restaurant->address)" required />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Phone Number -->
                        <div class="mt-4">
                            <x-input-label for="phone_number" :value="__('Phone Number')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number', $restaurant->phone_number)" required />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <!-- Cuisine Type -->
                        <div class="mt-4">
                            <x-input-label for="cuisine_type" :value="__('Cuisine Type (e.g., Italian, Indian, Mexican)')" />
                            <x-text-input id="cuisine_type" class="block mt-1 w-full" type="text" name="cuisine_type" :value="old('cuisine_type', $restaurant->cuisine_type)" required />
                            <x-input-error :messages="$errors->get('cuisine_type')" class="mt-2" />
                        </div>

                        <!-- Current Image (Optional Display) -->
                        @if($restaurant->image_url)
                        <div class="mt-4">
                            <x-input-label :value="__('Current Image')" />
                            <img src="{{ asset('storage/' . $restaurant->image_url) }}" alt="{{ $restaurant->name }}" class="mt-1 h-40 w-auto object-cover rounded">
                        </div>
                        @endif

                        <!-- New Image (Optional) -->
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Change Restaurant Image (Optional)')" /> {{-- Changed name to "image" to match typical controller handling --}}
                            <input id="image" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" name="image" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank to keep the current image.</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        {{-- is_open is often handled by a separate toggle button/action for better UX --}}
                        {{-- If you want it here:
                        <div class="block mt-4">
                            <label for="is_open" class="inline-flex items-center">
                                <input id="is_open" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="is_open" value="1" {{ old('is_open', $restaurant->is_open) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Open for orders') }}</span>
                            </label>
                            <x-input-error :messages="$errors->get('is_open')" class="mt-2" />
                        </div>
                        --}}

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('restaurant.dashboard') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Profile') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>