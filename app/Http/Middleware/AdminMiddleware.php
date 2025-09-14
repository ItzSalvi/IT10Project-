<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('AdminMiddleware called', [
            'url' => $request->url(),
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email ?? 'not logged in',
            'user_role' => auth()->user()->role ?? 'no role'
        ]);
        
        if (!auth()->check()) {
            \Log::warning('AdminMiddleware: User not authenticated');
            return redirect()->route('login');
        }

        if (!auth()->user()->isAdmin()) {
            \Log::error('AdminMiddleware: User is not admin', [
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role
            ]);
            abort(403, 'Access denied. Admin privileges required.');
        }

        \Log::info('AdminMiddleware: Access granted');
        return $next($request);
    }
}
