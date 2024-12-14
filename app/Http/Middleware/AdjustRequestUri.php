<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdjustRequestUri
{
    public function handle(Request $request, Closure $next)
    {
        $originalUri = $request->getRequestUri();
        $newUri = str_replace('/projects/plannermoney', '', $originalUri);

        $request->server->set('REQUEST_URI', $newUri);

        return $next($request);
    }
}
