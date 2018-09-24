<?php
namespace App\Http\Controllers;
use Redirect;
use App\Category;
use Illuminate\Http\Request;
use DB;
use App\Right;
use Auth;
use App\BrandModel;
use App\Brand;
use App\AttributeGroup;
use App\ProductType;
use App\ModelReview;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\GeneralFunctions;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class ReviewController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $rightObj;

    public function __construct() {
        $this->rightObj = new Right();
    }

    public function index() {
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create() {
     
    }

    public function store(Request $request) {
      
        
    }

  
    public function edit($id){       
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id) {
       //dd($request);
       foreach($request->reivew as $key=>$value){
           $review=ModelReview::where('brand_model_id','=',$id)->where('attribute_group_id','=',$key)->first();
           if(!$review)
               $review=new ModelReview();
           if((trim($value)) || (trim($request->rating[$key])) ){
            $review->brand_model_id=$id;
            $review->attribute_group_id=$key;
            $review->rating=$request->rating[$key];
            $review->review=$value;
            $review->save(); 
           }else{
               if($review)
                   $review->delete();
           }  
       }
        Session::flash('message', 'Review updated successfully.');
        return Redirect::to('brand-models/'); 
    }
    
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
        //echo 'Model id:'.$id;
        //echo '<b>There section is under development.</b>';exit;
       $model=  BrandModel::find($id);
      // DB::enableQueryLog();
       $attributeGroups=AttributeGroup::join('product_types','attribute_groups.product_type_id','=','product_types.id')
                ->select('attribute_groups.*')
                ->join('brand_models','product_types.id','=','brand_models.product_type_id')
                ->where('brand_models.id','=',$id)
                ->get();
       
       $reviews=array();
       foreach($attributeGroups as $attributeGroup){
           $temp=ModelReview::where('attribute_group_id','=',$attributeGroup->id)->where('brand_model_id','=',$id)->first();
           if($temp){
               $reviews[$attributeGroup->id]['rating']=$temp->rating;
               $reviews[$attributeGroup->id]['review']=$temp->review;
           }else{
               $reviews[$attributeGroup->id]['rating']='';
               $reviews[$attributeGroup->id]['review']='';
           }
       }
       //dd(DB::getQueryLog());
       $productType = ProductType::find($model->product_type_id);
       $brand = Brand::find($model->brand_id);
        return view('review.review',compact('attributeGroups','model','productType','brand','reviews'));
        
       //$attributeGroups=AttributeGroup::join()->join()->get();
    }
  
    
    public function destroy(Request $request){
      
    }
}
