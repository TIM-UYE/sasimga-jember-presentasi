<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($request->is('login') || $request->is('register') || $request->is('user/*')) {
            return $next($request);
        }

        if (Auth::user()->role !== 'user') {
            // If user is admin, redirect to admin dashboard
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman user.');
        }

        return $next($request);
    }
}
