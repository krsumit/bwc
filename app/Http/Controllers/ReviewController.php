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
       $rightId=125;
       $model=BrandModel::find($id);
       $productType = ProductType::find($model->product_type_id);
       $currentChannelId=$productType->channel_id;
       if(!$this->rightObj->checkRights($currentChannelId, $rightId))
              return redirect('/dashboard');

       //dd($request);
       $model->review_title=$request->review_title;
       $model->review_description=$request->review_description;
       $model->review_social_title=$request->review_social_title;
       $model->review_social_description=$request->review_social_description;
       $model->review_conclusion=$request->review_conclusion;
       
       if ($request->file('review_image')) { 
                $fileTran = new FileTransfer();
                $file = $request->file('review_image');
                $filename=GeneralFunctions::cleanFileName($request->file('review_image')->getClientOriginalName());
                $destination_path = config('constants.aws_review_image');
                $fileTran->uploadFile($file, $destination_path, $filename);
                if (trim($model->review_image)){    
                    $fileTran->deleteFile($destination_path,$model->review_image);
                } 
                $model->review_image=$filename;
         }
       
       $model->save();
       
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
       $rightId=125;
       $model=  BrandModel::find($id);
       //dd($model);
       $productType = ProductType::find($model->product_type_id);
       $currentChannelId=$productType->channel_id;
       if(!$this->rightObj->checkRights($currentChannelId, $rightId))
              return redirect('/dashboard');
       
       // DB::enableQueryLog();
       //dd($model);
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
       $brand = Brand::find($model->brand_id);
        return view('review.review',compact('attributeGroups','model','productType','brand','reviews'));
       //$attributeGroups=AttributeGroup::join()->join()->get();
    }
  
    
    public function destroy(Request $request){
      
    }
}
