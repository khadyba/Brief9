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
 // CheckAdminRole.php

public function handle($request, Closure $next)
{
    $user= $request->user();

    if ($user->roles === 'admin') {
        return $next($request);
    }
    return response()->json(['message' => 'Vous n\'êtes pas autorisé à effectuer cette action.'], 403);
}


}



