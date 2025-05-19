<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class AdminRestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restaurants = Restaurant::with('owner')->latest()->paginate(10); // Eager load owner, paginate
        return view('admin.restaurants.index', compact('restaurants'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {
        $restaurant->load('owner', 'menuItems'); // Eager load related data
        return view('admin.restaurants.show', compact('restaurant'));
    }

    /**
     * Approve the specified restaurant.
     */
    public function approve(Restaurant $restaurant)
    {
        $restaurant->is_approved = true;
        $restaurant->save();

        // Optionally: Send notification to restaurant owner
        // Mail::to($restaurant->owner->email)->send(new RestaurantApproved($restaurant));

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant approved successfully.');
    }

    /**
     * Reject or unapprove the specified restaurant.
     */
    public function reject(Restaurant $restaurant) // Or make it a toggle
    {
        $restaurant->is_approved = false;
        $restaurant->save();

        // Optionally: Send notification to restaurant owner
        // Mail::to($restaurant->owner->email)->send(new RestaurantStatusChanged($restaurant, 'rejected'));

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant status updated successfully.');
    }

    // If you need full edit/delete functionality for admin:
    /*
    public function edit(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'cuisine_type' => 'nullable|string|max:100',
            // 'is_open' => 'sometimes|boolean', // Admin might control this too
        ]);

        $restaurant->update($validatedData);
        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant updated successfully.');
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant deleted successfully.');
    }
    */
}