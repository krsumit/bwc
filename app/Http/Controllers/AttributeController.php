<?php
namespace App\Http\Controllers;
use Redirect;
use App\Category;
use Illuminate\Http\Request;
use DB;
use App\Right;
use Auth;
use App\Attribute;
use App\AttributeType;
use App\AttributeGroup;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\GeneralFunctions;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class AttributeController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $rightObj;

    public function __construct() {
        $this->rightObj = new Right();
    }

    public function index(){        
       $attributes=  Attribute::join('attribute_types','attributes.attribute_type_id','=','attribute_types.id')
               ->select('attributes.*','attribute_types.name as type_name','attribute_types.type')
               ->orderBy('created_at');
       if (isset($_GET['keyword'])) {
           $queryed = $_GET['keyword'];
           if(trim($queryed)){
                $attributes->where('attributes.name','like','%'.$queryed.'%')->orWhere('attribute_types.name','like','%'.$queryed.'%');
           }
       }
       $attributes=$attributes->paginate(config('constants.recordperpage'));
       return view('attribute.attributes',compact('attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(){
      $atributeTypes= AttributeType::orderBy('name','desc')->lists('name','id')->toArray();  
      return view('attribute.create',compact('atributeTypes'));
    }

    public function store(Request $request) {
      
        $validation = $this->validate($request,[
            'attribute_type' => 'required',  
            'attribute_name' => 'required',
        ]);
          
        $attribute = new Attribute();
        $attribute->name = trim($request->attribute_name);
        $attribute->attribute_type_id = $request->attribute_type;
        $attribute->save();
   
        Session::flash('message', 'Attribute added successfully.');
        return Redirect::to('attributes');
        
    }

  
    public function edit($id){       
        $rightId = 10;
        $atributeTypes= AttributeType::orderBy('name','desc')->lists('name','id')->toArray();  
        $attribute= Attribute::find($id); // Brand::find($id);  
        return view('attribute.edit',compact('attribute','atributeTypes'));
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
            'attribute_type' => 'required',  
            'attribute_name' => 'required',
        ]);
               
        $attribute = Attribute::find($id);
        $attribute->name = trim($request->attribute_name);
        $attribute->attribute_type_id = $request->attribute_type;
        $attribute->save();
   
        Session::flash('message', 'Attribute updated successfully.');
        return Redirect::to('attributes');
               
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
        Attribute::whereIn('id',$request->checkItem)->delete();
        Session::flash('message', 'Attribute  deleted successfully.');
        return Redirect::to('attributes/');
    }
}
