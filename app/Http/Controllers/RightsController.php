<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\User;
use App\UserRight;
use App\Roles;
use App\Right;
use App\Channel;
use App\UserChannelRight;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class RightsController extends Controller
{
    
    private $rightObj;
    public function __construct() {
        $this->middleware('auth');
        $this->rightObj= new Right();
    
    }
    
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
        
        
         /* Right mgmt start */
        $rightId=72;
          if(!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */    
            //return 'You are not authorized to access.';
        
        
        $roles = DB::table('roles')->where('valid','1')->get();
       
        //Existing Users
        $users = User::where('valid','1')->orderBy('updated_at','desc')->get();
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
            
        $allchannel=DB::table('channels')->where('valid','1')->get();
        return view('rights.index',compact('roles','users','ucArr','allchannel'));
    }

    /*
     * Get user's rights
     */
    /*public function getRights($uid){

        $rights = DB::table('rights')
        ->join('user_rights','user_rights.rights_id','=','rights.rights_id')
        ->where('user_rights.user_id','=',$uid)
        ->where('rights.label','=','cmsRights')        
        ->get();

        return $rights;
    }*/
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
         /* Right mgmt start */
        $rightId=72;
          if(!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */  
          
        //$validator = Validator::make($request->all(), [    
        //print_r($_POST);
        $messages = [
            'same'    => 'The :attribute and :other must match.',            
        ];
        
        $this->validate($request,[
            //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'role' => 'required',
            'mobile' => 'numeric',    
            
        ],$messages);
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->mobile = $request->mobile;
        $user->user_type_id = $request->role;
                
        $user->save();
        $userid = $user->id;
        $rolerights=  UserRight::where('right_for','2')->where('user_id',$request->role)->get();
        foreach($request->rightArr as $right){
            //if($right == 0){continue;}
            $ur = new UserChannelRight();
            $ur->user_id = $userid;
            $ur->channel_id = $right;
            $ur->save();
            foreach($rolerights as $roleright) {
            $rur = new UserRight();
            $rur->user_id = $userid;
            $rur->channel_id = $right;
            $rur->rights_id = $roleright->rights_id;
            $rur->right_for = '1';
            $rur->save();
            }
           //print_r($request->rightArr); exit;
        }
        Session::flash('message', 'User added successfully.');
        return redirect('/rights');
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
        /* Right mgmt start */
        $rightId=72;
          if(!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */ 
          
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
        $delArrcheck = explode(',', $old->allrights);
        $allchannel=DB::table('channels')->where('valid','1')->get();
         $rightChannels=DB::table('user_channels_right')->where('user_id',$id)->get();
        if( count($rightChannels)>0){
            $tempchannels=array();
        foreach($rightChannels as $rightchannel){
            //print_r($rightchannel); exit;
            $tempchannels[]=$rightchannel->channel_id;
        }
        $rightChannels=$tempchannels;
        }else{
            $rightChannels=array();
        }
        return view('rights.manage',compact('roles','name','email','mobile','roleO','userid','delArrcheck','allchannel','rightChannels'));
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
       
        
        /* Right mgmt start */
        $rightId=72;
          if(!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */ 
          
          
        $this->validate($request,[
            'name' =>'required',
            'email' => 'required|email',      
            'role' => 'required',
            'mobile' => 'numeric',
            'password' => 'min:6',
            'password_confirmation' => 'same:password',
        ]);
        
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        if(trim($request->password))
        $user->password = bcrypt($request->password);
        
        $user->mobile = $request->mobile;
        $user->user_type_id = $request->role;
        $user->update();
        
        $userid = $request->id;
        //Deleting those channel right's which are unchecked while editing
      // print_r($request->channelArr); exit;
        if($request->channelArr){ //echo '1'; exit;
           UserRight::where('user_id',$userid)->where('right_for','1')->whereNotIn('channel_id',$request->channelArr)->delete();
            //Deleting old channel which are unchecked while editing
            UserChannelRight::whereNotIn('channel_id',$request->channelArr)->where('user_id',$userid)->delete();
 
        }else{ //echo '2'; exit;
            UserRight::where('user_id',$userid)->where('right_for','1')->delete();
            //Deleting old channel which are unchecked while editing
            UserChannelRight::where('user_id',$userid)->delete();
        }
        
        $useroldchannels=UserChannelRight::where('user_id',$request->id)->get();
        $tempchannels=array();
        foreach($useroldchannels as $oldchannels){
            $tempchannels[]=$oldchannels->channel_id;
        }
        $useroldchannels=$tempchannels;
       
        if($request->channelArr){
        
        foreach($request->channelArr as $right){
            $useroldrights=UserRight::where('user_id',$userid)->where('right_for',1)->where('channel_id',$right);
            //if($right == 0){continue;}
            if(!in_array($right, $useroldchannels)){
                $ur = new UserChannelRight();
                $ur->user_id = $userid;
                $ur->channel_id = $right;
                $ur->save();
            }
            
            UserRight::where('user_id',$userid)->where('right_for','1')->where('channel_id',$right)->whereNotIn('rights_id',$request->rightArr[$right])->delete();
            
            $oldrights=DB::table('user_rights')->select(DB::raw('group_concat(`rights_id`) as oldrights,count(*)'))->where('right_for','1')->where('user_id',$userid)->where('channel_id',$right)->first();
            
             $oldrights->oldrights; 
             $oldrights=explode(',',$oldrights->oldrights);
             //echo '<pre>';
             //print_r($oldrights); exit;
            
            foreach ($request->rightArr[$right] as $roleright) {
                if (!in_array($roleright, $oldrights)) {
                    $rur = new UserRight();
                    $rur->user_id = $userid;
                    $rur->channel_id = $right;
                    $rur->rights_id = $roleright;
                    $rur->right_for = '1';
                    $rur->save();
                }
            }
            //print_r($request->rightArr); exit;
        }
        }
        
        Session::flash('message', 'Role updated successfully.');
        return redirect('/rights');
        
        
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
    
    public function manageRole(){
       /* Right mgmt start */
        $rightId=73;
        if(!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        $roles=Roles::where('valid','1')->get();
        return view('rights.role_manage',compact('roles'));
    }
    
     public function destroyRole() {
         
         /* Right mgmt start */
        $rightId=73;
        if(!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return 'You are not authorized to access.';
        /* Right mgmt end */
        
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
            $delArr = explode(',', $id);
            foreach ($delArr as $d) {
                $deleteRl = Roles::find($d);
                $deleteRl->valid='0';
                $deleteRl->update();
            }
          
        }
        return;
    }
    /* Add role form */
    public function addRole(){
        /* Right mgmt start */
        $rightId=73;
        if(!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
       return view('rights.role_add');
    }
    /* store role, called after submitting the from from addRole */
    public function storeRole(Request $request){
        /* Right mgmt start */
        $rightId=73;
        if(!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        $this->validate($request,[
            //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
            'name' => 'required',
        ]);
        
        $roles=New Roles();
        $roles->name=$request->name;
        $roles->save();
        $id = $roles->id;
        foreach($request->rightArr as $right){
           // if($right == 0){continue;}
            $ur = new UserRight();
            $ur->user_id = $id;
            $ur->rights_id = $right;
            $ur->right_for='2';
            $ur->save();
        }
        Session::flash('message', 'Role added successfully.');
        return redirect('/roles/manage');
        
        
    }
    /* Edit role form */
    public function editRole(Request $request){
        /* Right mgmt start */
        $rightId=73;
        if(!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        $roleDetail=  Roles::find($request->id);
        return view('rights.role_edit',compact('roleDetail'));  
    }
    //
    public function updateRole(Request $request) {
        /* Right mgmt start */
        $rightId=73;
        if(!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        $role = Roles::find($request->id);
        $role->name = $request->name;
        $role->update();
        $deltedRows = UserRight::whereNotIn('rights_id', $request->rightArr)->where('user_id', $request->id)->where('right_for', '2')->delete();
        $selectedRights = UserRight::where('right_for', '2')->where('user_id', $request->id)->get();
        $tempRight = array();
        foreach ($selectedRights as $right) {
            $tempRight[] = $right->rights_id;
        };
        $insertarray = array_diff($request->rightArr, $tempRight);
        foreach ($insertarray as $right) {
            $ur = new UserRight();
            $ur->user_id = $request->id;
            $ur->rights_id = $right;
            $ur->right_for = '2';
            $ur->save();
        }
        
        Session::flash('message', 'Role updated successfully.');
        return redirect('/roles/manage');
    }

    public function getRoleChannelPermission(Request $request){
        $channelDetial='';
        $selectedRights=new \stdClass();
        if($request->get('channelId')){
        $channelDetial=Channel::find($request->get('channelId'));
            if($request->get('id') && $request->get('right_for')){
             if($request->get('right_for')=='1'){ //To edit role which is alredy assinged to user   
             $selectedRights=UserRight::where('user_id',$request->get('id'))->where('right_for',trim($request->get('right_for')))->where('channel_id',$request->get('channelId'))->get();
             }else{ // To ad channel on user rigt edit/manage page
             $selectedRights=UserRight::where('user_id',$request->get('id'))->where('right_for',trim($request->get('right_for')))->get();    
             }
             
             }
        }else{ // It's for role page 
            if($request->get('id') && $request->get('right_for')){ // It's for role edit page
            $selectedRights=UserRight::where('user_id',$request->get('id'))->where('right_for',trim($request->get('right_for')))->get();
            }
        }
         $tempRights=array();
         foreach($selectedRights as $right){
             $tempRights[]=$right->rights_id;
         }   
         //print_r($channelDetial->channel);exit;
        $selectedRights=$tempRights;
        //print_r($selectedRights); exit;
        $rights=Right::where('valid','1')->orderBy('sort_sequence')->get();
        return view('rights.role_channel_perimssion',compact('rights','channelDetial','selectedRights')); 
    }
}
