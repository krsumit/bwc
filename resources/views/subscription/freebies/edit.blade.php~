@extends('layouts/master')

@section('title', 'Edit Subscription Freebies - BWCMS')


@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small> Edit subscription Freebies </small></h1>
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
                <a href="#">Subscription Freebies </a>
                <ul class="breadcrumb-sub-nav">
                    <li>
                        <a href="#">Edit Subscription Freebies</a>
                    </li>
                    
                </ul>
            </li>
           
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Edit Subscription Freebies</small></h2>
        <h3><small>{{ $userTup->name or '' }}</small></h3>
    </header>
    {!! Form::open(array('url'=>'subscription/freebies/update','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
    <input type="hidden" name="freebies_id" value="{{$freebies->id}}"/>
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
        <div class="form-legend" id="Article-Details">Freebies Detail</div>
        <!--Text Area - No Resize begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="no_of_magazine" for="No of magazine">Description</label>
            </div>
            <div class="span9">
                <div class="controls">

                    <textarea name="description">{{$freebies->description}}</textarea>
                </div>
                <span class="error">{{$errors->first('description')}}</span>
            </div>
        </div>
       
        

    </div><!-- end container1 -->
   
    
    <div class="container-fluid">

       
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Status </label>
            </div>
            <div class="span9">
                <div class="controls">
                		
                                       Inactive<input type="radio" name="status" value="0" style="margin:15px" @if($freebies->status=='0') checked @endif />
                                       Active <input type="radio" name="status" style="margin:15px"  value="1" @if($freebies->status=='1') checked @endif/>
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
                'description':{
                          required: true,
                 },
            }
       });
    });
</script> 

@stop
