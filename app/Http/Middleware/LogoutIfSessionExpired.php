<?php

namespace App\Http\Middleware;
use Closure;

class LogoutIfSessionExpired
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];
    
    public function handle($request,Closure $next)
    {
       echo 'test'; exit;
    }
    
}
