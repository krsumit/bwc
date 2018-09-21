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
use \App\ProductAttributeValue;
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
        $products = Product::join('brand_models', 'products.model_id', '=', 'brand_models.id')->select('products.*', 'brand_models.name as model_name')->orderBy('created_at');

        if (isset($_GET['keyword'])) {
            $queryed = $_GET['keyword'];
            if (trim($queryed)) {
                $products->where('name', 'like', '%' . $queryed . '%');
            }
        }
        $products = $products->paginate(config('constants.recordperpage'));
        return view('product.products', compact('products', 'model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create() {
        //echo Helper::cleanFileName('fldjlkj-jjlf-dd');exit;
        $model_id = Input::get('model_id');
        $copy_id = 0;
        $productName = '';
        if (Input::get('copy_id')) {
            $copy_id = Input::get('copy_id');
            $productName = Product::find($copy_id)->name;
        }
        //dd($copy_id);
        $model = BrandModel::find($model_id);
        $attributeGroups = AttributeGroup::join('brand_models', 'attribute_groups.product_type_id', '=', 'brand_models.product_type_id')
                        ->leftJoin('attribute_group_attributes', 'attribute_groups.id', '=', 'attribute_group_attributes.attribute_group_id')
                        ->select(DB::raw('attribute_groups.*,group_concat(attribute_group_attributes.attribute_id) as group_attributes,group_concat(attribute_group_attributes.sequence) as attribute_sequence'))->where('brand_models.id', '=', $model_id)->groupBy('attribute_groups.id')->get();


        //dd($attributeGroups);
        $brand = Brand::find($model->brand_id);
        $productType = ProductType::find($model->product_type_id);
        //ProductType::orderBy('name','desc')->lists('name','id')->toArray();
        return view('product.create', compact('brand', 'productType', 'model', 'attributeGroups', 'copy_id', 'productName'));
    }

    public function store(Request $request) {
        //dd($request->file('attribute_file'));
        $product = new Product();
        $product->model_id = $request->model_id;
        $product->name = $request->product_name;
        $product->save();
        $productId = $product->id;

        foreach ($request->attribute_text as $key => $val) {
            $productAttributeValue = new ProductAttributeValue();
            $productAttributeValue->product_id = $productId;
            $productAttributeValue->attribute_id = $key;
            $productAttributeValue->value = $val;
            $productAttributeValue->save();
        }
        foreach ($request->attribute_textarea as $key => $val) {
            $productAttributeValue = new ProductAttributeValue();
            $productAttributeValue->product_id = $productId;
            $productAttributeValue->attribute_id = $key;
            $productAttributeValue->value = $val;
            $productAttributeValue->save();
        }
        foreach ($request->attribute_selectbox as $key => $val) {
            $productAttributeValue = new ProductAttributeValue();
            $productAttributeValue->product_id = $productId;
            $productAttributeValue->attribute_id = $key;
            $productAttributeValue->attribute_value_id = $val;
            $productAttributeValue->save();
        }

        foreach ($request->attribute_multiselect as $key => $valArray) {

            foreach ($valArray as $val) {
                $productAttributeValue = new ProductAttributeValue();
                $productAttributeValue->product_id = $productId;
                $productAttributeValue->attribute_id = $key;
                $productAttributeValue->attribute_value_id = $val;
                $productAttributeValue->save();
            }
        }

        foreach ($request->attribute_colorpicker as $key => $valArray) {

            foreach ($valArray as $k => $val) {

                $productAttributeValue = new ProductAttributeValue();
                $productAttributeValue->product_id = $productId;
                $productAttributeValue->attribute_id = $key;
                $productAttributeValue->value = $val;
                $productAttributeValue->caption = $request->label_attribute_colorpicker[$key][$k];
                $productAttributeValue->save();
            }
        }


        foreach ($request->file('attribute_file') as $key => $file) {
            if ($file) {
                $fileTran = new FileTransfer();
                //$file = $request->file('logo_image');
                $filename = GeneralFunctions::cleanFileName($file->getClientOriginalName());
                $destination_path = config('constants.aws_attribute_file');
                $fileTran->uploadFile($file, $destination_path, $filename);

                $productAttributeValue = new ProductAttributeValue();
                $productAttributeValue->product_id = $productId;
                $productAttributeValue->attribute_id = $key;
                $productAttributeValue->value = $filename;
                $productAttributeValue->caption = $request->label_attribute_file[$key];
                $productAttributeValue->save();
            }
        }


        Session::flash('message', 'Product model added successfully.');
        return Redirect::to('products/' . $request->model_id);
    }

    public function edit($id) {
        $product = Product::find($id);
        $model_id = $product->model_id;
        $model = BrandModel::find($model_id);
        $attributeGroups = AttributeGroup::join('brand_models', 'attribute_groups.product_type_id', '=', 'brand_models.product_type_id')
                        ->leftJoin('attribute_group_attributes', 'attribute_groups.id', '=', 'attribute_group_attributes.attribute_group_id')
                        ->select(DB::raw('attribute_groups.*,group_concat(attribute_group_attributes.attribute_id) as group_attributes,group_concat(attribute_group_attributes.sequence) as attribute_sequence'))->where('brand_models.id', '=', $model_id)->groupBy('attribute_groups.id')->get();
        $brand = Brand::find($model->brand_id);
        $productType = ProductType::find($model->product_type_id);

        return view('product.edit', compact('brand', 'productType', 'model', 'attributeGroups', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {

        //echo '<b>There is some issue working on it</b>';exit;
        //dd($request->file('attribute_file')[10]);
        $product = Product::find($id);
        $product->name = $request->product_name;
        $product->save();
        $productId = $product->id;
        $oldAttributeLists = ProductAttributeValue::join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')->select('attributes.*', 'attribute_types.type')->join('attribute_types', 'attributes.attribute_type_id', '=', 'attribute_types.id')->where('product_attribute_values.product_id', '=', $id)->lists('type', 'id')->toArray();
        $oldAttributes = array_keys($oldAttributeLists);
        $newAttributes = array_merge(array_keys($request->attribute_text), array_keys($request->attribute_textarea), array_keys($request->attribute_selectbox), array_keys($request->attribute_multiselect), array_keys($request->attribute_colorpicker), array_keys($request->attribute_file));
        //echo '<pre>';print_r($oldAttributes); print_r($newAttributes); 
        $deleteAttributes = array_diff($oldAttributes, $newAttributes);
        $addAttributes = array_diff($newAttributes, $oldAttributes);
        ProductAttributeValue::whereIn('attribute_id', $deleteAttributes)->where('product_id', '=', $id)->delete();
        //print_r($deleteAttributes); print_r($addAttributes); exit;
        foreach ($request->attribute_text as $key => $val) {
            if (in_array($key, $addAttributes))
                $productAttributeValue = new ProductAttributeValue();
            else
                $productAttributeValue = ProductAttributeValue::where('attribute_id', '=', $key)->where('product_id', '=', $id)->first();
            $productAttributeValue->product_id = $productId;
            $productAttributeValue->attribute_id = $key;
            $productAttributeValue->value = $val;
            $productAttributeValue->save();
        }

//        $newAttributes=array_keys($request->attribute_textarea);
//        $oldAttributes=  ProductAttributeValue::join('attributes','product_attribute_values.attribute_id','=','attributes.id')->select('attributes.*')->join('attribute_types','attributes.attribute_type_id','=','attribute_types.id')->where('type','=','textarea')->where('product_attribute_values.product_id','=',$id)->lists('id')->toArray();
//        $deleteAttributes=array_diff($oldAttributes,$newAttributes);
//        $addAttributes=array_diff($newAttributes,$oldAttributes);
//        ProductAttributeValue::whereIn('attribute_id',$deleteAttributes)->where('product_id','=',$id)->delete();

        foreach ($request->attribute_textarea as $key => $val) {
            if (in_array($key, $addAttributes))
                $productAttributeValue = new ProductAttributeValue();
            else
                $productAttributeValue = ProductAttributeValue::where('attribute_id', '=', $key)->where('product_id', '=', $id)->first();
            $productAttributeValue->product_id = $productId;
            $productAttributeValue->attribute_id = $key;
            $productAttributeValue->value = $val;
            $productAttributeValue->save();
        }

        foreach ($request->attribute_selectbox as $key => $val) {
            if (in_array($key, $addAttributes))
                $productAttributeValue = new ProductAttributeValue();
            else
                $productAttributeValue = ProductAttributeValue::where('attribute_id', '=', $key)->where('product_id', '=', $id)->first();
            $productAttributeValue->product_id = $productId;
            $productAttributeValue->attribute_id = $key;
            $productAttributeValue->attribute_value_id = $val;
            $productAttributeValue->save();
        }

        foreach ($request->attribute_multiselect as $key => $valArray){
            $oldOptionValues=  ProductAttributeValue::where('product_id','=',$id)->where('attribute_id','=',$key)->lists('attribute_value_id')->toArray();
            $deleteAttributeValues = array_diff($oldOptionValues, $valArray);
            $addAttributeValues = array_diff($valArray, $oldOptionValues);
            //echo '<pre>';  print_r($deleteAttributeValues); print_r($addAttributeValues); exit;
            ProductAttributeValue::whereIn('attribute_value_id',$deleteAttributeValues)->where('attribute_id','=',$key)->where('product_id', '=', $id)->delete();
            foreach($addAttributeValues as $val){
                $productAttributeValue=new ProductAttributeValue();
                $productAttributeValue->product_id=$productId;
                $productAttributeValue->attribute_id=$key;
                $productAttributeValue->attribute_value_id=$val;
                $productAttributeValue->save();
            }
        }

        foreach ($request->attribute_colorpicker as $key => $valArray) {
            $oldOptionValues=  ProductAttributeValue::where('product_id','=',$id)->where('attribute_id','=',$key)->lists('value')->toArray();
       
            $deleteAttributeValues = array_diff($oldOptionValues, $valArray);
            $addAttributeValues = array_diff($valArray, $oldOptionValues);
            //echo '<pre>'; print_r($oldOptionValues);print_r($valArray); print_r($deleteAttributeValues); print_r($addAttributeValues); exit;
            ProductAttributeValue::whereIn('value',$deleteAttributeValues)->where('attribute_id','=',$key)->where('product_id', '=', $id)->delete();
            foreach($valArray as $k=>$val){
                if (in_array($k, $addAttributeValues))
                    $productAttributeValue = new ProductAttributeValue();
                else
                    $productAttributeValue = ProductAttributeValue::where('attribute_id', '=', $key)->where('product_id', '=', $id)->where('value','=',$val)->first();
                $productAttributeValue->product_id=$productId;
                $productAttributeValue->attribute_id=$key;
                $productAttributeValue->value=$val;
                $productAttributeValue->caption=$request->label_attribute_colorpicker[$key][$k];
                $productAttributeValue->save();
            }
        }


        foreach ($request->file('attribute_file') as $key => $file) {
            if ($file) {
                $fileTran = new FileTransfer();
                //$file = $request->file('logo_image');
                $filename = GeneralFunctions::cleanFileName($file->getClientOriginalName());
                $destination_path = config('constants.aws_attribute_file');
                $fileTran->uploadFile($file, $destination_path, $filename);

                if (in_array($key, $addAttributes))
                    $productAttributeValue = new ProductAttributeValue();
                else {
                    $productAttributeValue = ProductAttributeValue::where('attribute_id', '=', $key)->where('product_id', '=', $id)->first();
                    if (trim($productAttributeValue->value)) {
                        $fileTran->deleteFile(config('constants.aws_attribute_file'), $productAttributeValue->value);
                    }
                }
                $productAttributeValue->product_id = $productId;
                $productAttributeValue->attribute_id = $key;
                $productAttributeValue->value = $filename;
                $productAttributeValue->caption = $request->label_attribute_file[$key];
                $productAttributeValue->save();
            }
        }
        
        foreach ($request->label_attribute_file as $key => $val) {
            if(!$request->file('attribute_file')[$key]){
                $productAttributeValue = ProductAttributeValue::where('attribute_id', '=', $key)->where('product_id', '=', $id)->first();
                if(trim($productAttributeValue->value)){
                    $productAttributeValue->caption = $val;
                    $productAttributeValue->save();
                }
               
            }    
        }
        
        Session::flash('message', 'Product model added successfully.');
        return Redirect::to('products/' . $request->model_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {

        $model = BrandModel::find($id);

        $products = Product::where('model_id', '=', $id)->orderBy('created_at');

        if (isset($_GET['keyword'])) {
            $queryed = $_GET['keyword'];
            if (trim($queryed)) {
                $products->where('name', 'like', '%' . $queryed . '%');
            }
        }
        $products = $products->paginate(config('constants.recordperpage'));
        return view('product.model_products', compact('products', 'model'));
    }

    public function destroy(Request $request) {
        //dd($request);
        BrandModel::whereIn('id', $request->checkItem)->delete();
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
                        </td><td>' . $photo->title . '</td>
                <input type="hidden" id="' . $photo->id . '" name="deleteImagel">
                <td class="center"><button class="btn btn-mini btn-danger" id="deleteImage" name="' . $photo->id . '" onclick="$(this).MessageBox(' . $photo->id . ')" type="button">Dump</button>
                    <button class="btn btn-mini btn-edit" id="deleteImage" name="image' . $photo->id . '" onclick="editModelImage(' . $photo->photo_id . ')" type="button">Edit</button>
                    <img style="width:20%; display:block; margin-left:15px;display:none;" alt="loader" src="' . asset('images/photon/preloader/76.gif') . '"></td>
               ';
        $photo->save();
        return $return;
        //print_r($request->all());
    }

    public function deleteImage(Request $request) {
        $image = ModelImage::find($request->photoId);
        $fileTran = new FileTransfer();
        $fileTran->deleteFile(config('constants.aws_model_image'), $image->image);
        $image->forceDelete();
    }

}
