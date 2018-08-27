<?php
namespace App\Http\Controllers;
use Redirect;
use App\Category;
use Illuminate\Http\Request;
use DB;
use App\Right;
use Auth;
use App\Brand;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\GeneralFunctions;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class BrandController extends Controller {

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
       $brands=Brand::orderBy('created_at');
       if (isset($_GET['keyword'])) {
           $queryed = $_GET['keyword'];
           if(trim($queryed)){
                $brands->where('name','like','%'.$queryed.'%');
           }
       }
       $brands=$brands->paginate(config('constants.recordperpage'));
       return view('brand.brands',compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create() {
      return view('brand.create');
      echo 'create brand page';
    }

    public function store(Request $request) {
      
          $validation = $this->validate($request,[
            'brand_name' => 'required',
            'logo_image' => 'mimes:jpeg,bmp,png,jpg,gif',
        ]);
        $brand = new Brand();
        $brand->name = trim($request->brand_name);
        if ($request->file('logo_image')) { 
                $fileTran = new FileTransfer();
                $file = $request->file('logo_image');
                $filename=GeneralFunctions::cleanFileName($request->file('logo_image')->getClientOriginalName());
                $destination_path = config('constants.aws_brand_logo');
                $fileTran->uploadFile($file, $destination_path, $filename);
                $brand->logo=$filename;
         }
        $brand->save();
   
        Session::flash('message', 'Brand added successfully.');
        return Redirect::to('brands');
        
    }

  
    public function edit($id){       
        $rightId = 10;
        $brand=  Brand::find($id);       
        return view('brand.edit',compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id) {
          
        $validation = $this->validate($request,[
            'brand_name' => 'required',
            'logo_image' => 'mimes:jpeg,bmp,png,jpg,gif',
        ]);
        $brand = Brand::find($id);
        
        $brand->name = trim($request->brand_name);
       
        if ($request->file('logo_image')) { 
                $fileTran = new FileTransfer();
                $file = $request->file('logo_image');
                //$filename = str_random(6) . '_' . $request->file('logo_image')->getClientOriginalName();
                $filename=GeneralFunctions::cleanFileName($request->file('logo_image')->getClientOriginalName());
                $destination_path = config('constants.aws_brand_logo');
                $fileTran->uploadFile($file, $destination_path, $filename);
                if (trim($brand->logo)){    
                    $fileTran->deleteFile($destination_path,$brand->logo);
                } 
                $brand->logo=$filename;
         }
        
        $brand->save();
   
        Session::flash('message', 'Brand updated successfully.');
        return Redirect::to('brands');
         
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
        //dd($request);
        Brand::whereIn('id',$request->checkItem)->delete();
        Session::flash('message', 'Brand deleted successfully.');
        return Redirect::to('brands/');
    }
}
