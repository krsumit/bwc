<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Validator;
use Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;

//use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectPath = '/dashboard';
    
    protected $loginPath = '/login';
    
    
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    */
	public function __construct(Guard $auth)
	{
//            echo '<pre>';
//    print_r(Auth::user()); exit;;
           
		$this->auth = $auth;
		//$this->registrar = $registrar;
        //$this->middleware('auth', ['except' => 'getLogout','postLogin']);
        //$this->middleware('auth', ['except' => 'getLogout','postLogin']);
        //$this->middleware('guest', ['except' => 'getLogout','getLogin']);
	}
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
    
    public function getLogout()
    {
        
        Auth::logout();
        return redirect()->guest('auth/login');
    }
    
    public function authenticate($email,$password)
    {
        $attemptSatus=Auth::attempt(['email' => $email, 'password' => $password],true);

        //$asd=fopen("/home/sudipta/log.log",'a+');
        //fwrite($asd," Saving Session \n");
        //fwrite($asd,Auth::user()." \n");
            Session::put('users',Auth::user());
        //$request->session()->put('user', Auth::user());
        //$request->session()->has('users')
        return ($attemptSatus);
            // Authentication passed...
            //echo "Here";
            //return redirect()->route('dashboard');
    }
    
    public function postLogin()
    {
        $data = Request::all();
        //print_r($data);
        //$this->validate('email'=>'required','password'=>'required');
        /*
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            // Authentication passed...
            return redirect()->intended('dashboard');
        }*/
        $is_authenticated = AuthController::authenticate($data['email'],$data['password']);
        //$asd=fopen("/home/sudipta/log.log",'a+');
        //fwrite($asd,"EEEE ".$is_authenticated." RRRRR \n");
        //AuthController::authenticate($request->only('email','password'));
        if($is_authenticated == '1'){
            $has = Session::has('users');
            //$id = Session::get('id');
            //$data12 = $data->session()->all();
           // echo "Hereljlcsc;";
            $user = Auth::user();
            //print_r($user);
            print Auth::check();
            return redirect()->intended('/dashboard');
        }else{
            print "cudnt authenticate";
            return redirect('/auth/login');
        }
        
    }
    
    
}
