<?php

namespace App\Http\Controllers\Restaurant; // <--- THIS MUST BE CORRECT

use App\Http\Controllers\Controller;
use App\Models\Restaurant; // You'll likely need this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // If you need the authenticated user

class RestaurantProfileController extends Controller
{
    // Make sure you have the create() method defined
    public function create()
    {
        // Check if the owner already has a restaurant.
        // If they can only have one, redirect if it exists.
        $user = Auth::user();
        if ($user->restaurant) { // Assuming 'restaurant' is the hasOne relationship on User model
            return redirect()->route('restaurant.profile.edit')->with('info', 'You already have a restaurant profile. You can edit it here.');
        }

        return view('restaurant.profile.create'); // We will create this view
    }

    // Add other methods like store(), edit(), update() as needed
    // Example store()
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'cuisine_type' => 'required|string|max:100',
            // Add validation for image_url if you're handling uploads
        ]);

        $user = Auth::user();

        // Prevent creating multiple restaurants if your logic is one-per-owner
        if ($user->restaurant) {
            return redirect()->route('restaurant.dashboard')->with('error', 'You already have a restaurant registered.');
        }

        $restaurant = new Restaurant($request->all());
        $restaurant->user_id = $user->id;
        $restaurant->is_approved = false; // New restaurants need admin approval
        $restaurant->is_open = false;     // Default to closed until owner explicitly opens
        
        // Handle image upload here if you have an image_url field
        // if ($request->hasFile('image')) {
        //     $path = $request->file('image')->store('restaurant_images', 'public');
        //     $restaurant->image_url = $path;
        // }

        $restaurant->save();

        return redirect()->route('restaurant.dashboard')->with('success', 'Restaurant profile created successfully! It is now pending admin approval.');
    }

    public function edit()
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;

        if (!$restaurant) {
            return redirect()->route('restaurant.profile.create')->with('info', 'You need to create your restaurant profile first.');
        }

        return view('restaurant.profile.edit', compact('restaurant'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;

        if (!$restaurant) {
            return redirect()->route('restaurant.profile.create')->with('error', 'No restaurant profile found to update.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'cuisine_type' => 'required|string|max:100',
            // 'is_open' => 'sometimes|boolean', // For the toggleOpenStatus method
        ]);

        $restaurant->fill($request->except(['is_open'])); // is_open might be handled by a separate method

        // Handle image upload update
        // if ($request->hasFile('image')) {
            // Delete old image if necessary
            // $path = $request->file('image')->store('restaurant_images', 'public');
            // $restaurant->image_url = $path;
        // }

        $restaurant->save();

        return redirect()->route('restaurant.dashboard')->with('success', 'Restaurant profile updated successfully.');
    }

    public function toggleOpenStatus()
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;

        if ($restaurant && $restaurant->is_approved) { // Only allow toggling if approved
            $restaurant->is_open = !$restaurant->is_open;
            $restaurant->save();
            $status = $restaurant->is_open ? 'open' : 'closed';
            return redirect()->route('restaurant.dashboard')->with('success', "Restaurant is now {$status} for orders.");
        }
        return redirect()->route('restaurant.dashboard')->with('error', 'Cannot change status currently.');
    }


}