<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant; // To fetch restaurants

class HomeController extends Controller
{
    public function index()
    {
        // Fetch only approved and open restaurants for customers
        $restaurants = Restaurant::where('is_approved', true)
                                 ->where('is_open', true) // Assuming you have an 'is_open' flag
                                 ->latest()
                                 ->paginate(9); // Or however many you want

        return view('customer.home', compact('restaurants'));
        // Or, if you want to keep the view name generic:
        // return view('dashboard', compact('restaurants'));
        // But 'customer.home' is more descriptive.
    }
}