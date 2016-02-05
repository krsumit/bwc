@extends('layouts/master')

@section('title', 'Create New Album - BWCMS')


@section('content')
<?php
//print_r($p1);exit;
?>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Add Video</small></h1>

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
            <li>
                <a href="/video/create">Video</a>
                
            </li>
            <li class="current">
                <a href="javascript:;">Add Video</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Add Video</small></h2>
        
    </header>
    
   {!! Form::open(array('url'=>'video/','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
        {!! csrf_field() !!} 
        <div class="container-fluid " id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')))style="display: none" @endif >

            <div class="form-legend" id="Notifications">Notifications</div>

            <!--Notifications begin-->
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    
                        @if(Session::has('message'))
                    <div class="alert alert-success alert-block">
                        <i class="icon-alert icon-alert-info"></i>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>This is Success Notification</strong>
                        <span>{{ Session::get('message') }}</span>
                    </div>
                    @endif
                   
                </div>
            </div>
            <!--Notifications end-->

        </div>

        <div class="container-fluid">

            <div id="channel" class="form-legend">Channel</div>

            <!--Select Box with Filter Search begin-->
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Channel</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="channel" id="selectBoxFilter20">
                      
                            @foreach($channels as $channel)
                               <option @if($channel->channel_id==$currentChannelId) selected="selected" @endif value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <script>
                    $().ready(function () {
                        $("#selectBoxFilter20").select2();
                    });
                </script>
            </div>

            <!--Select Box with Filter Search end-->					
        </div>


        <div class="container-fluid">

            <div id="video" class="form-legend" id="Tabs">Videos</div>

            <div id="tab-example1" class="tab-pane fade active in">
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Title</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input name="video_title" id="video_title" type="text">
                        </div>
                    </div>
                </div>
                <!--Text Area Resizable begin-->
                <div id="Text_Area_Resizable" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Summary (800 Characters)</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <textarea  rows="4" class="" name="video_summary"></textarea>
                        </div>
                    </div>
                </div>
            <!--Text Area Resizable end-->
                 <div id="File_Upload" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Upload A Video </label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="video_name"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="File_Upload" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Upload A Thumbnail Image</label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="video_thumb_name"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
        </div><!--end container-->
 
        <div class="container-fluid">

        <div class="form-legend" id="tags">Tags</div>
        <!--Select Box with Filter Search begin-->

        <div class="control-group row-fluid" id="Multiple_Select_Box_with_Filter_Search">
            <div class="control-group row-fluid">    
                <div class="span3">
                    <label for="multiFilter" class="control-label">Tags</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input type="text" class="valid" name="Taglist" id="Taglist"/>
                    </div>
                </div>
            </div>
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="add tags">Add New Tags<br>(Separated by Coma. No spaces)</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input type="text" name="addtags" class="valid"><span for="add tags" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                    </div>
                </div>
                <div class="span12 span-inset">

                    <div style="float:right; width:11%; margin-bottom:5px;"><button type="button" class="btn btn-primary" id="attachTag" style="display:block;">Attach</button>
                        <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:50%; display:block; margin-left:15px;display:none;"></div>
                </div>
            </div>
            <!-- Add Tag to Tags Table - Ajax request -->
            <script>
                                $().ready(function() {
                        var token = $('input[name=_token]');
                                // process the form
                                $("#attachTag").click(function(){
                        if ($('input[name=addtags]').val().trim().length == 0){
                        alert('Please enter tage'); return false;
                        }

                        $.ajax({
                        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                                url         : '/article/addTag', // the url where we want to POST
                                data        :   { tag : $('input[name=addtags]').val() },
                                dataType    : 'json', // what type of data do we expect back from the server
                                encode      : true,
                                beforeSend  :function(data){
                                $('#attachTag').hide();
                                        $('#attachTag').siblings('img').show();
                                },
                                complete    :function(data){
                                $('#attachTag').show();
                                        $('#attachTag').siblings('img').hide();
                                },
                                success     :  function(data){

                                $.each(data, function(key, val){

                                $("#Taglist").tokenInput("add", val);
                                });
                                 $('input[name=addtags]').val('');
//                                        alert('Tag Saved');
//                                        $("#Taglist").tokenInput("add", [{"id":"2","name":"Coal Scam"},{"id":"4","name":"Cuisine"},{"id":"7","name":"Education"},{"id":"15","name":"Election"},{"id":"208","name":"testtag1"},{"id":"1","name":"Modi"},{"id":"207","name":"tagtest"},{"id":"210","name":"ankita"}]);
//                                         //$("#Taglist").tokenInput("add", {id: 9992, name: "test22"});
                                },
                                headers: {
                                'X-CSRF-TOKEN': token.val()
                                }
                        })
                        });
                                $("#Taglist").tokenInput("/tags/getJson", {
                        theme: "facebook",
                                searchDelay: 300,
                                minChars: 4,
                                preventDuplicates: true,
                        });
                        });            </script>
        </div>                       
        <!--Select Box with Filter Search end-->
    </div>

      <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button  type="submit" class="btn btn-info">Save</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
            </div>
        </div>
        <!--	end container-->

    {!! Form::close() !!}
</div>
<script>
     $("#fileupload").validate({
                    errorElement: "span",
                            errorClass: "error",
                            //$("#pageSubmit").onclick: true,
                            onclick: true,
                            invalidHandler: function(event, validator) {
                         
                                    for (var i in validator.errorMap) {
                                        
                                            if($('#'+i).hasClass('formattedelement')){
                                                $('#'+i).siblings('.formattedelement').addClass('error');
                                                
                                        }
                                   
                                }
                             },
                            rules: {
                            "req": {
                            required: true
                            },
                                    "channel": {
                                        required: true
                                    },
                                    "video_thumb_name": {
                                    required: true,
                                    accept: "image/*"
                                            
                                    },
                                    "video_title": {
                                    required: true
                                    },
                                    "video_summary":{
                                    rangelength: [100, 800]
                                    },
                                    "video_name":{
                                    required: true,
                                    accept: "video/*",
                                    maxFileSize: {
                                        "unit": "MB",
                                        "size": 200
                                    }
                                    },
                                     
                            }
                    });
</script>
@stop

