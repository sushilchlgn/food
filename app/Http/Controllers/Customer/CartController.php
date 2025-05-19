<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
// use Darryldecode\Cart\Facades\CartFacade as Cart; // If using a cart package

class CartController extends Controller
{
    // If NOT using a package, you'll manage the cart in the session manually.
    // Example using manual session cart:

    public function index()
    {
        $cartItems = session()->get('cart', []);
        $total = $this->calculateCartTotal($cartItems);
        return view('customer.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, MenuItem $menuItem)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1); // Get quantity from form, default 1

        if (!is_numeric($quantity) || $quantity < 1) {
            return redirect()->back()->with('error', 'Invalid quantity.');
        }
        $quantity = (int)$quantity;

        if (!$menuItem->is_available || !$menuItem->restaurant->is_open || !$menuItem->restaurant->is_approved) {
            return redirect()->back()->with('error', 'This item is currently unavailable.');
        }

        // Basic: Ensure all items in cart are from the same restaurant (optional, but good for food delivery)
        if (!empty($cart)) {
            $firstItemRestaurantId = null;
            foreach ($cart as $id => $details) {
                // Need to fetch restaurant ID for existing cart items or store it in session
                $existingMenuItem = MenuItem::find($id);
                if ($existingMenuItem) {
                    $firstItemRestaurantId = $existingMenuItem->restaurant_id;
                    break;
                }
            }
            if ($firstItemRestaurantId && $menuItem->restaurant_id !== $firstItemRestaurantId) {
                // Option 1: Clear cart and add new item
                // session()->put('cart', []); // Clear existing cart
                // $cart = [];
                // Option 2: Show error
                return redirect()->back()->with('error', 'You can only order from one restaurant at a time. Please clear your cart or complete your existing order first.');
            }
        }


        if(isset($cart[$menuItem->id])) {
            $cart[$menuItem->id]['quantity'] += $quantity;
        } else {
            $cart[$menuItem->id] = [
                "name" => $menuItem->name,
                "quantity" => $quantity,
                "price" => $menuItem->price,
                "image_url" => $menuItem->image_url, // Store for display in cart
                "restaurant_id" => $menuItem->restaurant_id // Useful for checks
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }

    public function update(Request $request, $menuItemId) // Assuming $menuItemId is the key in session cart
    {
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity');

        if(isset($cart[$menuItemId]) && is_numeric($quantity) && $quantity > 0) {
            $cart[$menuItemId]['quantity'] = (int)$quantity;
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
        } elseif (isset($cart[$menuItemId]) && is_numeric($quantity) && $quantity <= 0) {
            // If quantity is 0 or less, remove the item
            unset($cart[$menuItemId]);
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
        }
        return redirect()->route('cart.index')->with('error', 'Unable to update cart.');
    }

    public function remove($menuItemId)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$menuItemId])) {
            unset($cart[$menuItemId]);
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Item removed from cart successfully.');
        }
        return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }

    private function calculateCartTotal($cartItems)
    {
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}