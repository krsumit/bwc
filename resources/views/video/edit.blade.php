@extends('layouts/master')

@section('title', 'Edit Video - BWCMS')


@section('content')
<?php
//echo '<pre>';
//print_r($arrTopics);exit;
?>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit video</small></h1>
            
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
            <a href="#">Video &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
            <ul class="breadcrumb-sub-nav">
                                 <li>
                            <a href="{{url('video/create')}}">Create New Video</a>
                        </li>
						<li>
                            <a href="{{url('video/list')}}">Published Video</a>
                        </li>
				
                            </ul>
        </li>
                <li class="current">
            <a href="javascript:;">Edit Video</a>
        </li>
    </ul>
</div>            <header>
                <i class="icon-big-notepad"></i>
                <h2><small>Edit Video</small></h2>
            </header>
<!--            <form class="form-horizontal" id="fileupload" action="" method="POST" enctype="multipart/form-data">-->
              {!! Form::open(array('url'=>'video/update','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
                
              {!! csrf_field() !!}    
             
                <input type="hidden" name="id" value="{{$video->id}}">
                <input type="hidden" name="video_thumb_name_second" value="{{$video->video_thumb_name}}">
                <input type="hidden" name="video_name_second" value="{{$video->video_name}}">
                <div class="container-fluid" style="display:none">
                        <div class="form-legend" id="Notifications">Notifications</div>
                        <!--Notifications begin-->
                        <div class="control-group row-fluid" >
                            <div class="span12 span-inset">
                                <div class="alert alert-success alert-block">
                                    <i class="icon-alert icon-alert-info"></i>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>This is Success Notification</strong>
                                    <span>Your data has been successfully modified.</span>
                                </div>
                                <div class="alert alert-block">
                                    <i class="icon-alert icon-alert-info"></i>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>This is Alert Notification</strong>
                                    <span>No result found.</span>
                                </div>
                                <div class="alert alert-error alert-block">
                                    <i class="icon-alert icon-alert-info"></i>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>This is Error Notification</strong>
                                    <span>Please select a valid search criteria.</span>
                                </div>
								<div class="alert alert-error alert-block">
                                    <i class="icon-alert icon-alert-info"></i>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>This is Error Notification</strong>
                                    <span>Please enter a valid email id.</span>
                                </div>
                            </div>
                        </div>
                        <!--Notifications end-->
                </div>
               <div class="container-fluid">

                        <div class="form-legend" id="Channel">Channel</div>

						<!--Select Box with Filter Search begin-->
                        <div  class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label" for="selectBoxFilter">Channel</label>
                            </div>
							<div class="span9">
                                <div class="controls">
                                    <select name="channel"  id="channel" class="formattedelement">
                                        <option selected="" value="">Please Select</option>
                                            @foreach($channels as $channel)
                                            <option @if($channel->channel_id==$video->channel_id) selected @endif; value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
                                    $("#channel").select2();
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
                            <input name="video_title" id="video_title" type="text" value="{{$video->video_title}}" >
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
                            <textarea  rows="4" class="" name="video_summary">{{$video->video_summary}}</textarea>
                        </div>
                    </div>
                </div>
            <!--Text Area Resizable end-->
                 <div id="File_Upload" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Upload A Video (File Size<={{config('constants.maxfilesizevideo').' '.config('constants.filesizein')}}) </label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="video_name"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                @if(trim($video->video_name))
                                <a href="{{ config('constants.awsbaseurl').config('constants.awvideo').$video->video_name}}" target="_blank"/><img src="{{config('constants.awsbaseurl').config('constants.awsstaticimage').'video.png'}}"/></a>
                                @endif
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div id="File_Upload" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Upload A Thumbnail Image (Size:{{config('constants.dimension_video')}}, File Size<={{config('constants.maxfilesize').' '.config('constants.filesizein')}})</label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="video_thumb_name"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_video')}}')">&nbsp;Need to crop images? Click here</a>                                
                                <div>
                                <img style="width: 70px;height: 70px;margin-left: 5px;" src="{{ config('constants.awsbaseurl').config('constants.awvideothumb').$video->video_thumb_name}}" alt="user" style="width:12%;" />
                                </div>
                            
                            </div>
                            
                        </div>
                         
                    </div>
                </div>   
            </div>
        </div><!--end container-->

<div class="container-fluid">

        <div class="form-legend" id="categories">Categories</div>

        <!--Select Box with Filter Search begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Categories</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="category1" id="selectBoxFilter2" class="formattedelement">
                        @if(count($acateg)>0)
                        <option selected="" value="{{ $acateg[0]['category_id'] }}">{{$acateg[0]['name']}}</option>
                        @else
                        <option selected="selected" value="">Please Select</option>
                        @endif
                        @foreach($category as $key )
                        <option value="{{ $key->category_id }}">{{ $key->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                $(document).ready(function(){
                $("#selectBoxFilter2").select2();
                $('#selectBoxFilter2').change(function(){
                $.get("{{ url('article/dropdown1')}}",
                { option: $(this).attr("value") + '&level=_two' },
                        function(data) {
                        var selectBoxFilter3 = $('#selectBoxFilter3');
                                selectBoxFilter3.empty();
                                selectBoxFilter3.append("<option selected='' value=''>Please Select</option>");
                                $.each(data, function(index, element) {
                                selectBoxFilter3.append("<option value='" + element + "'>" + index + "</option>");
                                });
                                $("#selectBoxFilter3").select2();
                                $('#selectBoxFilter4').html("<option value=''>Please Select</option>");
                                $('#selectBoxFilter4').select2();
                                $('#selectBoxFilter5').html("<option value=''>Please Select</option>");
                                $('#selectBoxFilter5').select2();
                        });
                });
                });</script>
        </div>
        <div id="categories" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter"></label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="category2" id="selectBoxFilter3">
                        @if(count($acateg)>1)
                        <option selected="" value="{{ $acateg[1]['category_id'] }}">{{$acateg[1]['name']}}</option>
                        @endif
                        <option value="">Please Select</option>
                    </select>
                </div>
            </div>
            <script>
                $(document).ready(function(){
                $("#selectBoxFilter3").select2();
                $('#selectBoxFilter3').change(function(){
                $.get("{{ url('article/dropdown1')}}",
                { option: $(this).attr("value") + '&level=_three' },
                        function(data) {
                        var selectBoxFilter4 = $('#selectBoxFilter4');
                                selectBoxFilter4.empty();
                                selectBoxFilter4.append("<option selected='' value=''>Please Select</option>");
                                $.each(data, function(index, element) {
                                selectBoxFilter4.append("<option value='" + element + "'>" + index + "</option>");
                                });
                                $('#selectBoxFilter4').select2();
                                $('#selectBoxFilter5').html("<option value=''>Please Select</option>");
                                $('#selectBoxFilter5').select2();
                        });
                });
                });</script>
        </div>
        <div id="categories" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter"></label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="category3" id="selectBoxFilter4">
                        @if(count($acateg)>2)
                        <option selected="" value="{{ $acateg[2]['category_id'] }}">{{ $acateg[2]['name'] }}</option>
                        @endif
                        <option value="">Please Select</option>
                    </select>
                </div>
            </div>
            <script>
                $(document).ready(function(){
                $("#selectBoxFilter4").select2();
                $('#selectBoxFilter4').change(function(){
                $.get("{{ url('article/dropdown1')}}",
                { option: $(this).attr("value") + '&level=_four' },
                        function(data) {
                        var selectBoxFilter5 = $('#selectBoxFilter5');
                                selectBoxFilter5.empty();
                                selectBoxFilter5.append("<option selected='' value=''>Please Select</option>");
                                $.each(data, function(index, element) {
                                selectBoxFilter5.append("<option value='" + element + "'>" + index + "</option>");
                                });
                                $('#selectBoxFilter5').select2();
                        });
                        
                });
                });</script>
        </div>
        <div id="categories" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter"></label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="category4" id="selectBoxFilter5">
                        @if(count($acateg)>3)
                        <option selected="" value="{{ $acateg[3]['category_id'] }}">{{$acateg[3]['name']}}</option>
                        @endif
                        <option value="">Please Select</option>
                    </select>
                </div>
            </div>
            <script>
                $().ready(function(){
                $("#selectBoxFilter5").select2();
                });</script>
        </div>
        <!--Select Box with Filter Search end-->

    </div> 

    <div class="container-fluid"> 
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
                                prePopulate: <?php echo $tags ?>
                        });
                        });            </script>
        </div>  
        
        <!--Select Box with Filter Search end-->
    </div>  
       
         <div class="container-fluid">

        <div class="form-legend" id="assign-article-to-a-campaign">Assign this Video to a Campaign</div>

        <!--Select Box with Filter Search begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="campaign">Campaign</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="campaign"  id="campaign_id">
                         <option value="">Please Select</option>
                     
                        @foreach($campaign as $campaigns)
                        @if($video->campaign_id == $campaigns->campaign_id)
                        <option selected value="{{ $campaigns->campaign_id }}">{{ $campaigns->title }}</option>
                        @else
                        <option value="{{ $campaigns->campaign_id }}">{{ $campaigns->title }}</option>
                        @endif
                        @endforeach
                       
                    </select>
                    <span for="campaign" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                   
                </div>
            </div>

            <script>
                        $().ready(function(){
                $("#campaign_id").select2();
                 $('#channel').change(function () {

                        $.get("{{ url('article/campaign')}}",
                        { option: $(this).attr("value") },
                                function(data) {
                                var Box = $('#campaign_id');
                                        Box.empty();
                                        Box.append("<option value=''>Please Select</option>");
                                        $.each(data, function(index, element) {
                                        Box.append("<option value='" + element + "'>" + index + "</option>");
                                        });
                                        $("#campaign_id").select2();
                                });
                                
                             $.get("{{ url('article/dropdown1')}}",
                                { option: $(this).attr("value") + '&level=' },
                                        function(data) {
                                        var Box = $('#selectBoxFilter2');
                                                Box.empty();
                                                Box.append("<option value=''>Please Select</option>");
                                                $.each(data, function(index, element) {
                                                Box.append("<option value='" + element + "'>" + index + "</option>");
                                                });
                                                $("#selectBoxFilter2").select2();
                                                $('#selectBoxFilter3').html("<option value=''>Please Select</option>");
                                                $("#selectBoxFilter3").select2();
                                                $('#selectBoxFilter4').html("<option value=''>Please Select</option>");
                                                $('#selectBoxFilter4').select2();
                                                $('#selectBoxFilter5').html("<option value=''>Please Select</option>");
                                                $('#selectBoxFilter5').select2();
                                        });     
                        });
                        
                });</script>
        </div>
        <!--Select Box with Filter Search end-->
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="simpleSelectBox">Video Type</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="video_type" id="simpleSelectBox">
                        <option value="0"> Select Video Type</option>
                        @foreach($videotypes as $videotype)
                        <option @if($video->video_type == $videotype->news_type_id) selected="selected" @endif value="{{ $videotype->news_type_id }}"> {{ $videotype->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                       $().ready(function(){
                        $("#simpleSelectBox").select2();
                        });
             </script>
        </div>
    </div>
        <div class="control-group row-fluid">
            <div class="span12 span-inset">

                <label class="checkbox" >
                    <input type="checkbox" name="for_automated_news_video" class="uniformCheckbox" value="1"  @if($video->for_automated_news_video == '1') checked="checked" @endif >
                    <a href="#" target="_blank">Automated News Video</a>
                </label>
                <script>
                    $().ready(function(){
                    $(".uniformCheckbox").uniform();
                    });
                </script>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button  type="submit" class="btn btn-info">Save</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
            </div>
        </div>

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
                                    accept: "image/*"
                                            
                                    },
                                    "video_title": {
                                    required: true
                                    },
                                    "video_summary":{
                                    rangelength: [100, 800]
                                    },
                                    "video_name":{
                                    accept: "video/*",
                                    maxFileSize: {
                                        "unit": "MB",
                                        "size": 200
                                    }
                                    }
                                    
                            }
                    });
</script>


@stop
    
    
