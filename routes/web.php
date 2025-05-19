<?php

use Illuminate\Support\Facades\Route;

// Core & Profile Controllers
use App\Http\Controllers\ProfileController;
// We'll use RestaurantViewController for the main page now
use App\Http\Controllers\Customer\RestaurantViewController; // For listing restaurants & showing menus

// Authentication Controllers
use App\Http\Controllers\Auth\RestaurantOwnerRegisteredUserController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminRestaurantController;
// ... (other admin controllers as needed)

// Restaurant Owner Controllers
use App\Http\Controllers\Restaurant\RestaurantDashboardController;
use App\Http\Controllers\Restaurant\RestaurantProfileController;
use App\Http\Controllers\Restaurant\RestaurantMenuController;
use App\Http\Controllers\Restaurant\RestaurantOrderController;

// Customer Interaction Controllers (Cart, Checkout, My Orders)
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PUBLICLY ACCESSIBLE MAIN PAGE & RESTAURANT VIEWING ---
// The root route will now list restaurants (or be the main entry point)
Route::get('/', [RestaurantViewController::class, 'listPublic'])->name('home'); // Renamed from 'restaurants.listPublic'
Route::get('/restaurants/{restaurant}', [RestaurantViewController::class, 'show'])->name('restaurants.show'); // Show specific restaurant & menu
Route::get('/restaurants/search', [RestaurantViewController::class, 'search'])->name('restaurants.search'); // Optional search


// --- CART MANAGEMENT (ACCESSIBLE BY GUESTS AND LOGGED-IN CUSTOMERS) ---
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{menuItem}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{cartItemId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');


// --- CHECKOUT PROCESS ---
// Guest will be redirected to login/register from here if not authenticated
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware('checkout.redirect'); // Custom middleware
Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder')->middleware(['auth', 'verified']); // Must be logged in to place order
Route::get('/order-confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation')->middleware(['auth', 'verified']);


// --- AUTHENTICATION RELATED ROUTES ---
Route::middleware('guest')->group(function () {
    Route::get('register/restaurant-owner', [RestaurantOwnerRegisteredUserController::class, 'create'])
                ->name('register.restaurant_owner');
    Route::post('register/restaurant-owner', [RestaurantOwnerRegisteredUserController::class, 'store']);
    // Standard customer registration, login, password reset routes are in auth.php
});

// --- AUTHENTICATED USER ROUTES (COMMON) ---
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - Redirects based on user role (Admin, Restaurant Owner)
    // Customers will no longer have a distinct /dashboard they are sent to.
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isRestaurantOwner()) {
            return redirect()->route('restaurant.dashboard');
        }
        // If a customer somehow lands here (e.g., old bookmark), redirect to home
        return redirect()->route('home');
    })->name('dashboard');

    // User Profile Management (for logged-in users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer's Order History (logged-in customers only)
    Route::name('customer.')->prefix('my-account')->group(function () { // Prefix to avoid clashes
        // Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        // Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    });
});


// --- RESTAURANT OWNER ROUTES ---
Route::middleware(['auth', 'verified', 'restaurant_owner'])->prefix('restaurant-admin')->name('restaurant.')->group(function () {
    Route::get('/dashboard', [RestaurantDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile/create', [RestaurantProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [RestaurantProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [RestaurantProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [RestaurantProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/toggle-open', [RestaurantProfileController::class, 'toggleOpenStatus'])->name('profile.toggleOpen');
    Route::resource('menu-items', RestaurantMenuController::class);
    Route::patch('menu-items/{menu_item}/toggle-availability', [RestaurantMenuController::class, 'toggleAvailability'])->name('menu-items.toggleAvailability');
    Route::get('/orders', [RestaurantOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [RestaurantOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/update-status', [RestaurantOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});


// --- ADMIN ROUTES ---
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // Route::resource('users', App\Http\Controllers\Admin\AdminUserController::class)->except(['create', 'store']);
    Route::resource('restaurants', AdminRestaurantController::class);
    Route::patch('restaurants/{restaurant}/approve', [AdminRestaurantController::class, 'approve'])->name('restaurants.approve');
    Route::patch('restaurants/{restaurant}/reject', [AdminRestaurantController::class, 'reject'])->name('restaurants.reject');
    // ... (other admin menu/order routes)
});


// Standard Breeze Authentication Routes
require __DIR__.'/auth.php';