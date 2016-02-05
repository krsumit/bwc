<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Session;
class VerifyCsrfToken extends BaseVerifier
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
        if($request->input('_token')) {
        if ( \Session::getToken() != $request->input('_token')) {
            Session::flash('error', 'Someting has goen wrong, please try again.');
            //return redirect('/dashboard');
            return redirect($_SERVER['HTTP_REFERER']);
        }
        }
       return parent::handle($request, $next);
       //return $next($request);
    }
    
}
