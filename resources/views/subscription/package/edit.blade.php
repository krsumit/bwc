@extends('layouts/master')

@section('title', 'Edit Subscription Packages - BWCMS')


@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small> Edit subscription package </small></h1>
        </div>

        <div class="panel-header">
        <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
        </div>
        <div class="sidebarMenuHolder">
            <div class="JStree">
                <div class="Jstree_shadow_top"></div>
                <div id="jstree"></div>
                <div class="Jstree_shadow_bottom"></div>
            </div>
        </div>
    </div>
    <div class="panel-slider">
        <div class="panel-slider-center">
            <div class="panel-slider-arrow"></div>
        </div>
    </div>
</div>
<div class="main-content">
    <div class="breadcrumb-container">
        <ul class="xbreadcrumbs">
            <li>
                <a href="/dashboard">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li>
                <a href="#">Subscription Package </a>
                <ul class="breadcrumb-sub-nav">
                    <li>
                        <a href="#">Edit Subscription Package</a>
                    </li>
                    
                </ul>
            </li>
           
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Edit Subscription Package</small></h2>
        <h3><small>{{ $userTup->name or '' }}</small></h3>
    </header>
    {!! Form::open(array('url'=>'subscription/packages/update','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
    <input type="hidden" name="packageId" id="packageId" value="{{$packageDetail->id}}"/>
    <div class="container-fluid" id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')))style="display: none" @endif >

         <div class="form-legend" id="Notifications">Notifications</div>

        <!--Notifications begin-->
        <div class="control-group row-fluid" >
            <div class="span12 span-inset">
                @if (Session::has('message'))
                <div class="alert alert-success alert-block" style="">
                    <i class="icon-alert icon-alert-info"></i>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>This is Success Notification</strong>
                    <span>{{ Session::get('message') }}</span>
                </div>
                @endif

                @if (Session::has('error'))
                <div class="alert alert-error alert-block">
                    <i class="icon-alert icon-alert-info"></i>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>This is Error Notification</strong>
                    <span>{{ Session::get('error') }}</span>
                </div>
                @endif
            </div>
        </div>
        <!--Notifications end-->

    </div>


    <div class="container-fluid">
        <div class="form-legend" id="Article-Details">Package Detail

        </div>
        <!--Text Area - No Resize begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Title </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="title" value="{{$packageDetail->name}}"/>
                </div>
            </div>
        </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Duration Type</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="duration_type" id="duration_type" class="formattedelement">
							   <option value="1" @if($packageDetail->duration_type=='1') selected="selected"  @endif>Month</option>
                        <option value="2" @if($packageDetail->duration_type=='2') selected="selected"  @endif >Year</option>
                    </select>
                </div>
            </div>
            <script>
                 $(document).ready(function(){
                        $("#duration_type").select2();
                              
                 });
            </script>
        </div>
        <!--Text Area - No Resize end-->
		  <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Duration</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="duration" value="{{$packageDetail->duration}}"/>
                </div>
            </div>
        </div>
        
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Freebies</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="freebies[]" id="freebies" class="formattedelement" multiple="multiple">
                    			  <option value="">Select Freebies</option>
							   @foreach($freebies as $freebie)
										 <option value="{{$freebie->id}}" @if(in_array($freebie->id,$selectedFreebiesId)) selected="selected" @endif>{{substr($freebie->description,0,50)}}</option>
							   @endforeach
                    </select>
                </div>
            </div>
            <script>
                 $(document).ready(function(){
                        $("#freebies").select2();
                              
                 });
            </script>
        </div>
        

    </div><!-- end container1 -->
   

	

    <div class="container-fluid">
        <div class="form-legend" id="Article-Details">Magazines Price </div>
        <!--Text Area - No Resize begin-->
        @foreach($magazines as $magazine)
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">{{$magazine->magazine}} </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="price[{{$magazine->channel_id}}]" value="{{$prices[$magazine->channel_id]}}"/>
                </div>
            </div>
        </div>
        @endforeach
     </div><!-- end container1 -->
    
    <div class="container-fluid">

       
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Status </label>
            </div>
            <div class="span9">
                <div class="controls">
                		
                                       Inactive<input type="radio" name="status" value="0" style="margin:15px" @if($packageDetail->status==0) checked @endif />
                                       Active <input type="radio" name="status" style="margin:15px"  value="1" @if($packageDetail->status==1) checked @endif />
                </div>
            </div>
        </div>
      
     </div>
 
    <div class="container-fluid">
        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <button type="submit" name="submitstatus" value="P" id="publishSubmit" class="btn btn-success">Save</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
            </div>
        </div>
    </div>
    <!--	end container-->
    {!! Form::close() !!}

</div>
<script>
    $(document).ready(function(){
         $("#fileupload").validate({
            errorElement: "span",
            errorClass: "error",
            rules: {
                'title':"required",
                'duration':{
                          required: true,
                          number: true
                 },
                @foreach($magazines as $magazine)'price[{{$magazine->channel_id	}}]': {
               	required: true,
                  number: true
               },@endforeach    
            }
       });
    });
</script> 

@stop
