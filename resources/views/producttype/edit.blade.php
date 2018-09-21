@extends('layouts/master')
@section('title', 'Edit Product - BWCMS')
@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit Product Type</small></h1>
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
                <a href="/product-types">Product Types</a>

            </li>
            <li class="current">
                <a href="javascript:;">Edit Product Type</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Edit Product Type</small></h2>
    </header>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/product-types" >
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Product Types List</button>
        </a>
    </div>
    {!! Form::open(array('url'=>'product-types/'.$productType->id,'class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
    {!! method_field('PUT') !!}
    <input type="hidden" name="product_type_id" id="product_type_id" value="{{$productType->id}}"/>

    <div class="container-fluid" id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')) && (!$errors->any()))style="display: none" @endif >
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
        <div class="form-legend" id="feed-detail">Product Type Details</div>
         <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="channel_sel">Channel</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="channel_id" id="channel_id" class="required channel_sel formattedelement">
                        @foreach($channels as $channel)
                        <option value="{{ $channel->channel_id }}" @if($channel->channel_id==$productType->channel_id) selected="selected" @endif>{{ $channel->channel }}</option>
                        @endforeach
                    </select>
                    <span for="channel_sel1" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                </div>
            </div>
            <script>
                        $().ready(function(){
                            $("#channel_id").select2();
                        });
                                            
            </script>
        </div>
        <!--Text Area - No Resize begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" id="product_type_name" name="product_type_name" required="required" value="{{ old('product_type_name')?old('product_type_name'):$productType->name }}"/>

                </div>
            </div>
        </div>
        <!--Text Area - No Resize end-->    

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
