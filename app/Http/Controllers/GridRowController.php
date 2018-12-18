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
use App\GridRow;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\GeneralFunctions;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class GridRowController extends Controller {

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
        
        Session::flash('error', 'Please go through grid listing page');
        return Redirect::to('dashboard');
      
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
      //print_r($girdTypes); exit;
      return view('grid.row.create',compact('channel','grid'));
    }
   
    public function store(Request $request) {
        $grid=Grid::find($request->grid_id);
        $rightId=126;  
        $currentChannelId=$grid->channel_id;  
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');  
        
        $validation = $this->validate($request,[
              'row_name' => 'required',
        ]);
        $seq=GridRow::where('grid_id','=',$request->grid_id)->max('sequence')+1;
        $row = new GridRow();
        $row->name = trim($request->row_name);
        $row->grid_id=$request->grid_id;
        $row->sequence=$seq;
        $row->save();
        Session::flash('message', 'Row added successfully.');
        return Redirect::to('grid-rows/'.$request->grid_id);
        
    }  
    public function edit($id){       
        $rightId=126;  
        $row= GridRow::find($id);
        $grid=Grid::find($row->grid_id); 
        $currentChannelId = $grid->channel_id;
        $channels = $this->rightObj->getAllowedChannels($rightId);  
        if(!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        $channel=Channel::find($grid->channel_id);
        return view('grid.row.edit',compact('grid','channel','row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id){
       $row = GridRow::find($id); 
       $grid=Grid::find($row->grid_id);
       $rightId=126;  
       $currentChannelId=$grid->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');   
         
        $validation = $this->validate($request,[
            'row_name' => 'required',
        ]);
        $row->name = trim($request->row_name);
        $row->save();
        Session::flash('message', 'Row updated successfully.');
        return Redirect::to('grid-rows/'.$row->grid_id);
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
        $rows=GridRow::where('grid_id','=',$grid->id)->orderBy('sequence')->get();
        return view('grid.row.rows',compact('rows','grid','channel'));
    }
  
    
    public function destroy($id,Request $request){
        echo $id; exit;
        //dd($request->all());
        $rightId = 126;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
             return redirect('/dashboard'); 
        GridRow::whereIn('id',$request->checkItem)->delete();
        Session::flash('message', 'Row(s) deleted successfully.');
        return Redirect::to('grid-rows/'.$id);
    }
    
    public function sortRows($id,Request $request) {
        $gridId=$id;
        foreach($request->item as $k => $itm){
            $gridRow=GridRow::find($itm);
            $gridRow->sequence=$k+1;
            $gridRow->save();
        }
        
    }
}
