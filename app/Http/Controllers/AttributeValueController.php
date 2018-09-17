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
use App\AttributeValue;
use App\Attribute;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\GeneralFunctions;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class AttributeValueController extends Controller {

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
       if(count($request->edit_value)>0){
           $oldValueIds=array_keys($request->edit_value);
           AttributeValue::where('attribute_id','=',$id)->whereNotIn('id',$oldValueIds)->delete();
           
           foreach($request->edit_value as $key=>$val){
                $attVal=AttributeValue::find($key);
                if(trim($val)){
                 $attVal->value=$val;
                 $attVal->attribute_id=$id;
                 $attVal->save();
                }else{
                 $attVal->delete();   
                }
            }
           
       }else{
           AttributeValue::where('attribute_id','=',$id)->delete();
       }
       foreach($request->attribute_value as $val){
           if(trim($val)){
            $attVal=new AttributeValue();
            $attVal->value=$val;
            $attVal->attribute_id=$id;
            $attVal->save();
           }
       }
       
       Session::flash('message', 'Attribute values saved successfully.');
       return Redirect::to('attribute-values/'.$id);
   }
    
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
      $attribute= Attribute::find($id);  
      $attributeValues=  AttributeValue::where('attribute_id',$id)->get();
      return view('attributevalue.attribute_values',compact('attribute','attributeValues'));
    }
  
    
    public function destroy(Request $request){
       
    }
}
