@extends('layouts/master')
@section('title', 'Edit Grid - BWCMS')
@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit Grid</small></h1>
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
                <a href="/girds">Grid</a>

            </li>
            <li class="current">
                <a href="javascript:;">Edit Grid</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Edit Grid</small></h2>
    </header>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/grids?channel={{$grid->channel_id}}" >
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Girds List</button>
        </a>
    </div>
    {!! Form::open(array('url'=>'grids/'.$grid->id,'class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
    {!! method_field('PUT') !!}
    <input type="hidden" name="brand_id" id="brand_id" value="{{$grid->id}}"/>

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
        <div class="form-legend" id="feed-detail">Grid Details</div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="channel_sel">Channel</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="channel_id" id="channel_id" class="required channel_sel formattedelement">
                        @foreach($channels as $channel)
                        <option @if($channel->channel_id==$grid->channel_id) selected="selected" @endif value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
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
                    <input type="text" id="grid_name" name="grid_name" required="required" value="{{ old('grid_name')?old('grid_name'):$grid->name }}"/>
            </div>
            </div>
        </div>
        <!--Text Area - No Resize end-->    
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="channel_sel">Grid Type</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="gride_type" id="gride_type" class="required channel_sel formattedelement">
                        @foreach($girdTypes as $girdType)
                        <option @if($girdType==$grid->type) selected="selected" @endif value="{{ $girdType }}">{{ ucfirst($girdType) }}</option>
                        @endforeach
                    </select>
                    <span for="channel_sel1" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                </div>
            </div>
            <script>
                        $().ready(function(){
                            $("#gride_type").select2();
                        });
                                            
            </script>
        </div>
        
        <div  class="control-group row-fluid">
            <div class="span12 span-inset">
                    <label class="checkbox" >
                        <input type="checkbox" name="is_home_page" class="uniformCheckbox" value="1" @if($grid->is_home_page=='1') checked="checked" @endif >
                        <a href="javascript:void(0);">Show on Home Page.</a>
                    </label>
            </div>
              <script>
                    $().ready(function(){
                        $(".uniformCheckbox").uniform();
                    });
                </script>
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
