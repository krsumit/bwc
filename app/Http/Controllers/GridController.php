<?php
namespace App\Http\Controllers;
use Redirect;
use App\Category;
use Illuminate\Http\Request;
use DB;
use App\Right;
use Auth;
use App\Brand;
use App\Grid;
use App\ProductType;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\GeneralFunctions;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class GridController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $rightObj;

    public function __construct(){
        $this->rightObj = new Right();
    }

    public function index() {
        
        $rightId = 126;
        $channels = $this->rightObj->getAllowedChannels($rightId);  
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        
       $grids=Grid::where('channel_id','=',$currentChannelId)->orderBy('created_at');
       if (isset($_GET['keyword'])) {
           $queryed = $_GET['keyword'];
           if(trim($queryed)){
                $grids->where('name','like','%'.$queryed.'%');
           }
       }
       $grids=$grids->paginate(config('constants.recordperpage'));
       return view('grid.grids',compact('grids','channels','currentChannelId'));
       
     /*  $rightId=115;  
      $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
      $channels = $this->rightObj->getAllowedChannels($rightId);  
      if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
      
       $productTypes=ProductType::where('channel_id','=',$currentChannelId)->orderBy('created_at');
       if (isset($_GET['keyword'])) {
           $queryed = $_GET['keyword'];
           if(trim($queryed)){
                $productTypes->where('name','like','%'.$queryed.'%');
           }
       }
       $productTypes=$productTypes->paginate(config('constants.recordperpage'));
       return view('producttype.product_types',compact('productTypes','channels','currentChannelId'));
       */
       
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create() {
      $rightId=126;  
      $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
      $channels = $this->rightObj->getAllowedChannels($rightId);  
      if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
      $girdTypes=$this->getGridType();    
      //print_r($girdTypes); exit;
      return view('grid.create',compact('channels','girdTypes'));
    }
    private function getGridType(){
        $enumValues=DB::select(DB::raw("SHOW COLUMNS FROM grids LIKE 'type'"));
        foreach($enumValues as $enumValue){
          //echo $enumValue->Type; exit;
          $girdTypes = str_replace("enum('", "", $enumValue->Type);
          $girdTypes = str_replace("')", "", $girdTypes);
          $girdTypes = explode("','", $girdTypes);
          }
          return $girdTypes;
    }      
    public function store(Request $request) {
        $rightId=126;  
        $currentChannelId=$request->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        
        $validation = $this->validate($request,[
              'grid_name' => 'required',
        ]);
        $grid = new Grid();
        $grid->name = trim($request->grid_name);
        $grid->type = trim($request->gride_type);
        $grid->channel_id=$request->channel_id;
        if($request->has('is_home_page')){
            $grid->is_home_page=$request->is_home_page;
        }
        $grid->save();
        Session::flash('message', 'Grid added successfully.');
        return Redirect::to('grids?channel='.$request->channel_id);
        
    }  
    public function edit($id){       
        $rightId=126;  
        $grid=Grid::find($id); 
        $currentChannelId = $grid->channel_id;
        $channels = $this->rightObj->getAllowedChannels($rightId);  
        $girdTypes=$this->getGridType(); 
        if(!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        return view('grid.edit',compact('grid','channels','girdTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id){
       $rightId=126;  
       $currentChannelId=$request->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');        
          
        $validation = $this->validate($request,[
            'grid_name' => 'required',
        ]);
        $grid = Grid::find($id);
        $grid->name = trim($request->grid_name);
        $grid->type = trim($request->gride_type);
        $grid->channel_id=$request->channel_id;
        if($request->has('is_home_page')){
            $grid->is_home_page=$request->is_home_page;
        }else{
             $grid->is_home_page=0;
        }
        $grid->save();
   
        Session::flash('message', 'Grid updated successfully.');
        return Redirect::to('grids?channel='.$request->channel_id);
         
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
        $rightId = 126;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
             return redirect('/dashboard'); 
        Grid::whereIn('id',$request->checkItem)->delete();
        Session::flash('message', 'Grid deleted successfully.');
        return Redirect::to('grids/');
    }
    /*
     * This function will return the grides having same channel_is as product_type 
    */
    public function getGridJson(){
        //echo 'test';exit;
        $str=trim($_GET['q']);
        $gridList=array();
        if(trim($_GET['product_type'])){
            $product_type_id=$_GET['product_type'];
            $productType=ProductType::find($product_type_id);
            if($productType){
                $gridList= Grid::select('id','name')
                        ->where('type','review')
                        ->where('channel_id',$productType->channel_id)
                        ->where('name', "like", '%'.$str . '%')
                        ->orderBy('name')
                        ->get();
            }
        }
        
        return json_encode($gridList);
    }
}
