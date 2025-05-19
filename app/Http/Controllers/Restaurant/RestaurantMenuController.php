<?php

namespace App\Http\Controllers\Restaurant; // <--- THIS MUST BE CORRECT

use App\Http\Controllers\Controller;
use App\Models\MenuItem; // Assuming you have this model
use App\Models\Restaurant; // To get the owner's restaurant
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // To get the authenticated user

class RestaurantMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $restaurant = $user->restaurant; // Assuming hasOne relationship: User -> Restaurant

        if (!$restaurant) {
            // If the owner hasn't created their restaurant profile yet
            return redirect()->route('restaurant.profile.create')
                             ->with('info', 'Please create your restaurant profile before adding menu items.');
        }

        // Eager load menu items for the owner's restaurant
        // $menuItems = $restaurant->menuItems()->latest()->paginate(10);
        // Or if you defined the relationship on the Restaurant model:
        $menuItems = MenuItem::where('restaurant_id', $restaurant->id)->latest()->paginate(10);


        return view('restaurant.menu_items.index', compact('menuItems', 'restaurant'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;

        if (!$restaurant) {
            return redirect()->route('restaurant.profile.create')
                             ->with('info', 'Please create your restaurant profile first.');
        }
        if (!$restaurant->is_approved) {
             return redirect()->route('restaurant.dashboard')
                             ->with('error', 'Your restaurant is not yet approved. You cannot add menu items.');
        }

        return view('restaurant.menu_items.create', compact('restaurant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;

        if (!$restaurant || !$restaurant->is_approved) {
            return redirect()->route('restaurant.dashboard')
                             ->with('error', 'Cannot add menu items at this time.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Example validation for image
            'is_available' => 'sometimes|boolean',
        ]);

        $data = $request->all();
        $data['restaurant_id'] = $restaurant->id;
        $data['is_available'] = $request->has('is_available'); // Handle checkbox

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('menu_item_images', 'public');
            $data['image_url'] = $path;
        } else {
            // Unset image_url from data if no new file is uploaded, to avoid issues
            // or handle default image logic
            unset($data['image_url']);
        }


        MenuItem::create($data);

        return redirect()->route('restaurant.menu-items.index')
                         ->with('success', 'Menu item added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItem $menuItem) // Route Model Binding
    {
        // Ensure this menu item belongs to the authenticated owner's restaurant
        $this->authorizeMenuItemAccess($menuItem);
        $restaurant = Auth::user()->restaurant;
        return view('restaurant.menu_items.show', compact('menuItem', 'restaurant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuItem $menuItem) // Route Model Binding
    {
        $this->authorizeMenuItemAccess($menuItem);
        $restaurant = Auth::user()->restaurant;
        return view('restaurant.menu_items.edit', compact('menuItem', 'restaurant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuItem $menuItem) // Route Model Binding
    {
        $this->authorizeMenuItemAccess($menuItem);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_available' => 'sometimes|boolean',
        ]);

        $data = $request->all();
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image_url')) {
            // Optionally, delete the old image if it exists
            // Storage::disk('public')->delete($menuItem->image_url);
            $path = $request->file('image_url')->store('menu_item_images', 'public');
            $data['image_url'] = $path;
        } else {
            // If no new image is uploaded, don't overwrite existing image_url unless explicitly cleared
             unset($data['image_url']); // Or if you have a checkbox to "remove image", handle that
        }


        $menuItem->update($data);

        return redirect()->route('restaurant.menu-items.index')
                         ->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $menuItem) // Route Model Binding
    {
        $this->authorizeMenuItemAccess($menuItem);
        // Optionally, delete the image file from storage
        // if ($menuItem->image_url) {
        //     Storage::disk('public')->delete($menuItem->image_url);
        // }
        $menuItem->delete();

        return redirect()->route('restaurant.menu-items.index')
                         ->with('success', 'Menu item deleted successfully.');
    }

    /**
     * Toggle availability of a menu item.
     */
    public function toggleAvailability(MenuItem $menuItem)
    {
        $this->authorizeMenuItemAccess($menuItem);
        $menuItem->is_available = !$menuItem->is_available;
        $menuItem->save();
        $status = $menuItem->is_available ? 'available' : 'unavailable';
        return redirect()->back()->with('success', "Menu item '{$menuItem->name}' is now {$status}.");
    }


    /**
     * Helper method to authorize access to a menu item.
     */
    private function authorizeMenuItemAccess(MenuItem $menuItem)
    {
        $userRestaurantId = Auth::user()->restaurant->id ?? null;
        if ($menuItem->restaurant_id !== $userRestaurantId) {
            abort(403, 'Unauthorized action. This menu item does not belong to your restaurant.');
        }
    }
}