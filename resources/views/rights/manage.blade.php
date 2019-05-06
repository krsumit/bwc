@extends('layouts/master')

@section('title', 'CMS Management - BWCMS')


@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Right Management</small></h1>
        </div>
        <div class="panel-search container-fluid">
            <form class="form-horizontal" action="javascript:;">
                <input id="panelSearch" placeholder="Search" type="text" name="panelSearch">
                <button class="btn btn-search"></button>

            </form>
        </div>
        <label class="radio">
            <input type="radio" name="optionsRadios" class="uniformRadio" value="radio1">
            Search by Name
        </label>
        <label class="radio">
            <input type="radio" name="optionsRadios" class="uniformRadio" value="radio1">
            Search by Email ID
        </label>

        <script>
            $().ready(function(){
            $(".uniformRadio").uniform({
            radioClass: 'uniformRadio'
            });
            });                    </script>
        <br><br>
        <div class="panel-header">
    <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
        </div> 
        <script type="text/javascript">
                    $(function () {
                    $("#jstree").jstree({
                    "json_data" : {
                    "data" : [
                    {
                    "data" : {
                    "title" : "CMS Rights",
                            "attr" : { "href" : "#rights" }
                    }
                    },
                    ]
                    },
                            "plugins" : [ "themes", "json_data", "ui" ]
                    })
                            .bind("click.jstree", function (event) {
                            var node = $(event.target).closest("li");
                                    document.location.href = node.find('a').attr("href");
                                    return false;
                            })
                            .delegate("a", "click", function (event, data) { event.preventDefault(); });
                    });    </script>
        <div class="sidebarMenuHolder">
            <div class="JStree">
                <div class="Jstree_shadow_top"></div>
                <div id="jstree"></div>
                <div class="Jstree_shadow_bottom"></div>
            </div>
        </div>    </div>
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
                <a href="#">Right Management</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Right Management</small></h2>

    </header>
    {!! Form::open(array('url'=>'rights/manage/','class'=> 'form-horizontal','id'=>'form1')) !!}
    {!! csrf_field() !!}
    <div class="container-fluid" @if(count($errors->all())==0) style="display:none;" @endif >

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
        <!--Notifications end-->

    </div>
    <div class="container-fluid">
        <input type="hidden" name="id" value="{{$userid}}">
        <div class="form-legend" >Edit Profile</div>

        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="inputField" name="name" type="text" value="{{ $name }}">
                </div>
            </div>
        </div>

        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Email </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input  name="email" type="email" value="{{ $email}}">
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Password </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input  name="password" type="password">
                </div>
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
            <div class="span3">
                <label class="control-label" for="inputField">Mobile</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input  name="mobile" type="tel" maxlength="10" value="{{ $mobile}}">
                </div>
            </div>
        </div>
        <!--Simple Select Box begin-->
        <div id="Simple_Select_Box" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="simpleSelectBox">Role</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="role" id="roleid">
                        <option selected="" value="">---Please Select---</option>
                        @foreach($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <script>
                        $().ready(function(){
                var r = {{ $roleO }};
                        $("#roleid").val(r);
                        $("#roleid").select2({
                dropdownCssClass: 'noSearch'
                });
                });                                                </script>
        </div>
        <!--Simple Select Box end-->

        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Channel</label>
            </div>
            @foreach($allchannel as $channel)
            <div class="span3">
                <label class="radio">
                    <input type="checkbox"  @if(in_array($channel->channel_id,$rightChannels))  {{'checked="checked"'}} @endif   name="channelArr[]" class="uniformRadio" value="{{$channel->channel_id}}">
                           {{$channel->channel}}
                </label>
            </div>
            @endforeach

        </div>

        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button class="btn btn-primary" type="submit" name="save" style="display:block;">Save</button>
            </div>
        </div>


    </div>


    <div class="container-fluid">

        <div class="form-legend" id="rights">CMS Rights</div>
        <div class="per_container">

        </div>





        <div class="control-group row-fluid ">
            <div class="span12 span-inset ">
                <button type="submit" name="saveRights" class="btn btn-info rol-sav-btn" style="display:none;">Save</button><img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>

            </div>
        </div>


    </div><!-- end container -->


    {!! Form::close() !!}
</div>
<script>
            $(document).ready(function () {
    $("input[name='channelArr[]']:checked").each(function(){

    //alert($(this).val());
    $.ajax({
    type: 'GET', // define the type of HTTP verb we want to use (POST for our form)
            url: '/roles/get/channel/permission', // the url where we want to POST
            async:false,
            data: {id:'<?php echo $userid; ?>', right_for:'1', channelId:$(this).val()},
            dataType: 'text', // what type of data do we expect back from the server

            beforeSend: function (data) {
//                        $('#genTopic').hide();
//                        $('#genTopic').siblings('img').show();
            },
            complete: function (data) {
//                        $('#genTopic').show();
//                        $('#genTopic').siblings('img').hide();
            },
            success: function (data) {
            $('.per_container').append(data);
                    $('.rol-sav-btn').show();
            }
    })

    });
            $(".uniformCheckbox").uniform();
            $("input[name='channelArr[]']").click(function () {
    if (this.checked) {
    $.ajax({
    type: 'GET', // define the type of HTTP verb we want to use (POST for our form)
            url: '/roles/get/channel/permission', // the url where we want to POST
            async:false,
            data: {id:$('#roleid').val(), right_for:'2', channelId:$(this).val()},
            dataType: 'text', // what type of data do we expect back from the server

            beforeSend: function (data) {
//                        $('#genTopic').hide();
//                        $('#genTopic').siblings('img').show();
            },
            complete: function (data) {
//                        $('#genTopic').show();
//                        $('#genTopic').siblings('img').hide();
            },
            success: function (data) {

            $('.per_container').append(data);
                    $('.rol-sav-btn').show();
            }
    });
            $("#permissionch_" + $(this).val() + " .uniformCheckbox").uniform();
    } else {
    $('#permissionch_' + $(this).val()).remove();
            ///alert(2)
    }
    });
    });


</script>
@stop 
