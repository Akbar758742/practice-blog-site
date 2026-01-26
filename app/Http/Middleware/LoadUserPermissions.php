<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoadUserPermissions
{
    /**
     * Eager load user roles and permissions to prevent N+1 queries.
     * This middleware loads all roles and their permissions in one query,
     * making subsequent permission checks much faster.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Eager load roles with their permissions
            auth()->user()->load('roles.permissions');
        }

        return $next($request);
    }
}
