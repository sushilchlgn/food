{{-- resources/views/restaurant/profile/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Your Restaurant Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('restaurant.profile.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Restaurant Name -->
                        <div>
                            <x-input-label for="name" :value="__('Restaurant Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Phone Number -->
                        <div class="mt-4">
                            <x-input-label for="phone_number" :value="__('Phone Number')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <!-- Cuisine Type -->
                        <div class="mt-4">
                            <x-input-label for="cuisine_type" :value="__('Cuisine Type (e.g., Italian, Indian, Mexican)')" />
                            <x-text-input id="cuisine_type" class="block mt-1 w-full" type="text" name="cuisine_type" :value="old('cuisine_type')" required />
                            <x-input-error :messages="$errors->get('cuisine_type')" class="mt-2" />
                        </div>

                        <!-- Image (Optional) -->
                        {{--
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Restaurant Image (Optional)')" />
                            <input id="image" class="block mt-1 w-full type="file" name="image" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                        --}}


                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Create Profile') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>