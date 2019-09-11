@extends('layouts/master')

@section('title', 'Change Password - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Change Password</small></h1>
        </div>
        <div class="panel-search container-fluid">
             
                <script>
                    $().ready(function () {
                        var searchTags = [
                            "Dashboard",
                            "Form Elements",
                            "Graphs and Statistics",
                            "Typography",
                            "Grid",
                            "Tables",
                            "Maps",
                            "Sidebar Widgets",
                            "Error Pages",
                            "Help",
                            "Input Fields",
                            "Masked Input Fields",
                            "Autotabs",
                            "Text Areas",
                            "Select Menus",
                            "Other Form Elements",
                            "Form Validation",
                            "UI Elements",
                            "Graphs",
                            "Statistical Elements",
                            "400 Bad Request",
                            "401 Unauthorized",
                            "403 Forbidden",
                            "404 Page Not Found",
                            "500 Internal Server Error",
                            "503 Service Unavailable"
                        ];
                        $("#panelSearch").autocomplete({
                            source: searchTags
                        });
                    });

                
                </script>
        </div>

       

       
        <br><br>
        <div class="panel-header">
    <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
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
            <li class="current">
                <a href="javascript:;">Change Password</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Change Password</small></h2>

    </header>
    {!! Form::open(array('url'=>'change/password','class'=> 'form-horizontal','id'=>'form1')) !!}
    {!! csrf_field() !!}

    <div class="container-fluid" id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')) && (count($errors->all())==0) )style="display: none" @endif >

         <div class="form-legend" id="Notifications">Notifications</div>

        <!--Notifications begin-->
        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                @if (count($errors) > 0)
                <div class="alert alert-error alert-block">
                    <i class="icon-alert icon-alert-info"></i>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>This is Error Notification</strong>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                    <!--<span>Please select a valid search criteria.</span>-->
                </div>
                @endif				
            </div>
        </div>
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
        <div class="form-legend" id="new">Change Password</div>

        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Current Password</label>
            </div>
            <div class="span9">
                <div class="controls">
                     <input  name="old_password" type="password" value="{{old('old_password')}}">
                </div>
                <span class="alert-danger alert-error" role="alert">
                    <strong>{{ $errors->first('old_password') }}</strong>
                </span>
            </div>
        </div>

        
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">New Password </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input  name="password" type="password" value="{{old('password')}}">
                </div>
                <span class="alert-danger alert-error" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Confirm Password</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input  name="password_confirmation" type="password">
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button class="btn btn-warning pull-right" type="submit" name="add" style="display:block;">Change</button>
            </div>
        </div>

    </div>



    {!! Form::close() !!}
</div>

@stop