@extends('layouts/master')
@section('title', 'Add Attribute Group - BWCMS')
@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Add Attribute Group</small></h1>
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
                <a href="/attribute-groups">Attribute Group</a>

            </li>
            <li class="current">
                <a href="javascript:;">Add Attribute Group</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Add Attribute Group</small></h2>
    </header>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/attribute-groups" >
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Attribute Group List</button>
        </a>
    </div>
    {!! Form::open(array('url'=>'attribute-groups','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
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
        <div class="form-legend" id="feed-detail">Attribute Group Details</div>

           <div id="Simple_Select_Box" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="simpleSelectBox">Product Type</label>
            </div>
            <div class="span9">
                <div class="controls">
                    {!! Form::select('product_type',['' => 'Please Select']+$productTypes,old('product_type'),
                    ['class' => 'form-control formattedelement','id' =>'product_type','class'=>'selectBox formattedelement']) !!}

                </div>
            </div>
            <script>
                $().ready(function(){
                $(".selectBox").select2({
                dropdownCssClass: 'noSearch'
                });
                });             </script>
        </div>
        <!--Text Area - No Resize begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" id="group_name" name="group_name" value="{{ old('group_name')}}"/>
                </div>
            </div>
        </div>
        <!--Text Area - No Resize end-->    
    </div>  

    <div class="container-fluid">


        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <button type="submit" name="add" value="N" id="add"class="btn btn-warning">Save</button>
            </div>
        </div>
    </div>
    <script>
            $("#fileupload").validate({
                    errorElement: "span",
                    rules: {
                    "req": {
                    required: true
                    },
                        "product_type": {
                            required: true,
                            },
                            "group_name": {
                            required: true
                            }
                    }
            });
    </script>
</div>
</div>
@stop
