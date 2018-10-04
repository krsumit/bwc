<?php
namespace App\Http\Controllers;
use Redirect;
use App\Category;
use Illuminate\Http\Request;
use DB;
use App\Right;
use Auth;
use App\AttributeGroup;
use App\ProductType;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\GeneralFunctions;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class AttributeGroupController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $rightObj;

    public function __construct() {
        $this->rightObj = new Right();
    }

    public function index(){
      $rightId=121;  
      $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
      $channels = $this->rightObj->getAllowedChannels($rightId);  
      if(!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
       $groups=  AttributeGroup::join('product_types','attribute_groups.product_type_id','=','product_types.id')
               ->where('channel_id',$currentChannelId)
               ->select('attribute_groups.*','product_types.name as type_name')
               ->orderBy('created_at');
       if (isset($_GET['keyword'])) {
           $queryed = $_GET['keyword'];
           if(trim($queryed)){
                $groups->where('attribute_groups.name','like','%'.$queryed.'%')->orWhere('product_types.name','like','%'.$queryed.'%');
           }
       }
       $groups=$groups->paginate(config('constants.recordperpage'));
       return view('attributegroup.attribute_groups',compact('groups','channels','currentChannelId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(){
      $rightId=122;  
      $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
      $channels = $this->rightObj->getAllowedChannels($rightId);
      if(!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');  
      $allowedChannel=array();
      foreach($channels as $channel){
          $allowedChannel[]=$channel->channel_id;
      }
      
      $productTypes= ProductType::whereIn('channel_id',$allowedChannel)->orderBy('name','desc')->lists('name','id')->toArray();  
      return view('attributegroup.create',compact('productTypes'));
    }

    public function store(Request $request) {
        $rightId=122;   
        $currentPtype=ProductType::find($request->product_type);
        if(!$this->rightObj->checkRights($currentPtype->channel_id, $rightId))
              return redirect('/dashboard');
        
        $validation = $this->validate($request,[
            'product_type' => 'required',  
            'group_name' => 'required',
        ]);
          
        $group = new AttributeGroup();
        $group->name = trim($request->group_name);
        $group->product_type_id = $request->product_type;
        $group->save();
   
        Session::flash('message', 'Group added successfully.');
        return Redirect::to('attribute-groups');
        
    }

  
    public function edit($id){       
        $rightId=122;  
        $group= AttributeGroup::find($id); // Brand::find($id); 
        $currentPtype=ProductType::find($group->product_type_id);
        $currentChannelId = $currentPtype->channel_id;
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId, $rightId))
              return redirect('/dashboard');  
        $allowedChannel=array();
        foreach($channels as $channel){
            $allowedChannel[]=$channel->channel_id;
        }
        $productTypes= ProductType::whereIn('channel_id',$allowedChannel)->orderBy('name','desc')->lists('name','id')->toArray();
         
        return view('attributegroup.edit',compact('group','productTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id) {
        $rightId=122;   
        $currentPtype=ProductType::find($request->product_type);
        if(!$this->rightObj->checkRights($currentPtype->channel_id, $rightId))
              return redirect('/dashboard');
        
         $validation = $this->validate($request,[
            'product_type' => 'required',  
            'group_name' => 'required',
        ]);
         
        $group = AttributeGroup::find($id);
        $group->name = trim($request->group_name);
        $group->product_type_id = $request->product_type;
        $group->save();
  
        Session::flash('message', 'Attribute group updated successfully.');
        return Redirect::to('attribute-groups');
         
    }
    
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
      
    }
  
    
    public function destroy(Request $request){
        $rightId=122;
        $currentAGroup=AttributeGroup::find($request->checkItem[0]);
        $currentPtype=ProductType::find($currentAGroup->product_type_id);
        if(!$this->rightObj->checkRights( $currentPtype->channel_id, $rightId))
              return redirect('/dashboard');
        AttributeGroup::whereIn('id',$request->checkItem)->delete();
        Session::flash('message', 'Attribute groups deleted successfully.');
        return Redirect::to('attribute-groups/');
    }
}
