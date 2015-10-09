<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        //fwrite($asd, "Step 2-- MiddleWare Cons.. SSSSS \n");
        //fclose($asd);
        $this->auth = $auth;
        //print_r($auth);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\                                                                Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        //fwrite($asd, $this->auth->user()." Step 3-- SSSSS \n");
        $value = $request->session()->get('key');
        $s = $request->session()->has('users');
        $user = Auth::user();
        if($user) {
            //fwrite($asd, "\nHAS SESSION " . $s . "  USER 11" . $user->id . " SSSSS  \n\n");
            //fclose($asd);
        }
/*
        if($user) {
            fwrite($asd, "USER 11" . $user->id . " SSSSS \n");

            return $next($request);
        }
*/
        if ($this->auth->guest()) {
            //fwrite($asd, " Is it a GUEST " . $user->id . " GSGSGSGS \n");
            //fclose($asd);
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }
        //fclose($asd);
        return $next($request);

                if (Session::has('users')) {
                   // $asd = fopen("/home/sudipta/log.log", 'a+');
                    //fwrite($asd," HAS SESSION \n");
                    //fclose($asd);
                    Auth::login(Session::get('users'));
                    //$asd = fopen("/home/sudipta/log.log", 'a+');
                    //fwrite($asd, "\n\n After Login Function Call ---- \n");
                    //fclose($asd);
                }
                else if(Cookie::has('users')){
                        Session::put('users',Cookie::get('users'));
                        Auth::login(Cookie::get('users'));
                }
                else{
                     return redirect('auth/login');
                }

                return $next($request);

    }
}