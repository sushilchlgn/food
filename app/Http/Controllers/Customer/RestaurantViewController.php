<?php

namespace App\Http\Controllers\Customer; // <--- Ensure this is correct

use App\Http\Controllers\Controller;
use App\Models\Restaurant; // To fetch restaurant data
use App\Models\MenuItem;   // To fetch menu items
use Illuminate\Http\Request;

class RestaurantViewController extends Controller
{
    /**
     * Display a public listing of approved and open restaurants.
     */
    // In RestaurantViewController.php
    public function listPublic(Request $request)
    {
        $query = Restaurant::where('is_approved', true);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('cuisine_type', 'like', '%' . $searchTerm . '%');
            });
        }
        $restaurants = $query->latest()->paginate(12);
        // This view will be your new homepage, e.g., resources/views/home.blade.php
        // or resources/views/restaurants/index.blade.php (choose a good name)
        return view('restaurant.index_public', compact('restaurants'));
    }

    // The show() method for viewing a specific restaurant's menu should also work for guests.
    public function show(Restaurant $restaurant)
    {
        if (!$restaurant->is_approved) {
            abort(404, 'Restaurant not found or currently unavailable.');
        }
        $restaurant->load([
            'menuItems' => function ($query) {
                $query->where('is_available', true)->orderBy('category')->orderBy('name');
            }
        ]);
        $menuItemsByCategory = $restaurant->menuItems->groupBy('category');
        // This view can be resources/views/restaurants/show.blade.php
        return view('restaurant.show_public', compact('restaurant', 'menuItemsByCategory'));
    }

    /**
     * Handle restaurant search for logged-in customers.
     * Could be similar to listPublic or have additional customer-specific filters.
     */
    public function search(Request $request)
    {
        // This logic can be very similar to listPublic or HomeController's index method
        // For now, let's reuse much of the listPublic logic.
        // You might want to redirect to a page that uses this logic
        // or return a JSON response if it's an AJAX search.

        $query = Restaurant::where('is_approved', true)->where('is_open', true);

        if ($request->has('q') && $request->q != '') { // Assuming search term is 'q'
            $searchTerm = $request->q;
            $query->where(function ($sq) use ($searchTerm) {
                $sq->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('cuisine_type', 'like', '%' . $searchTerm . '%');
                // Add more search fields if needed (e.g., description, address parts)
            });
        }

        $restaurants = $query->latest()->paginate(10);

        // This could render the same view as customer.home or a dedicated search results view
        return view('customer.restaurants.search_results', compact('restaurants'));
        // Or, if you integrated search into the main listing:
        // return view('customer.home', compact('restaurants'));
    }
}