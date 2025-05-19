<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem; // To verify item details

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('home')->with('info', 'Your cart is empty. Please add items to proceed to checkout.');
        }

        $total = 0;
        $processedCartItems = [];
        $restaurantId = null;

        foreach ($cartItems as $id => $details) {
            $menuItem = MenuItem::find($id); // Fetch from DB to ensure price/availability is current
            if (!$menuItem || !$menuItem->is_available || !$menuItem->restaurant->is_open) {
                // Handle item becoming unavailable, remove from cart and notify
                unset($cartItems[$id]);
                session()->put('cart', $cartItems);
                return redirect()->route('cart.index')->with('error', "Item '{$details['name']}' is no longer available and has been removed from your cart.");
            }
            if ($restaurantId === null) {
                $restaurantId = $menuItem->restaurant_id;
            } elseif ($restaurantId !== $menuItem->restaurant_id) {
                // This check should ideally happen when adding to cart
                return redirect()->route('cart.index')->with('error', 'Error: Items from multiple restaurants in cart.');
            }

            $processedCartItems[$id] = [
                'menu_item_id' => $menuItem->id,
                'name' => $menuItem->name,
                'quantity' => $details['quantity'],
                'price_at_order_time' => $menuItem->price, // Use current price from DB
                'subtotal' => $menuItem->price * $details['quantity']
            ];
            $total += $processedCartItems[$id]['subtotal'];
        }
        session()->put('processed_cart_items_for_checkout', $processedCartItems); // Store processed items for order creation
        session()->put('checkout_restaurant_id', $restaurantId);
        session()->put('checkout_total', $total);


        $user = Auth::user(); // User is now authenticated due to middleware
        return view('customer.checkout.index', compact('processedCartItems', 'total', 'user'));
    }

    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        $processedCartItems = session()->get('processed_cart_items_for_checkout', []);
        $restaurantId = session()->get('checkout_restaurant_id');
        $totalAmount = session()->get('checkout_total');

        if (empty($processedCartItems) || !$restaurantId || $totalAmount === null) {
            return redirect()->route('home')->with('error', 'Your cart is empty or there was an issue with checkout.');
        }

        $request->validate([
            'delivery_address' => 'required|string|max:500', // Assuming user confirms/enters address
            'phone_number' => 'required|string|max:20', // Can be prefilled from user profile
            'special_instructions' => 'nullable|string|max:1000',
        ]);

        // Create Order
        $order = Order::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurantId,
            'total_amount' => $totalAmount,
            'status' => 'pending', // Initial status
            'delivery_address' => $request->delivery_address,
            'payment_method' => 'cash_on_delivery', // Hardcoded for now
            'special_instructions' => $request->special_instructions,
        ]);

        // Create OrderItems
        foreach ($processedCartItems as $itemDetails) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $itemDetails['menu_item_id'],
                'quantity' => $itemDetails['quantity'],
                'price_at_order_time' => $itemDetails['price_at_order_time'],
            ]);
        }

        // Clear the cart and checkout session data
        session()->forget('cart');
        session()->forget('processed_cart_items_for_checkout');
        session()->forget('checkout_restaurant_id');
        session()->forget('checkout_total');

        // Optionally, send notifications to restaurant owner and customer
        // Mail::to($order->restaurant->owner->email)->send(new NewOrderNotification($order));
        // Mail::to($user->email)->send(new OrderConfirmationEmail($order));

        return redirect()->route('checkout.confirmation', $order->id);
    }

    public function confirmation(Order $order)
    {
        // Ensure the logged-in user owns this order
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }
        return view('customer.checkout.confirmation', compact('order'));
    }
}