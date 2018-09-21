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
use App\AttributeGroup;
use App\Attribute;
use App\AttributeGroupAttribute;
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
      $rightId=116;  
      $channels = $this->rightObj->getAllowedChannels($rightId);  
      return view('producttype.create',compact('channels'));
      
    }

    public function store(Request $request) {
        
          $validation = $this->validate($request,[
            'product_type_name' => 'required',
        ]);
        $prodType = new ProductType();
        $prodType->name = trim($request->product_type_name);
        $prodType->channel_id=$request->channel_id;
        $prodType->save();
        Session::flash('message', 'Product Type added successfully.');
        return Redirect::to('product-types');
        
    }

  
    public function edit($id){       
        $rightId=116;  
        $channels = $this->rightObj->getAllowedChannels($rightId); 
        //dd($channels);
        $productType=  ProductType::find($id);       
        return view('producttype.edit',compact('productType','channels'));
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
        $productType->channel_id=$request->channel_id;      
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
       $productType=  ProductType::find($id);
       $attributeGroups=AttributeGroup::where('product_type_id','=',$id)->get();
       $groupAttributes=  AttributeGroup::leftJoin('attribute_group_attributes','attribute_groups.id','=','attribute_group_attributes.attribute_group_id')->select(DB::raw('attribute_groups.id,attribute_groups.name,group_concat(attribute_group_attributes.attribute_id) as group_attributes'))->where('attribute_groups.product_type_id','=',$id)->groupBy('attribute_groups.id')->get();
       //dd($groupAttributes);
       $groupAttributesArray=array();
       $assignedAttributes=array();
       foreach($groupAttributes as $groupAttribute){
           $groupAttributesArray[$groupAttribute->id]=explode(',',$groupAttribute->group_attributes);
           $assignedAttributes=array_merge($assignedAttributes,explode(',',$groupAttribute->group_attributes));
       }
       
       $unassingedAttributes= Attribute::whereNotIn('id',$assignedAttributes)->get();
       $assignedAttributesDetail=  Attribute::whereIn('id',$assignedAttributes)->select('name','id')->get();
       $assignedAttributesDetail=$assignedAttributesDetail->pluck('name','id');
       //print_r($assignedAttributesDetail);exit;
      return view('producttype.manage_attribute',compact('productType','unassingedAttributes','attributeGroups','groupAttributesArray','assignedAttributesDetail'));
    }
  
    public function storeAttribute(Request $request){
       //dd($request); //AttributeGroupAttribute
        $id=$request->product_type_id;
        $attributeGroups=AttributeGroup::where('product_type_id','=',$id)->get();
        $groupAttributes=  AttributeGroup::leftJoin('attribute_group_attributes','attribute_groups.id','=','attribute_group_attributes.attribute_group_id')->select(DB::raw('attribute_groups.id,attribute_groups.name,group_concat(attribute_group_attributes.attribute_id) as group_attributes'))->where('attribute_groups.product_type_id','=',$id)->groupBy('attribute_groups.id')->get();
        //dd($request->attr_group);
        $allAttributes=array();
        $allGroup=array();
        foreach($request->attr_group as $key => $grp){
            $allAttributes=array_merge($allAttributes,$grp);
            $allGroup[]=$key;
        }
        
        foreach($groupAttributes as $groupAttribute){
            $existingGroupAttr=array_filter(explode(',',$groupAttribute->group_attributes));
            $newGroupAttr=$request->attr_group[$groupAttribute->id];
            // Delete old attribute from Group
            AttributeGroupAttribute::where('attribute_group_id','=',$groupAttribute->id)->whereNotIn('attribute_id',$newGroupAttr)->whereNotIn('attribute_id',$allAttributes)->forceDelete();
            // Delete old attribute from product
            

            //Add new attribute in group or change group
            $newAttributes=array_diff($newGroupAttr,$existingGroupAttr);
            foreach($newAttributes as $newAttribute){
                
                $checkAtt=  AttributeGroupAttribute::where('attribute_id',$newAttribute)->whereIn('attribute_group_id',$allGroup)->first();
                if($checkAtt){ // If attribute already exist change group
                    $groupAtt=AttributeGroupAttribute::find($checkAtt->id);
                    $groupAtt->attribute_group_id=$groupAttribute->id;
                    $groupAtt->update();
                }else{ // If new add attribute in group
                    $groupAtt=new AttributeGroupAttribute();
                    $groupAtt->attribute_group_id=$groupAttribute->id;
                    $groupAtt->attribute_id=$newAttribute;
                    $groupAtt->save(); 
                }
                   
            }
        }
         return Redirect::to('product-types');
    }
    
    public function destroy(Request $request){
        //dd($request);
        ProductType::whereIn('id',$request->checkItem)->delete();
        Session::flash('message', 'Product Type(s) deleted successfully.');
        return Redirect::to('product-types');
    }
}
