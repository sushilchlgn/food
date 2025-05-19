<?php 
// app/Http/Controllers/Auth/RestaurantOwnerRegisteredUserController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RestaurantOwnerRegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // We will create this new view
        return view('auth.register-restaurant-owner');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Add any restaurant-specific fields you want to collect at registration
            // For example:
            // 'restaurant_name' => ['required', 'string', 'max:255'],
            // 'restaurant_address' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_RESTAURANT_OWNER, // Assign the correct role
        ]);

        // --- OPTIONAL: Create Restaurant Record Immediately ---
        // If you want to create a basic restaurant record at the same time:
        /*
        if ($user) {
            $user->restaurants()->create([ // Assuming 'restaurants' is the relationship name in User model
                'name' => $request->restaurant_name, // Collect this from the form
                'address' => $request->restaurant_address, // Collect this from the form
                'description' => 'Awaiting further details.', // Default description
                'is_approved' => false, // Default to not approved
                'is_open' => false, // Default to not open
                // Add other necessary default restaurant fields
            ]);
        }
        */
        // You would need to add a 'restaurants' relationship to the User model:
        // In app/Models/User.php:
        // public function restaurants() { return $this->hasMany(Restaurant::class); }


        event(new Registered($user));

        Auth::login($user);

        // Redirect restaurant owner to a specific dashboard or a "pending approval" page
        // For now, let's redirect to the generic dashboard.
        // You might want to create a route like 'restaurant.dashboard' later.
        return redirect(route('dashboard', absolute: false));
    }
}