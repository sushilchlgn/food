<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckoutRedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) { // If user is a guest
            // Store the intended URL (checkout page)
            session(['url.intended' => url()->current()]);
            return redirect()->route('login')->with('info', 'Please login or register to continue to checkout.');
        }

        // If user is authenticated but not verified (if email verification is enabled by Breeze)
        if (Auth::check() && !Auth::user()->hasVerifiedEmail() && config('auth.verification.enabled', true)) {
             session(['url.intended' => url()->current()]);
             return redirect()->route('verification.notice')->with('info', 'Please verify your email to continue to checkout.');
        }

        return $next($request);
    }
}