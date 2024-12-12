<?php

namespace Modules\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Ensure a user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized: User not authenticated'], 401);
        }

        // Retrieve the authenticated user
        $user = Auth::user();

        // Debugging: Log the user's role
        Log::info('Authenticated User Role:', ['role' => $user->role]);

        // Check if the user's role matches the required role
        // Role 0 = User, Role 1 = Admin
        if ($role == 'admin' && $user->role != 1) {
            return response()->json(['error' => 'Unauthorized: Insufficient role'], 403);
        }

        // If checking for user role
        if ($role == 'user' && $user->role != 0) {
            return response()->json(['error' => 'Unauthorized: Insufficient role'], 403);
        }

        return $next($request);
    }
}
