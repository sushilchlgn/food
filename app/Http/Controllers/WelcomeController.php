<?php

namespace App\Http\Controllers; // <--- Ensure this is correct (no sub-namespace)

use Illuminate\Http\Request;
// You might want to fetch some data, e.g., approved restaurants, to display on the welcome page
use App\Models\Restaurant;

class WelcomeController extends Controller
{
    /**
     * Show the application's welcome page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Example: Fetch some approved restaurants to display
        // $restaurants = Restaurant::where('is_approved', true)
        //                          ->where('is_open', true)
        //                          ->orderBy('created_at', 'desc') // Or by some other criteria like rating
        //                          ->take(6) // Show a few featured ones
        //                          ->get();

        // return view('welcome', compact('restaurants'));
        return view('welcome'); // This loads resources/views/welcome.blade.php
    }
}