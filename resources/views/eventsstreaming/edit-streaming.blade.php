@extends('layouts/master')
@section('title', 'Edit Event Streaming - BWCMS')
@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit Event Streaming</small></h1>
        </div>
        <script>

            $().ready(function () {
                $(".uniformRadio").uniform({
                    radioClass: 'uniformRadio'
                });

            });
        </script>
        <br><br>
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
                                    "title": "Edit Streaming",
                                    "attr": {"href": "#new"}
                                },
                            },
                            {
                                "data": {
                                    "title": "Save Streaming",
                                    "attr": {"href": "#save-streaming"}
                                },
                            }
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
            });
        </script>

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
                <a href="dashboard.html">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li class="current">
                <a href="javascript:;">Edit Event Streaming</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Edit Event Streaming</small></h2>

    </header>
    {!! Form::open(array('url'=>'event/streaming/'.$streaming->id,'class'=> 'form-horizontal','id'=>'speaker-form','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
     {{ method_field('PUT') }}
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
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
        <div class="form-legend" id="new">Streaming Details</div>
          <!--Select Box with Filter Search begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="channel_sel">Channel</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="channel_sel" id="channel_sel" class="required channel_sel formattedelement">
                        @foreach($channels as $channel)
                        <option value="{{ $channel->channel_id }}" @if($channel->channel_id==$streaming->channel_id) selected="selected" @endif>{{ $channel->channel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                $().ready(function(){
                    $("#channel_sel").select2();
                });
            </script>
        </div>

        <!--Select Box with Filter Search end-->

        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label required-label" for="inputField">Event Name</label>
                
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="event_name" name="event_name" value="{{$streaming->event_name}}" type="text">
                </div>
            </div>
        </div>

        <div id="File_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Photo<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Image dimension should be as per UI requirement, Size<500KB"><i class="icon-photon info-circle"></i></a></label>

            </div>
            <div class="span9">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="input-append">
                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="banner_image" id="speaker_image"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                        <a href="{{ config('constants.awsbaseurl').config('constants.eventstreaming').$streaming->banner_image}}" target="_blank"/><img height="50px" width="50px" src="{{config('constants.awsbaseurl').config('constants.eventstreaming').$streaming->banner_image}}"/></a>
                    </div>
                </div>
            </div>
        </div>
        <div id="Text_Area_-_No_Resize" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Embed Code</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea rows="4" name="embed_code" id="embed_code" class="no-resize">{{$streaming->embed_code}}</textarea>
                </div>
            </div>
        </div>
        <label class="" >
            <input type="checkbox" name="is_live" id="is_live" class="uniformCheckbox2" @if($streaming->is_live=='1') checked="checked" @endif value="1">
            <a for="is_live">Is live </a>
        </label>
        <!-- Add Tag to Tags Table - Ajax request -->
    </div>


    <div class="container-fluid" id="save-streaming">

        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button class="btn btn-success pull-right" type="submit" style="display:block; margin-right:5px;">Save</button>
            </div>

        </div>
    </div>                           

    <script>
        $(document).ready(function () {
            $("#speaker-form").validate({
                errorElement: "span",
                errorClass: "error",
                //$("#pageSubmit").onclick: true,
                onclick: true,
                invalidHandler: function (event, validator) {

                    for (var i in validator.errorMap) { ///alert(i);

                        if ($('#' + i).hasClass('formattedelement')) {
                            $('#' + i).siblings('.formattedelement').addClass('error');
                        }

                    }
                },
                rules: {
                    "req": {
                        required: true
                    },
                    "event_name": {
                        required: true
                    },
                     "embed_code": {
                        required: true,
                    },
                    "banner_image": {
                        extension: "jpg|png|jpeg"
                    },
                }
            });
        });
    </script>

    {!! Form::close() !!}

</div>

@stop