<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\User;
use App\UserRight;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class RightsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //Authenticate User
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        //Authenticate User to Access Rights Management
        $rights = RightsController::getRights($uid);   
        if(count($rights) < 1){
            return redirect()->intended('/dashboard');
        }
        
        $roles = DB::table('user_types')->where('valid','1')->get();
        //print_r($roles);
        
        //Existing Users
        $users = User::where('valid','1')->get();
            $ucArr = array();
            //Get Their Channels
            foreach($users as $u){
                $channels = DB::table('channels')
                    ->join('rights','rights.pagepath','=','channels.channel_id')
                    ->join('user_rights', 'user_rights.rights_id','=','rights.rights_id')
                    ->select('channels.*')
                    ->where('rights.label', '=', 'channel')
                    ->where('user_rights.user_id', '=', $u['id'])
                    ->get();
                
                $str = '';
                foreach($channels as $c){
                    $str.=$c->channel.", ";
                }
                $str = substr($str, 0, -2);
                $ucArr[$u['id']] = $str;                
            }
            
        return view('rights.index',compact('roles','users','ucArr'));
    }

    /*
     * Get user's rights
     */
    public function getRights($uid){

        $rights = DB::table('rights')
        ->join('user_rights','user_rights.rights_id','=','rights.rights_id')
        ->where('user_rights.user_id','=',$uid)
        ->where('rights.label','=','cmsRights')        
        ->get();

        return $rights;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //$validator = Validator::make($request->all(), [    
        //print_r($_POST);
        $messages = [
            'same'    => 'The :attribute and :other must match.',            
        ];
        
        $this->validate($request,[
            //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'role' => 'required',
            'mobile' => 'numeric',    
            
        ],$messages);
        /*
        if($validator){    
        if ($validator->fails()) {
            return redirect('/rights')
                        ->withErrors($validator)
                        ->withInput();
        }}
        */
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->mobile = $request->mobile;
        $user->user_type_id = $request->role;
                
        $user->save();
        $userid = $user->id;
        
        $BW = 0;
        $BWH = 0;
        //Add Channel(s) on ID
        if($request->BW){
            $userRight = new UserRight();
            $userRight->user_id = $userid;
            $userRight->rights_id = 4;
            $userRight->save();
            $BW = 1;
        }
        if($request->BWH){
            $userRight = new UserRight();
            $userRight->user_id = $userid;
            $userRight->rights_id = 5;
            $userRight->save();
            $BWH = 1;
        }       
        
        $roles = DB::table('user_types')->where('valid','1')->get();
        $name = $request->name;
        $email = $request->email;
        $mobile = $request->mobile;
        $roleO = $request->role;
        //$name = $request->name;
        
        return view('rights.manage',compact('roles','name','email','mobile','roleO','BW','BWH','userid'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $user = User::find($id);
        $userid = $id;
        $name = $user->name;
        $email = $user->email;
        $mobile = $user->mobile;
        $roleO = $user->user_type_id;        
        $roles = DB::table('user_types')->where('valid','1')->get();
        $old = DB::table('rights')
            ->join('user_rights','rights.rights_id','=','user_rights.rights_id')
            ->select(array(DB::raw('group_concat(rights.rights_id) as allrights')))
            ->where('user_rights.user_id','=',$id)
            ->first();
       // print_r($old);exit;
          $delArrcheck = explode(',', $old->allrights);
         //print_r($delArrcheck);die;
        //Get Channels
        $BW = $BWH = 0;
        $bw = UserRight::where('rights_id','4')->where('user_id',$id)->get();
        $bwh = UserRight::where('rights_id','5')->where('user_id',$id)->get();
        if(count($bw) > 0){$BW =1;}
        if(count($bwh) > 0){$BWH =1;}
        
        return view('rights.manage',compact('roles','name','email','mobile','roleO','BW','BWH','userid','delArrcheck'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {        
        print_r($_POST);
        //Validation
        $this->validate($request,[
            'email' => 'required|email',      
            'role' => 'required',
            'mobile' => 'numeric',         
        ]);
        
        //add/edit records to user_rights Table - on userid
            //delete Existing Rights
            $ur = UserRight::where('user_id',$request->id)->delete();
        //Add Each New in Loop
        foreach($request->rightArr as $right){
            if($right == 0){continue;}
            $ur = new UserRight();
            $ur->user_id = $request->id;
            $ur->rights_id = $right;
            $ur->save();
        }
        //add Channels
        //Add Channel(s) on ID
        if($request->BW){
            $userRight = new UserRight();
            $userRight->user_id = $request->id;
            $userRight->rights_id = 4;
            $userRight->save();           
        }
        if($request->BWH){
            $userRight = new UserRight();
            $userRight->user_id = $request->id;
            $userRight->rights_id = 5;
            $userRight->save();         
        }
        
        //update users Table
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->user_type_id = $request->role;
                
        $user->save();
        
        return redirect('/rights/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }        
        $deleteUser = User::find($id);
        $deleteUser->valid = 0;
        $deleteUser->save();
        
        return;
    }
}
