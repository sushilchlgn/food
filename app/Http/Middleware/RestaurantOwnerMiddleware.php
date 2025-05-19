<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Import User model

class RestaurantOwnerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === User::ROLE_RESTAURANT_OWNER) {
            return $next($request);
        }
        // abort(403, 'Unauthorized action.');
        return redirect('/dashboard')->with('error', 'You do not have restaurant owner access.');
    }
}