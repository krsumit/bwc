<?php
namespace App\Http\Middleware;
use Closure;
use Session;
use Illuminate\Contracts\Auth\Guard;
class LogoutIfSessionExpired
{
    
    protected $auth;
    
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
     
    public function handle($request,Closure $next)
    {
        //if($this->auth->check()){
        //    echo '1';exit;
        //}else{
        //   echo '2'; exit; 
        //}
        
         if (($this->auth->check()) && (!$request->session()->has('users'))) {
            ///print_r($request->session()->all());   
            $this->auth->logout();
            Session::flush();
            Session::flash('error', 'Session expired, please login again.');
            return redirect('auth/login');
        }
        return $next($request);
   }
    
}
?>
