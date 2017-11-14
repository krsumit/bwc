<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use App\UserRight;
use Validator;
//use Illuminate\Http\Request;
use Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;
//use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller {
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

use AuthenticatesAndRegistersUsers,
    ThrottlesLogins;

    protected $redirectPath = '/dashboard';
    protected $loginPath = '/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    /* public function __construct()
      {
      $this->middleware('guest', ['except' => 'getLogout']);
      }

     */
    public function __construct(Guard $auth) {
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
    protected function validator(array $data) {
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
    protected function create(array $data) {
        return User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
        ]);
    }

    public function getLogout() {

        Auth::logout();
        Session::flush();
        return redirect()->guest('auth/login');
    }

    public function authenticate($email, $password) {
        $attemptSatus = Auth::attempt(['email' => $email, 'password' => $password], true);

        //$asd=fopen("/home/sudipta/log.log",'a+');
        //fwrite($asd," Saving Session \n");
        //fwrite($asd,Auth::user()." \n");
        Session::put('users', Auth::user());
        //$request->session()->put('user', Auth::user());
        //$request->session()->has('users')
        return ($attemptSatus);
        // Authentication passed...
        //echo "Here";
        //return redirect()->route('dashboard');
    }

    public function postLogin() {
        $data = Request::all();
        //print_r($data); exit;
        //$this->validate('email'=>'required','password'=>'required');
        /*
          if (Auth::attempt(['email' => $email, 'password' => $password])) {
          // Authentication passed...
          return redirect()->intended('dashboard');
          } */
        $is_authenticated = AuthController::authenticate($data['email'], $data['password']);
        //$asd=fopen("/home/sudipta/log.log",'a+');
        //fwrite($asd,"EEEE ".$is_authenticated." RRRRR \n");
        //AuthController::authenticate($request->only('email','password'));
        //echo $is_authenticated; exit;
        if ($is_authenticated == '1') {
            $has = Session::has('users');
            //$id = Session::get('id');
            //$data12 = $data->session()->all();
            // echo "Hereljlcsc;";
            $user = Auth::user();
            Auth::check();
            $id = $user->id;

            $userRights = array();
            $rights = UserRight::where('user_id', $id)->where('right_for', '1')->distinct()->get();
            foreach ($rights as $right) {
                $userRights[] = $right->rights_id;
            }
            Session::put('user_rights', $userRights);
            if (strpos(Session::get('url.intended'), 'logout') === false) {
                return redirect()->intended('/dashboard');
            } else {
                return redirect('/dashboard');
            }
        } else {
            Session::flash('error', 'Invalid email/password, please check login detail and try again.');
            return redirect('/auth/login');
        }
    }

    function forgotPassword() {
        return view('auth.forgotpassword');
    }

    function sendResetLink() {
        $data = Request::all();
        $user = User::where('email', $data['email'])->where('valid','1')->first();

        if ($user) {
            $emailData = '';
            $from_email = 'administrator@businessworld.info';
            $sub = '=?UTF-8?B?' . base64_encode("Reset password link") . '?=';
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'From: ' . $from_email . "\r\n" . 'Reply-To: ' . $from_email . "\r\n" . 'X-Mailer: PHP/' . phpversion();

            $key = urlencode(md5(time() . '_' . $user->id . '_' . $user->email . rand(100, 1000)));
            $link = url('reset/password') . '/' . $key;
            $emailData.='Hi ' . $user->name . ',<br/><br/>';
            $emailData.='Click this link to reset your password (Unable to click, copy and open in new tab):<br/>';
            $emailData.='<a target="_blank" href="' . $link . '">' . $link . '</a><br/><br/>';
            $emailData.='Thanks,<br/>Bw Businessworld';
            $user->forgot_password_id = $key;
            $user->save();
            //echo $emailData;
            //exit; 
            mail($user->email, $sub, $emailData, $headers);
        }else{
            Session::flash('error', 'Invalid email id');
            return redirect('forgot/password');
        }
    }

    function resetPassword($key) {
        $user = User::where('forgot_password_id', urldecode($key))->where('valid','1')->first();
        if($user){
            return view('auth.resetpassword', compact('user', 'key'));
        }else{
            Session::flash('error', 'Invalid url.');
            return redirect('forgot/password' );
        }
    }

    function saveNewPassword() {
        $data = Request::all();
        //print_r($data); exit;
        $user = User::where('forgot_password_id', urldecode($data['key']))->first();
        if (!$user) {
            Session::flash('error', 'Invalid url.');
            return redirect('forgot/password');
        } else if ((strlen($data['password']) < 6) || (trim($data['password']) != trim($data['conf_password']))) {
            if ((strlen($data['password']) < 6))
                Session::flash('error', 'Please check the password, must be >=6 character');
            else
                Session::flash('error', 'Both passwords don\'t matche');

            return redirect('reset/password/' . $data['key']);
        }else {
            // old password $2y$10$zPlv1ROwHh4e04p1S1BC6eWFo/W73NhT7YJSFMkqtr7llhNgmMoJe
            $user->password=bcrypt($data['password']);
            $user->save();
            Session::flash('message', 'Your passsword have changed.You can login now.');
            return redirect('auth/login');
        }
        exit;
    }

}
