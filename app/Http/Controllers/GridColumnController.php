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
use App\Channel;
use App\GridColumn;
use App\ProductType;
use App\AttributeGroup;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\GeneralFunctions;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class GridColumnController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $rightObj;

    public function __construct(){
        $this->rightObj = new Right();
    }

    //// This function is being used to populate attribute group on change of product type drop-down
    public function index() { 
       $prodTypeId= $_GET['product_type']; 
       $attGroups= AttributeGroup::where('product_type_id','=',$prodTypeId)->orderBy('name')->lists('id','name');  
       return $attGroups;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create() {
      $grid=Grid::find($_GET['grid']); 
      $rightId=126;  
      $currentChannelId = $grid->channel_id;        
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
      $channel=Channel::find($grid->channel_id);
      $productTypes= ProductType::where('channel_id','=',$grid->channel_id)->get();
      return view('grid.column.create',compact('channel','grid','productTypes'));
    }
   
    public function store(Request $request) {
        //echo '<pre>';
        //print_r($request->all());
        $grid=Grid::find($request->grid_id);
        //dd($grid->type);
        $rightId=126;  
        $currentChannelId=$grid->channel_id;  
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard'); 
        
        if ($grid->type == "review") {
            $validation = $this->validate($request,[
                'column_name' => 'required',
                'product_type' => 'required',
                'attribute_group' => 'required',
            ]);
        } else {
            $validation = $this->validate($request, [
                'column_name' => 'required'
            ]);
        }
        $seq=GridColumn::where('grid_id','=',$request->grid_id)->max('sequence')+1;
        $column = new GridColumn();
        $column->name = trim($request->column_name);
        $column->grid_id=$request->grid_id;
        $column->sequence=$seq;
        if ($grid->type == "review")
            $column->att_group_id=$request->attribute_group;
        $column->save();
        Session::flash('message', 'Column added successfully.');
        return Redirect::to('grid-columns/'.$request->grid_id);
    }  
    public function edit($id){       
        $rightId=126;  
        $column= GridColumn::find($id);
        $grid=Grid::find($column->grid_id); 
        $currentChannelId = $grid->channel_id;
        $channels = $this->rightObj->getAllowedChannels($rightId);  
        if(!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        
        $channel=Channel::find($grid->channel_id);
        $productTypes= ProductType::where('channel_id','=',$grid->channel_id)->get();
        $attGroup=AttributeGroup::find($column->att_group_id);
        $attGroups= AttributeGroup::where('product_type_id','=',$attGroup->product_type_id)->orderBy('name')->get();
        return view('grid.column.edit',compact('grid','channel','column','productTypes','attGroup','attGroups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id){
       $column = GridColumn::find($id); 
       $grid=Grid::find($column->grid_id);
       $rightId=126;  
       $currentChannelId=$grid->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');   
         
        $validation = $this->validate($request,[
            'column_name' => 'required',
            'product_type' => 'required',
            'attribute_group' => 'required',
        ]);
        $column->name = trim($request->column_name);
        $column->att_group_id=$request->attribute_group;
        $column->save();
        Session::flash('message', 'Column updated successfully.');
        return Redirect::to('grid-columns/'.$column->grid_id);
    }
    
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
        $grid=Grid::find($id);
        $rightId = 126;
        $currentChannelId = $grid->channel_id;        
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        $channel=Channel::find($grid->channel_id);
        $columns=GridColumn::where('grid_id','=',$grid->id)->orderBy('sequence')->get();
        return view('grid.column.columns',compact('columns','grid','channel'));
    }
  
    
    public function destroy($id,Request $request){
        //echo $id; exit;
        //dd($request->all());
        $rightId = 126;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
             return redirect('/dashboard'); 
        GridColumn::whereIn('id',$request->checkItem)->delete();
        Session::flash('message', 'Column(s) deleted successfully.');
        return Redirect::to('grid-columns/'.$id);
    }
    
    public function sortColumns($id,Request $request) {
        $gridId=$id;
        foreach($request->item as $k => $itm){
            $gridColumn=GridColumn::find($itm);
            $gridColumn->sequence=$k+1;
            $gridColumn->save();
        }
    }
}
