<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $user = $request->user();
        $actions = $request->route()->getAction();

        if (! $user->isAdmin()) {
            flash("Доступ к административному разделу запрещён", "danger");
            return redirect()->route("posts.index");
        }

        return $next($request);
    }
}
