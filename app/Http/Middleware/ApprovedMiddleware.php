<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ApprovedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_approved) {
            return $next($request);
        }

        // If not approved, logout and redirect with message
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is pending admin approval.');
        }

        return redirect()->route('login');
    }
}
