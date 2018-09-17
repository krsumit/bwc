<?php
namespace App\Http\Controllers;
use Redirect;
use App\Category;
use Illuminate\Http\Request;
use DB;
use App\Right;
use Auth;
use App\Brand;
use App\ProductType;
use App\BrandModel;
use App\ModelImage;
use App\Product;
use \App\AttributeGroup;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Classes\GeneralFunctions;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;
//use App\Helpers\Helper;

class ProductController extends Controller {

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
        echo 'test'; exit;
       
       $brandModels=BrandModel::orderBy('created_at');
       if (isset($_GET['keyword'])) {
           $queryed = $_GET['keyword'];
           if(trim($queryed)){
                $brandModels->where('name','like','%'.$queryed.'%');
           }
       }
       $brandModels=$brandModels->paginate(config('constants.recordperpage'));
       return view('brandmodel.brand_models',compact('brandModels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create() {
      //echo Helper::cleanFileName('fldjlkj-jjlf-dd');exit;
      $model_id=Input::get('model_id');
      $model=  BrandModel::find($model_id);
      $attributeGroups=AttributeGroup::join('brand_models','attribute_groups.product_type_id','=','brand_models.product_type_id')
              ->leftJoin('attribute_group_attributes','attribute_groups.id','=','attribute_group_attributes.attribute_group_id')
              ->select(DB::raw('attribute_groups.*,group_concat(attribute_group_attributes.attribute_id) as group_attributes,group_concat(attribute_group_attributes.sequence) as attribute_sequence'))->where('brand_models.id','=',$model_id)->groupBy('attribute_groups.id')->get();
      
      
      //dd($attributeGroups);
      $brand=Brand::find($model->brand_id);
      $productType=ProductType::find($model->product_type_id); 
     //ProductType::orderBy('name','desc')->lists('name','id')->toArray();
      return view('product.create',compact('brand','productType','model','attributeGroups'));
      
   }

    public function store(Request $request){
        dd($request->file('attribute_image'));
        dd($request);
          $validation = $this->validate($request,[
            'model_name' => 'required',
            'product_type'=>'required',
            'brand'=>'required'
        ]);
          
        $brandModel = new BrandModel();
        $brandModel->brand_id =$request->brand;
        $brandModel->product_type_id = $request->product_type;
        $brandModel->name = trim($request->model_name);
        $brandModel->description =trim($request->description);
        $brandModel->save();
        $images = explode(',', $request->uploadedImages);
        $images = array_filter($images);
        $fileTran = new FileTransfer();
        $c=1;
        foreach ($images as $image) {
            $source = '';
            $dest =config('constants.aws_model_image');
            $fileTran->tranferFile($image, $source, $dest,false);
            
            if(is_file($_SERVER['DOCUMENT_ROOT'] . '/files/'.$image))
                unlink($_SERVER['DOCUMENT_ROOT'] . '/files/'.$image);
            
            $modelImage=new ModelImage();
            $modelImage->brand_model_id=$brandModel->id;
            $modelImage->image=$image;
            $modelImage->title=isset($request->imagetitle[$image]) ? $request->imagetitle[$image] : '';
            $modelImage->sequence=$c;
            $modelImage->save();
            $c++;
        }
        Session::flash('message', 'Brand model added successfully.');
        return Redirect::to('brands');
        
    }

  
    public function edit($id){       
        $rightId = 10;
        $brands=Brand::orderBy('name')->lists('name','id')->toArray();
        $productTypes= ProductType::orderBy('name','desc')->lists('name','id')->toArray();
        $brandModel=  BrandModel::find($id);  
        $photos=  ModelImage::where('brand_model_id','=',$id)->orderBy('sequence')->get();
        //dd($photos);
        return view('brandmodel.edit',compact('brandModel','brands','productTypes','photos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request) {
        
        $validation = $this->validate($request,[
            'model_name' => 'required',
            'product_type'=>'required',
            'brand'=>'required'
        ]);
        
        $brandModel = BrandModel::find($request->id);
        $brandModel->brand_id =$request->brand;
        $brandModel->product_type_id = $request->product_type;
        $brandModel->name = trim($request->model_name);
        $brandModel->description =trim($request->description);
        $brandModel->save();

        $images = explode(',', $request->uploadedImages);
        $images = array_filter($images);
        $fileTran = new FileTransfer();
        $lastImage=  ModelImage::where('brand_model_id','=',$request->id)->orderBy('sequence','desc')->first();
        if($lastImage)
            $c=$lastImage->sequence+1;
        else 
            $c=1;
        foreach ($images as $image) {
            $source = '';
            $dest =config('constants.aws_model_image');
            $fileTran->tranferFile($image, $source, $dest,false);
            
            if(is_file($_SERVER['DOCUMENT_ROOT'] . '/files/'.$image))
                unlink($_SERVER['DOCUMENT_ROOT'] . '/files/'.$image);
            
            $modelImage=new ModelImage();
            $modelImage->brand_model_id=$brandModel->id;
            $modelImage->image=$image;
            $modelImage->title=isset($request->imagetitle[$image]) ? $request->imagetitle[$image] : '';
            $modelImage->sequence=$c;
            $modelImage->save();
            $c++;
        }
        
        Session::flash('message', 'Model updated successfully.');
        return Redirect::to('brand_models');
         
    }
    
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
      
        $model=BrandModel::find($id);
        
        $products=Product::where('product_model_id','=',$id)->orderBy('created_at');

       if (isset($_GET['keyword'])) {
           $queryed = $_GET['keyword'];
           if(trim($queryed)){
                $products->where('name','like','%'.$queryed.'%');
           }
       }
       $products=$products->paginate(config('constants.recordperpage'));
       return view('product.model_products',compact('products','model'));
       
    }
  
    
    public function destroy(Request $request){
        //dd($request);
        BrandModel::whereIn('id',$request->checkItem)->delete();
        Session::flash('message', 'Model deleted successfully.');
        return Redirect::to('brand_models/');
    }
    
    
    
    public function sortImage($id, Request $request) {

        foreach ($request->row as $k => $itm) {
            $modelImage = ModelImage::find($itm);
            $modelImage->sequence = $k + 1;
            $modelImage->save();
        }

        
    }
    
    public function imageEdit(Request $request) {  
        $photo = ModelImage::find($request->id);
       return view('brandmodel.imageEdit', compact('photo'));
    }

    public function storeImageDetail(Request $request) {
        //echo $request->detail;exit;
        parse_str($request->detail);
        $photo = ModelImage::find($photo_id);
            $photo->title = $imagetitlep;
            $return = ' <td>
                            <img width="100" height="100" alt="article" src="' . config('constants.awsbaseurl') . config('constants.aws_model_image') . $photo->image . '">
                        </td><td>' . $photo->title. '</td>
                <input type="hidden" id="' . $photo->id . '" name="deleteImagel">
                <td class="center"><button class="btn btn-mini btn-danger" id="deleteImage" name="' . $photo->id . '" onclick="$(this).MessageBox(' . $photo->id . ')" type="button">Dump</button>
                    <button class="btn btn-mini btn-edit" id="deleteImage" name="image' . $photo->id . '" onclick="editModelImage(' . $photo->photo_id . ')" type="button">Edit</button>
                    <img style="width:20%; display:block; margin-left:15px;display:none;" alt="loader" src="' . asset('images/photon/preloader/76.gif') . '"></td>
               ';
        $photo->save();
        return $return;
        //print_r($request->all());
    }

    public function deleteImage(Request $request){
        $image=ModelImage::find($request->photoId);
        $fileTran=new FileTransfer();
        $fileTran->deleteFile(config('constants.aws_model_image'), $image->image);
        $image->forceDelete();
        
    }
}
