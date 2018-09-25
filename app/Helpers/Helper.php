<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use \App\Attribute;
use \App\AttributeValue;
//use \App\AttributeValue;
class Helper
{
 public static function rscUrl($string) {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

public static function is_url_exist($url){
            $ch = curl_init($url);    
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if($code == 200){
               $status = true;
            }else{
              $status = false;
            }
            curl_close($ch);
           return $status;
        }
 public static function cleanFileName($fileName)
    {
     $maxLen=100;
     $arr=explode('.',$fileName);
     $extension=array_pop($arr);
     $fName=implode('_',$arr);
     $fName=preg_replace('{([^a-zA-Z0-9_])+}', '_',$fName);
     $fName=preg_replace('/__+/', '_', $fName);
     $fName=substr($fName,0,$maxLen);
     $fName=$fName.'.'.$extension;
     return $fName;
    }
    
  public static function getAttributeHtml($attributeId,$productId=0){
     //return 'test';
     $attribute= Attribute::join('attribute_types','attributes.attribute_type_id','=','attribute_types.id')
             ->select('attributes.*','attribute_types.type')
             ->where('attributes.id','=',$attributeId)
             ->first();
     //dd($attribute);
     //return $attribute->name;
     $attributeOldValues=array();
     if($productId){        
         $attributeOldValues=Attribute::join('product_attribute_values','attributes.id','=','product_attribute_values.attribute_id')
                 ->select('product_attribute_values.*')
                 ->where('product_attribute_values.product_id','=',$productId)
                 ->whereNull('product_attribute_values.deleted_at')
                 ->where('product_attribute_values.attribute_id','=',$attributeId)
                 ->get();
     }
     $data='<div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">'.$attribute->name.'</label>
            </div>
            <div class="span9">
                <div class="controls">';
     
     switch ($attribute->type):
        case "text":
            $val='';
            if(count($attributeOldValues)>0){
            if(trim($attributeOldValues[0]->value))
                $val=$attributeOldValues[0]->value;
            }
            //dd($attributeValues[0]->name);
            $data.='<input maxlength="255" type="text" class="attribute_text" id="attribute_'.$attribute->id.'" name="attribute_'.$attribute->type.'['.$attribute->id.']" value="'.$val.'"/>';
             break;
        case "textarea":
            $val='';
            if(count($attributeOldValues)>0){
            if(trim($attributeOldValues[0]->value))
                $val=$attributeOldValues[0]->value;
            }
             $data.='<textarea maxlength="255" class="attribute_textarea" id="attribute_'.$attribute->id.'" name="attribute_'.$attribute->type.'['.$attribute->id.']">'.$val.'</textarea>';
             break; 
        case "selectbox":
            $val='';
            if(count($attributeOldValues)>0){
            if(trim($attributeOldValues[0]->attribute_value_id))
                $val=$attributeOldValues[0]->attribute_value_id;
            }            
            $attributeValues=AttributeValue::where('attribute_id','=',$attribute->id)->get();
            $data.='<select class="attribute_select" name="attribute_'.$attribute->type.'['.$attribute->id.']" id="attribute_'.$attribute->id.'"><option value="">Select '.$attribute->name.'</option>';
             foreach($attributeValues as $attributeValue):
                 $selected='';
                 if($val==$attributeValue->id)
                     $selected='selected="selected"';
                 $data.='<option value="'.$attributeValue->id.'" '.$selected.'>'.$attributeValue->value.'</option>';
             endforeach;       
             $data.='</select>';
             break;
        case "multiselect":
            $val=array();
            foreach($attributeOldValues as $attributeOldValue){
                $val[]=$attributeOldValue->attribute_value_id;
            }
            $attributeValues=AttributeValue::where('attribute_id','=',$attribute->id)->get();
            foreach($attributeValues as $attributeValue):
             $checked='';
            if(in_array($attributeValue->id, $val))
                    $checked='checked="checked"';
             $data.='<div style="float:left;"><input type="checkbox" id="attribute_'.$attribute->id.'_'.$attributeValue->id.'" name="attribute_'.$attribute->type.'['.$attribute->id.'][]" class="uniformCheckbox3" value="'.$attributeValue->id.'" '.$checked.'>&nbsp;&nbsp;&nbsp;<label class="control-label" style="padding:0;" for="attribute_'.$attribute->id.'_'.$attributeValue->id.'">'.$attributeValue->value.'</label>&nbsp;&nbsp;</div>';
            endforeach;  
             break;
        case "file":
            $caption='';
            $file='';
            if(count($attributeOldValues)>0){
            if(trim($attributeOldValues[0]->value))
                $caption=$attributeOldValues[0]->caption;
                $file='<image src="'.config('constants.awsbaseurl').config('constants.aws_attribute_file').$attributeOldValues[0]->value.'" width="50" hight="50"/>';
            }     
             $data.='<input type="file" id="attribute_'.$attribute->id.'" name="attribute_'.$attribute->type.'['.$attribute->id.']"/>'
                . $file. ' Label: <input  type="text" class="attribute_file_label span5" id="label_attribute_'.$attribute->id.'" name="label_attribute_'.$attribute->type.'['.$attribute->id.']"  value="'.$caption.'"/>';
             break;
        case "colorpicker": 
            if(count($attributeOldValues)>0){
                foreach($attributeOldValues as $attributeOldValue){
                $data.='<div class="colorpicker_div"><div class="attribute_colorpicker_div span5 input-group"><input type="text"  class="span10 attribute_colorpicker"  name="attribute_'.$attribute->type.'['.$attribute->id.'][]"  value="'.$attributeOldValue->value.'"/><span class="input-group-addon"><i></i></span></div>'
                . ' Label: <input  type="text" class="attribute_colorpicker_label span4"  name="label_attribute_'.$attribute->type.'['.$attribute->id.'][]" value="'.$attributeOldValue->caption.'"/><button class="btn btn-default del_colorpicker" type="button">Remove</button></div>';
                }
            }else{
            $val='#e30f0f';
            $data.='<div class="colorpicker_div"><div class="attribute_colorpicker_div span5 input-group"><input type="text"  class="span10 attribute_colorpicker"  name="attribute_'.$attribute->type.'['.$attribute->id.'][]"  value="'.$val.'"/><span class="input-group-addon"><i></i></span></div>'
                . ' Label: <input type="text" class="attribute_colorpicker_label span4"  name="label_attribute_'.$attribute->type.'['.$attribute->id.'][]" value=""/><button class="btn btn-default del_colorpicker" type="button">Remove</button></div>';
            }
            $data.='<div class="controls span-inset"><button class="btn btn-default " id="add_colorpicker" value="S" name="add_more" type="button">Add More</button></div>';
            break;
     endswitch;
    
    $data.='</div>
             </div>
            </div>';
     return $data;
 }   
    
}

