<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah admin sudah login
        if (!session()->has('admin_logged_in')) {
            return redirect('/admin/login');
        }

        return $next($request);
    }
}