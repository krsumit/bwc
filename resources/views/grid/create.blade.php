@extends('layouts/master')
@section('title', 'Add Grid - BWCMS')
@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">Grid
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
                <a href="/grids">Grid</a>

            </li>
            <li class="current">
                <a href="javascript:;">Grid</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Add Grid</small></h2>
    </header>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/grids" >
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Grids List</button>
        </a>
    </div>
    {!! Form::open(array('url'=>'grids','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
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
        <div class="form-legend" id="feed-detail">Grid Details</div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="channel_sel">Channel</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="channel_id" id="channel_id" class="required channel_sel formattedelement">
                        @foreach($channels as $channel)
                        <option value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
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
                    <input type="text" id="grid_name" name="grid_name" required="required" value="{{ old('grid_name')}}"/>
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
                        <option value="{{ $girdType }}">{{ ucfirst($girdType) }}</option>
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
                        <input type="checkbox" id="is_home_page" name="is_home_page" class="uniformCheckbox" value="1">
                        <a href="javascript:void(0);" for="is_home_page">Show on Home Page.</a>
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
                <button type="submit" name="add" value="N" id="add"class="btn btn-warning">Save</button>
            </div>
        </div>
    </div>

</div>
</div>
@stop
