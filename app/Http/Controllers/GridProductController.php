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
use App\GridProduct;
use App\GridColumn;
use App\GridRow;
use App\Channel;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\GeneralFunctions;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class GridProductController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $rightObj;

    public function __construct(){
        $this->rightObj = new Right();
    }

    public function update(Request $request,$id){
       $grid=Grid::find($id);
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
        $grid->save();
   
        Session::flash('message', 'Grid updated successfully.');
        return Redirect::to('grids?channel='.$request->channel_id);
         
    }
    

    public function show($id){
        $grid=Grid::find($id);
        $rightId=126;  
        $currentChannelId = $grid->channel_id;        
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        $channel=Channel::find($grid->channel_id);
        $girdRows=array();
        $gridColumn= GridColumn::where('grid_id','=',$grid->id)->get();
        //echo count($gridColumn).'-';
        if($grid->type=='review'){
            $gridRows= GridProduct::where('grid_id','=',$grid->id)->get();
            
            if(count($gridRows)>0){
                
            }
            
        }else{
            $girdRows= GridRow::where('grid_id','=',$grid->id);
        
           
        }
       $rows=$girdRows;
        return view('grid.product.products',compact('grid','gridColumn','channel','rows'));
        //dd($gridColumn); exit;
       // $gridProduct=$gridProduct::where('');
      
    }
  
    
    public function destroy(Request $request){
        dd($request);
        $rightId = 126;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
             return redirect('/dashboard'); 
        Grid::whereIn('id',$request->checkItem)->delete();
        Session::flash('message', 'Grid deleted successfully.');
        return Redirect::to('grids/');
    }
}
