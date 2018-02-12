@extends('layouts/master')

@section('title', 'Create Subscriber - BWCMS')


@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small> Add Subscriber </small></h1>
        </div>

        <div class="panel-header">
        <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
        </div>
        <script type="text/javascript">
            $(function () {
                $("#jstree").jstree({
                    "json_data": {
                        "data": [
                            {
                                "data": {
                                    "title": "Subscriber Detail",
                                    "attr": {"href": "#subscriber-detail-section"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Address",
                                    "attr": {"href": "#address-section"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Newsletter",
                                    "attr": {"href": "#newsletter-section"}
                                }
                            },
                        ]
                    },
                    "plugins": ["themes", "json_data", "ui"]
                })
                        .bind("click.jstree", function (event) {
                            var node = $(event.target).closest("li");
                            document.location.href = node.find('a').attr("href");
                            return false;
                        })
                        .delegate("a", "click", function (event, data) {
                            event.preventDefault();
                        });
            });</script>
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
                <a href="#">Subscriber </a>
                <ul class="breadcrumb-sub-nav">
                    <li>
                        <a href="#">Add Subscriber</a>
                    </li>

                </ul>
            </li>

        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Add Subscriber</small></h2>
        <h3><small>{{ $userTup->name or '' }}</small></h3>
    </header>
    {!! Form::open(array('url'=>'subscribers','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
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


    <div class="container-fluid" id="subscriber-detail-section">
        <div class="form-legend" id="Article-Details">Subscriber Detail</div>
        <!--Text Area - No Resize begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label"  for="First Name">First Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="first_name" id="first_name1" value="{{old('first_name')}}">
                </div>
                <span class="error">{{$errors->first('first_name')}}</span>
            </div>
        </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label"  for="Last Name">Last Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="last_name" id="last_name" value="{{old('last_name')}}">
                </div>
                <span class="error">{{$errors->first('last_name')}}</span>
            </div>
        </div>

        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label"  for="Email">Email</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="email" id="email" value="{{old('email')}}">
                </div>
                <span class="error">{{$errors->first('email')}}</span>
            </div>
        </div>

        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label"  for="Mobile">Mobile</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="mobile" id="mobile" value="{{old('mobile')}}">
                </div>
                <span class="error">{{$errors->first('mobile')}}</span>
            </div>
        </div>

        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="datepicker">
                    DOB<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Click to choose date."><i class="icon-photon info-circle"></i></a>
                </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="dob" id="datepicker" class="span3" value="{{old('dob')}}" />
                </div>
              <span class="error">{{$errors->first('dob')}}</span>
            </div>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#datepicker").datepicker({
                        dateFormat: "yy-mm-dd",
                        changeYear: true,
                        yearRange: "-100:+0",
                    });
                });
            </script>
        </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Sex </label>
            </div>
            <div class="span9">
                <div class="controls">
                    Male<input type="radio" name="sex" value="1" style="margin:15px" @if(old('sex')==1) checked @endif/>
                    Female <input type="radio" name="sex" style="margin:15px"  value="2" @if(old('sex')==2) checked @endif/>
                    Other <input type="radio" name="sex" style="margin:15px"  value="3" @if(old('sex')==3) checked @endif />
                </div>
                <span class="error">{{$errors->first('sex')}}</span>
            </div>
        </div>


    </div><!-- end container1 -->
    <div class="container-fluid" id="address-section">
        <div class="form-legend" id="Article-Details">Address</div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label"  for="Adress1">Street</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="address1" id="address1" value="{{old('address1')}}"/>
                </div>
                <span class="error">{{$errors->first('address1')}}</span>
            </div>
        </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label"  for="Address2">Landmark</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="address2" id="address2" value="{{old('address2')}}">
                </div>
                <span class="error">{{$errors->first('address2')}}</span>
            </div>
        </div>

        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label"  for="Address2">City</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="city" id="city" value="{{old('city')}}">
                </div>
                <span class="error">{{$errors->first('city')}}</span>
            </div>
        </div>

        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Select Location</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="country" id="selectBoxFilter6">
                        <option  value="">Please Select</option>
                        @foreach($country as $country)
                        <option value="{{ $country->country_id }}" @if($country->country_id==old('country')) selected="selected" @endif>{{ $country->name }}</option>
                        @endforeach	                                        
                    </select>
                </div>
                <span class="error">{{$errors->first('country')}}</span>
            </div>
            <script>

                $(document).ready(function () {
                    $("#selectBoxFilter6").select2();
                    $("#selectBoxFilter6").change(function () {
                        $(this).find("option:selected").each(function () {
                            if ($(this).attr("value") == "1") {
                                $("#tabState").show();
                            } else {
                                $("#selectBoxFilter7").select2();
                                $("#tabState").hide();
                            }

                        });
                    }).change();
                });</script>

        </div>

        <div id="tabState" class="control-group row-fluid">
            <div id="Simple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter"></label>
                </div>
                <div class="span9" >
                    <div class="controls">
                        <select name="state" id="selectBoxFilter7">
                            <option value="">Please Select</option>
                            @foreach($states as $state)
                            <option value="{{ $state->state_id}}" @if($state->state_id==old('state')) selected="selected" @endif>{{ $state->name }}</option>		
                            @endforeach
                        </select>
                    </div>
                    <span class="error">{{$errors->first('state')}}</span>
                </div>
                <script>
                    $().ready(function () {
                        $("#selectBoxFilter7").select2();
                    });
                </script>
            </div>

        </div>

        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label"  for="Pin">Pin</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="pin" id="pin" class="span3" value="{{old('pin')}}">
                </div>
                <span class="error">{{$errors->first('pin')}}</span>
            </div>
        </div>


    </div>      


    <div id="newsletter-section" class="container-fluid">
        <div class="form-legend" id="Article-Details">Newsletter</div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Newsletter </label>
            </div>
            <div class="span9">
                <div class="controls">
                    @foreach($channels as $channel)
                    {{$channel->channel}}<input type="checkbox" name="newsletter[]" value="{{$channel->channel_id}}" style="margin:15px" />
                    @endforeach  
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
    $(document).ready(function () {
        $("#fileupload").validate({
            errorElement: "span",
            errorClass: "error",
            rules: {
                'first_name': {
                    required: true,
                },
                 'email': {
                    required: true,
                    email:true,
                },
                 'mobile': {
                    required: true,
                },
                 'sex': {
                    required: true,
                },
                 'country': {
                    required: true,
                },
                 
            }
        });
    });
</script> 

@stop
