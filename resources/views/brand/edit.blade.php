@extends('layouts/master')
@section('title', 'Edit Brand - BWCMS')
@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit Brand</small></h1>
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
                <a href="/brands">Brand</a>

            </li>
            <li class="current">
                <a href="javascript:;">Edit Brand</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Edit brand</small></h2>
    </header>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/brands" >
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Brands List</button>
        </a>
    </div>
    {!! Form::open(array('url'=>'brands/'.$brand->id,'class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
    {!! method_field('PUT') !!}
    <input type="hidden" name="brand_id" id="brand_id" value="{{$brand->id}}"/>

    <div class="container-fluid" id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')) && (!$errors->any()))style="display: none" @endif >

         <div class="form-legend" id="Notifications">Notifications</div>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

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
        <div class="form-legend" id="feed-detail">Brand Details</div>

        <!--Text Area - No Resize begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" id="brand_name" name="brand_name" required="required" value="{{ old('brand_name')?old('brand_name'):$brand->name }}"/>
<!--                    <textarea  id="brand_name" name="brand_name" required="required" rows="2" class="no-resize">{{ old('brand_name')?old('brand_name'):$brand->name }}</textarea>-->

                </div>
            </div>
        </div>
        <!--Text Area - No Resize end-->    

        <div id="File_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Upload logo (Size:{{config('constants.dimension_brand_logo')}}, File Size<={{config('constants.maxfilesize').' '.config('constants.filesizein')}})
                </label>
            </div>
            <div class="span9">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="input-append">
                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="logo_image"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                         
                      
                        </div>

                    </div>
                @if(trim($brand->logo))
                <div>
                  <img style="width: 70px;height: 70px;margin-left: 5px;" src="{{ config('constants.awsbaseurl').config('constants.aws_brand_logo').$brand->logo}}" alt="user" style="width:12%;" />
                </div>
                 @endif
                </div>

            </div>
        </div>  
    
    <div class="container-fluid">


        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <button type="submit" name="update" value="N" id="update"class="btn btn-warning">Update</button>
            </div>
        </div>
    </div>

    </div>
</div>
@stop
