<?php //echo '<pre>';print_r($rights); exit;  
 ?>
@if(isset($channelDetial->channel))
<div class="container-fluid chpermission shekhar" id="permissionch_{{$channelDetial->channel_id}}">
    <div id="rights" class="form-legend">{{$channelDetial->channel}}</div>
@endif    
    <?php $flag = 0; ?>    
    @foreach($rights as $right)
    @if($right->parent_id==0 && $flag==0)
    <?php $flag = 1; ?>  
    <div class="control-group row-fluid">
        <div class="span3">
            <label class="checkbox">
                <b>{{$right->label}}</b>
            </label>
        </div>
        <div class="span9">
            <ul style="list-style:none;">
       
    @elseif($right->parent_id==0)
            </ul>
        </div>
    </div>
    <div class="control-group row-fluid">
        <div class="span3">
            <label class="checkbox">
                <b>{{$right->label}}</b>
            </label>
        </div>
        <div class="span9">
            <ul style="list-style:none;">

    @else
        <li>							
                    <label class="checkbox">
                        @if(isset($channelDetial->channel))
                         <input type="checkbox" class="uniformCheckbox" name="rightArr[{{$channelDetial->channel_id}}][]" @if(in_array($right->rights_id,$selectedRights)) checked="checked" @endif value="{{$right->rights_id}}">
<!--                        <div class="checker" id="uniform-undefined"><span class="checked"><input type="checkbox" value="20" name="rightArr[]" class="uniformCheckbox" checked="&quot;checked&quot;" style="opacity: 0;"></span></div>-->
                       @else
                         <input type="checkbox" class="uniformCheckbox" name="rightArr[]" @if(in_array($right->rights_id,$selectedRights)) checked="checked" @endif value="{{$right->rights_id}}">
                       @endif
                       {{$right->label}}({{$right->rights_id}})
                    </label>
         </li>
    @endif
    @endforeach
    </ul>
        </div>
    </div>
 @if(isset($channelDetial->channel))
</div>
@endif
