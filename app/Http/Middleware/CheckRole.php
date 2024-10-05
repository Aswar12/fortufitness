<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($request->path() == 'admin/login') {
            return redirect('/login');
        }
        if ($user) {
            if ($user->role == 'admin') {
                return redirect('/admin');
            }
        }
        return $next($request);
    }
}
