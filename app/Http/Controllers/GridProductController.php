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
use App\BrandModel;
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
        //dd($request->all());exit;
        //  echo '<pre>';
       $grid=Grid::find($id);
       $rightId=126;  
       $currentChannelId=$grid->channel_id;
       if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');        
      
        If($grid->type=='review'){
            //echo $request->product_list; exit;
           
            $gridProducts= array_filter(explode(',',$request->product_list));
            
            DB::table('grid_products')->where('grid_id', '=', $id)->whereNotIn('product_id',$gridProducts)->delete();    
            
            foreach($gridProducts as $gridProduct){
                if(!GridProduct::where('product_id','=',$gridProduct)->where('grid_id', '=', $id)->first()){
                    $insertGP=new GridProduct();
                    $insertGP->grid_id=$id;
                    $insertGP->product_id=$gridProduct;
                    $insertGP->save();
                }
            }
        }else{
            //dd($request->all());
            foreach($request->product_list as $rowKey=>$row){
                //echo '<br>'.$rowKey.'<br>';print_r($row);
                foreach($row as $colKey=>$val){
                    //echo $colKey.'<br>';echo $val.'<br>';
                    if(trim($val)){
                        
                        if(GridProduct::where('product_id','=',$val)->where('grid_id', '=', $id)->where('row_id','=',$rowKey)->where('column_id','=',$colKey)->first()){
                            continue;
                        }
                        DB::table('grid_products')->where('grid_id', '=', $id)->where('row_id','=',$rowKey)->where('column_id','=',$colKey)->delete();
                        $insertGP=new GridProduct();
                        $insertGP->grid_id=$id;
                        $insertGP->row_id=$rowKey;
                        $insertGP->column_id=$colKey;
                        $insertGP->product_id=$val;
                        $insertGP->save();
                    }else{
                      DB::table('grid_products')->where('grid_id', '=', $id)->where('row_id','=',$rowKey)->where('column_id','=',$colKey)->delete();    
                    }
                }
                
            }
        }
        //dd($request->all());
        Session::flash('message', 'Grid products updated successfully.');
        return Redirect::to('grids?channel='.$currentChannelId);
         
    }

    public function show($id){
        $grid=Grid::find($id);
        $rightId=126;  
        $currentChannelId = $grid->channel_id;      
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        $channel=Channel::find($grid->channel_id);
        $girdRows=array();
        $tableData=array();
        $gridColumns= GridColumn::where('grid_id','=',$grid->id)->orderBy('sequence')->get();
        //echo count($gridColumn).'-';
        if($grid->type=='review'){
            $tableData=array();
            $gridRows= GridProduct::where('grid_id','=',$grid->id)->get();
            if(count($gridRows)>0){
                $i=0;
                foreach($gridRows as $gridRow){
                $product=BrandModel::find($gridRow->product_id);  
                $tableData[$gridRow->product_id]['name']=$product->name;
                $productDetail=DB::table('grid_products')
                        ->leftJoin('model_reviews','grid_products.product_id','=','model_reviews.brand_model_id')
                        ->select('model_reviews.*')
                        ->where('grid_products.product_id','=',$gridRow->product_id)
                        ->where('grid_products.grid_id','=',$id)
                        ->get();
                
                    foreach($productDetail as $detail){
                        $tableData[$gridRow->product_id]['data'][$detail->attribute_group_id]=$detail;
                    }
                    
                }
            }
            //dd($tableData);
            $productList = DB::table('brand_models')
                ->join('grid_products','brand_models.id','=','grid_products.product_id')
                ->whereNull('brand_models.deleted_at')
                ->where('grid_products.grid_id','=',$grid->id)
                ->orderBy('brand_models.name')
                ->select('brand_models.id','brand_models.name')
                ->get();
            $productList=json_encode($productList);
        }else{
            $girdRows= GridRow::where('grid_id','=',$id)->orderBy('sequence')->get();
             $productDetails = DB::table('brand_models')
                ->join('grid_products','brand_models.id','=','grid_products.product_id')
                ->whereNull('brand_models.deleted_at')
                ->where('grid_products.grid_id','=',$grid->id)
                ->orderBy('brand_models.name')     
                ->select('brand_models.id','brand_models.name','grid_products.row_id','grid_products.column_id') 
                ->get();  
             //dd($productDetails);
             foreach($productDetails as $productDetail){
                 $productList[$productDetail->row_id][$productDetail->column_id]=array('id'=>$productDetail->id,'name'=>$productDetail->name);
             }
            // dd($productList);
        }
       $rows=$girdRows;
        return view('grid.product.products',compact('grid','gridColumns','channel','rows','productList','tableData'));
     
    }
  
    
    public function destroy(Request $request){
//        dd($request);
//        $rightId = 126;
//        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
//             return redirect('/dashboard'); 
//        Grid::whereIn('id',$request->checkItem)->delete();
//        Session::flash('message', 'Grid deleted successfully.');
//        return Redirect::to('grids/');
    }
}
