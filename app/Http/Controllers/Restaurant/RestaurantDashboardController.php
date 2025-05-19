<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Check if the restaurant owner already has a restaurant associated
        // We assume a one-to-one or one-to-many (if owner can have multiple restaurants)
        // For simplicity, let's assume one owner has one restaurant for now.
        // You'll need to define the 'restaurant' relationship on the User model.
        $restaurant = $user->restaurant; // Or $user->restaurants()->first();

        return view('restaurant.dashboard', compact('user', 'restaurant'));
    }
}