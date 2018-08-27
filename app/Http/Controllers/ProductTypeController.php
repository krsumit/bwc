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
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\GeneralFunctions;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class ProductTypeController extends Controller {

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
       $productTypes=ProductType::orderBy('created_at');
       if (isset($_GET['keyword'])) {
           $queryed = $_GET['keyword'];
           if(trim($queryed)){
                $productTypes->where('name','like','%'.$queryed.'%');
           }
       }
       $productTypes=$productTypes->paginate(config('constants.recordperpage'));
       return view('producttype.product_types',compact('productTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create() {
      return view('producttype.create');
      
    }

    public function store(Request $request) {
        
          $validation = $this->validate($request,[
            'product_type_name' => 'required',
        ]);
        $prodType = new ProductType();
        $prodType->name = trim($request->product_type_name);
        $prodType->save();
        Session::flash('message', 'Product Type added successfully.');
        return Redirect::to('product-types');
        
    }

  
    public function edit($id){       
        $rightId = 10;
        $productType=  ProductType::find($id);       
        return view('producttype.edit',compact('productType'));
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
            'product_type_name' => 'required',
        ]);
        $productType = ProductType::find($id);
        
        $productType->name = trim($request->product_type_name);
       
      
        $productType->save();
   
        Session::flash('message', 'Product Type updated successfully.');
        return Redirect::to('product-types');
         
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
        ProductType::whereIn('id',$request->checkItem)->delete();
        Session::flash('message', 'Product Type(s) deleted successfully.');
        return Redirect::to('product-types/');
    }
}
