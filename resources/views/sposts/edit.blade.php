@extends('layouts/master')

@section('title', 'Edit Sponsored Post - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit Sponsored Post</small></h1>
            
        </div>
       
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
                                "title" : "Channel", 
                                "attr" : { "href" : "#Channel" } 
                            }
                        },
												{
                            "data" : { 
                                "title" : "Article-Details", 
                                "attr" : { "href" : "#Article-Details" } 
                            }
                        },
                           
                                                {
                            "data" : { 
                                "title" : "Categories", 
                                "attr" : { "href" : "#categories" } 
                            }
                        },
							
												{
                            "data" : { 
                                "title" : "Assign This Article To An Event", 
                                "attr" : { "href" : "#assign-article-to-a-event" } 
                            }
                        },
							 
                                                {
                            "data" : { 
                                "title" : "Photos And Videos", 
                                "attr" : { "href" : "#photos-videos" } 
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
            <a href="/dashboard">
                <i class="icon-photon home"></i>
            </a>
        </li>
                <li class="current">
            <a href="#">Edit Sponsored Post</a>
        </li>
                
    </ul>
</div>            <header>
                <i class="icon-big-notepad"></i>
                <h2><small>Sponsored Post: {{$spost->id}}</small></h2>
                <h3><small>BW</small></h3>
            </header>
            {!! Form::open(array('url'=>'sposts/update','class'=> 'form-horizontal','id'=>'validation_form', 'files' => true)) !!}
            {!! csrf_field() !!}
			
                
                <div class="container-fluid">

                        <div class="form-legend" id="Channel">Channel</div>
                        <input type="hidden" name="id" value="{{$uid}}">
                        <input type="hidden" name="spid" value="{{$spost->id}}">
			<!--Select Box with Filter Search begin-->
                        <div  class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label" for="selectBoxFilter">Channel</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <select name="channel_sel" id="selectBoxFilter20">                                        
                                        @foreach($channel_arr as $ch)                                        
                                            <option value="{{$ch->channel_id}}">{{$ch->channel}}</option>
                                        @endforeach 
                                        <option value="All">Select</option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
                                    var v = {{$spost->channel_id}};                                    
                                    $("#selectBoxFilter20").val(v);
                                    $("#selectBoxFilter20").select2();
                                    
                                });
                            </script>
                        </div>						
                        <!--Select Box with Filter Search end-->					
                </div>
				
                <div class="container-fluid">
			<div class="form-legend" id="Article-Details">Article Details
							
			</div>
                        <!--Text Area - No Resize begin-->
                        <div  class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Title (200 Characters)</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <textarea  rows="4" name="title" id="title" class="no-resize">{{$spost->title}}</textarea>
                                </div>
                            </div>
                        </div>
                        <!--Text Area - No Resize end-->

                        <!--Text Area Resizable begin-->
                        <div id="Text_Area_Resizable" class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Summary (800 Characters)</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <textarea  rows="4" name="summary" id="summary" class="">{{$spost->summary}}</textarea>
                                </div>
                            </div>
                        </div>
                        <!--Text Area Resizable end-->

                        <!--WYSIWYG Editor - Full Options-->
                        <div id="WYSIWYG_Editor_-_Full_Options" class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Description</label>
                            </div>
                            <div class="span9">
                                <div class="controls elrte-wrapper">
                                    <textarea id="maxi" name="description" rows="2" class="auto-resize">{{$spost->description}}</textarea>
                                    <script>
                                        
                                        elRTE.prototype.options.panels.web2pyPanel = [
                                            'pastetext','bold', 'italic','underline','justifyleft', 'justifyright',
                                           'justifycenter', 'justifyfull','forecolor','hilitecolor','fontsize','link',
                                           'image', 'insertorderedlist', 'insertunorderedlist'];
 
                                       elRTE.prototype.options.toolbars.web2pyToolbar = ['web2pyPanel','tables'];
                                       
                                        $('#maxi').elrte({
                                            lang: "en",
                                            styleWithCSS: false,
                                            height: 200,
                                            toolbar: 'web2pyToolbar'
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                        <!--WYSIWYG Editor - Full Options end-->

                </div><!-- end container1 -->

		<div class="container-fluid">

                        <div class="form-legend" id="categories">Categories</div>

						<!--Select Box with Filter Search begin-->
                        <div  class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label" for="category1">Categories</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <select name="category1" id="selectBoxFilter2">                                        
                                        @foreach($category as $cat)
                                            <option value="{{$cat->category_id}}">{{$cat->name}}</option>
                                        @endforeach
                                        <option value="All">All</option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
                                    var c = {{$spost->category1}};                                    
                                    $("#selectBoxFilter2").val(c);
                                    
                                    $("#selectBoxFilter2").select2();
                                    $('#selectBoxFilter2').change(function(){
                                        $.get("{{ url('article/dropdown1')}}",
                                                { option: $(this).attr("value")+'&level=_two' },
                                                function(data) {
                                                    var selectBoxFilter3 = $('#selectBoxFilter3');
                                                    selectBoxFilter3.empty();
                                                    selectBoxFilter3.append("<option selected='' value=''>All</option>");
                                                    $.each(data, function(index, element) {
                                                        selectBoxFilter3.append("<option value='"+ element +"'>"+ index +"</option>");
                                                    });
                                                });
                                    });
                                });
                            </script>
                        </div>
			<div id="categories" class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label" for="category2"></label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <select name="category2" id="selectBoxFilter3">
                                        @foreach($category2 as $cat)
                                            <option value="{{$cat->category_two_id}}">{{$cat->name}}</option>
                                        @endforeach
                                        <option value="All">All</option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
                                    var c2 = {{$spost->category2}};                                    
                                    $("#selectBoxFilter3").val(c2);
                                    $("#selectBoxFilter3").select2();                                    
                                    //On category2 Change - get 3 tuple
                                    $('#selectBoxFilter3').change(function(){
                                        $.get("{{ url('article/dropdown1')}}",
                                                { option: $(this).attr("value")+'&level=_three' },
                                                function(data) {
                                                    var selectBoxFilter4 = $('#selectBoxFilter4');
                                                    selectBoxFilter4.empty();
                                                    selectBoxFilter4.append("<option selected='' value=''>All</option>");
                                                    $.each(data, function(index, element) {
                                                        selectBoxFilter4.append("<option value='"+ element +"'>"+ index +"</option>");
                                                    });
                                                });
                                    });
                                });
                            </script>
                        </div>
			<div id="categories" class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label" for="category3"></label>
                            </div>
				<div class="span9">
                                <div class="controls">
                                    <select name="category3" id="selectBoxFilter4">
                                        @foreach($category3 as $cat)
                                            <option value="{{$cat->category_three_id}}">{{$cat->name}}</option>
                                        @endforeach
                                        <option value="All">All</option>                                        
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
                                    var c3 = {{$spost->category3}};
                                    $("#selectBoxFilter4").val(c3);
                                    $("#selectBoxFilter4").select2();
                                    $('#selectBoxFilter4').change(function(){
                                        $.get("{{ url('article/dropdown1')}}",
                                                { option: $(this).attr("value")+'&level=_four' },
                                                function(data) {
                                                    var selectBoxFilter5 = $('#selectBoxFilter5');
                                                    selectBoxFilter5.empty();
                                                    selectBoxFilter5.append("<option selected='' value=''>All</option>");
                                                    $.each(data, function(index, element) {
                                                        selectBoxFilter5.append("<option value='"+ element +"'>"+ index +"</option>");
                                                    });
                                                });
                                    });
                                });
                            </script>
                        </div>
			<div id="categories" class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label" for="category4"></label>
                            </div>
				<div class="span9">
                                <div class="controls">
                                    <select name="category4" id="selectBoxFilter5">
                                        @foreach($category4 as $cat)
                                            <option value="{{$cat->category_four_id}}">{{$cat->name}}</option>
                                        @endforeach
                                        <option value="All">All</option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
                                    var c4 = {{$spost->category4}};
                                    $("#selectBoxFilter5").val(c4);
                                    $("#selectBoxFilter5").select2();
                                });
                            </script>
                        </div>
                        <!--Select Box with Filter Search end-->
						
                </div>
				
			<div class="container-fluid">

                        <div class="form-legend" id="assign-article-to-a-event">Assign This Article To An Event</div>

						<!--Select Box with Filter Search begin-->
                        <div  class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label" for="event">Event Name</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <select name="event" id="eventid">                                        
                                        @foreach($event as $ev)
                                        <option value="{{$ev->event_id}}">{{$ev->title}}</option>
                                        @endforeach
                                        <option value="All">All</option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
                                    $("#eventid").select2();
                                    var ev = {{$spost->event_id}};                                    
                                    $("#eventid").val(ev);
                                    
                                });
                            </script>
                        </div>
						
                        <!--Select Box with Filter Search end-->					
                </div>
				<!--end container-->
				
			<div class="container-fluid">

                        <div class="form-legend" id="photos-videos">Photos & Videos</div>
                 
                        <!--Tabs begin-->
                        <div  class="control-group row-fluid span-inset">
                            <ul class="nav nav-tabs" id="myTab">
                                 <li><a data-toggle="tab" class="active" href="#dropdown1">Upload Image</a></li>
                                <li><a data-toggle="tab" href="#tab-example1">Video</a></li>
				
                            </ul>
            <div class="tab-content">
                <div id="tab-example1" class="tab-pane fade">
		<div class="control-group row-fluid">
                    <div class="span3">
                                <label class="control-label">Title</label>
                            </div>
                            <input type="hidden" name="videoid" @if(count($arrVideo)>0) value="{{$arrVideo[0]->video_id}}" @endif>
                            <div class="span9">
                                <div class="controls">
                                    <input type="text" name="videoTitle" id="inputSpan9" @if(count($arrVideo)>0) value="{{$arrVideo[0]->title}}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Code (500/320)</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <textarea  rows="4" name="videoCode" class="no-resize" @if(count($arrVideo)>0) value="{{$arrVideo[0]->code}}" @endif>@if(count($arrVideo)>0) {{$arrVideo[0]->code}} @endif</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Source</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <input type="text" name="videoSource" id="inputSpan9" @if(count($arrVideo)>0) value="{{$arrVideo[0]->source}}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">URL</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <input type="text" name="videoURL" id="inputSpan9" @if(count($arrVideo)>0) value="{{$arrVideo[0]->url}}" @endif>
                                </div>
                            </div>
                        </div>
<!--                        <div class="control-group row-fluid">
                            <div class="span12 span-inset">
                                <div style="float:right; width:11%; margin-bottom:5px;"><button class="btn btn-warning" id="addvideobutton" name="addvideobutton" type="button" style="display:block;">Submit</button>
                                    <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:50%; display:block; margin-left:15px;"/></div>
                            </div>
                        </div>-->
                    </div>
               <script>
                // Delete / Save Photos - Video
                $.fn.MessageBox = function (msg)
                {
                    var formData = new FormData();
                    formData.append('photoId', msg);
                    var token = $('input[name=_token]');
                    var rowID = 'row'+msg;
                    var div = document.getElementById(rowID);
                    div.style.visibility = "hidden";
                    div.style.display = "none";
                    // process the form
                    $.ajax({
                        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                        url         : '/article/delPhotos', // the url where we want to POST
                        data        :  formData,
                        dataType    : 'json', // what type of data do we expect back from the server
                        contentType :  false,
                        processData :  false,
                        headers: {
                            'X-CSRF-TOKEN': token.val()
                        }
                    })
                        // using the done promise callback
                            .done(function(data) {
                                // log data to the console so we can see
                                console.log(data);                                
                            });
                };

                $(document).ready(function() {
                    var token = $('input[name=_token]');

                    // process the form - For Add Image in Album
                    $("#addvideobutton").click(function(){
                        // get the form data
                        var formData = new FormData();
                        formData.append('v_id', $('input[name=videoid]').val());
                        formData.append('title', $('input[name=videoTitle]').val());
                        formData.append('code',$('textarea[name=videoCode]').val());
                        formData.append('source', $('input[name=videoSource]').val());
                        formData.append('url', $('input[name=videoURL]').val());
                        formData.append('channel_id', $('select[name=channel_sel]').val());
                        formData.append('owner', 'sponsoredpost');

                        // process the form
                        $.ajax({
                            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                            //method      : 'POST',
                            url         : '/article/addVideos', // the url where we want to POST
                            //files       :  true,
                            data        :  formData,
                            dataType    : 'json', // what type of data do we expect back from the server
                            contentType :  false,
                            processData :  false,
                            success     :  function(respText){
                                theResponse = respText;
                                alert(theResponse);
                                //Assign returned ID to hidden array element
                                $('#uploadedVideos').val(theResponse);
                                //alert($('#uploadedVideos').val());
                            },
                            headers: {
                                'X-CSRF-TOKEN': token.val()
                            }
                        })
                            // using the done promise callback
                                .done(function(data) {
                                    // log data to the console so we can see
                                    console.log(data);
                                    // here we will handle errors and validation messages
                                });
                        // stop the form from submitting the normal way and refreshing the page
                        //event.preventDefault();
                    });

                    // Process the form - For Add Image in Album
                $.fn.addPhotoFunc = function (add_id, index){
                //$.fn.function.addPhotoFunc = function(){}
                //$("#addphotobutton").click(function(){}
                    //$("#addAuthorForm").on('click',function(event){}
 //                     alert('Yay 0 !');
                    //alert(index);
                    //alert(add_id);

                    var albumPhoto = "albumPhoto" + index;
                    var photoTitle = "photoTitle" + index;
                    var photoDesc = "photoDesc" + index;
                    var photoSource = "photoSource" + index;
                    var photoSourceURL = "photoSourceURL" + index;
                    var photoEnabled = "photoEnabled" + index;
                    var pID = add_id;

                     //alert(albumPhoto2.files.length);

                    // get the form data
                    // there are many ways to get this data using jQuery (you can use the class or id also)
                    var formData = new FormData();
                    if(index == 1)
                        {formData.append('albumphoto', albumPhoto1.files[0]);}
                    else if(index == 2)
                        {formData.append('albumphoto', albumPhoto2.files[0]);}
                    else if(index == 3)
                        {formData.append('albumphoto', albumPhoto3.files[0]);}
                    else if(index == 4)
                        {formData.append('albumphoto', albumPhoto4.files[0]);}

                    //formData.append('albumphoto', $('+albumPhoto+').files[0]);
                    formData.append('title', $('input[name='+photoTitle+']').val());
                    formData.append('description',$('textarea[name='+photoDesc+']').val());
                    formData.append('source', $('input[name='+photoSource+']').val());
                    formData.append('sourceurl', $('input[name='+photoSourceURL+']').val());
                    formData.append('active', $('input[name='+photoEnabled+']:checked').val());
                    formData.append('channel_id', $('select[name=channel_sel]').val());
                    formData.append('p_id', pID);
                    formData.append('owner', 'sponsoredpost');
                    
                    // process the form
                    $.ajax({
                        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                        //method      : 'POST',
                        url         : '/article/addPhotos', // the url where we want to POST
                        //files       :  true,
                        data        :  formData,
                        enctype     : 'multipart/form-data',
                        //dataType    : 'json', // what type of data do we expect back from the server
                        contentType :  false,
                        processData :  false,
                        success     :  function(respText){
                            theResponse = respText;
                            alert('response is ' + respText);
                            //Assign returned ID to hidden array element                            
                            var isthere = $('#uploadedImages').val();
                            alert('existing' + isthere);
                            var arrP = isthere.split(',');
                            arrP.push(theResponse);
                            var newval = arrP.join(',');
                            alert('New val' + newval);
                            $('#uploadedImages').val(newval);
                            
                        },
                        //encode      : true,
                        headers: {
                            'X-CSRF-TOKEN': token.val()
                        }
                    })
                        // using the done promise callback
                            .done(function(data) {
                                // log data to the console so we can see
                                console.log(data);                                
                                // here we will handle errors and validation messages
                            });
                    // stop the form from submitting the normal way and refreshing the page
                    //event.preventDefault();
                    }//);
                });
            </script>
            
                    <div id="dropdown1" class="tab-pane fade active in">
                        <div class="row-fluid">
                            <div class="span12">
                                @if(count($photos)>0)
                                <table class="table table-striped table-responsive" id="tableSortableResMed">
                                    <thead class="cf sorthead">
                                        <tr>
                                            <th>Image</th>
<!--                                            <th>Title</th>
                                            <th>Source</th>
                                            <th>Source URL</th>-->
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($photos as $photo)
                                        <tr id="row{{$photo->photo_id}}">
                                            <td>
                                                <img src="{{ config('constants.awsbaseurl').config('constants.aw_sponsored_image_thumb_dir').$photo->photopath}}" alt="spost" />
                                            </td>
<!--                                            <td>{{ $photo->title }}</td>-->
                                    <input type="hidden" name="deleteImagel" id="{{ $photo->photo_id }}">
<!--                                    <td class="center">{{ $photo->source }}</td>
                                    <td class="center">{{ $photo->source_url }}</td>-->
                                    <td class="center"><button type="button" onclick="$(this).MessageBox({{ $photo->photo_id }})" name="{{ $photo->photo_id }}" id="deleteImage" class="btn btn-mini btn-danger">Dump</button><img  src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:block; margin-left:15px;display:none;"/></td>
                                    </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>
                             <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="inputField">
                                Upload Photos<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Here You can add multiple photos by Drag and Drop or Simply By clicking and selecting  photos."><i class="icon-photon info-circle"></i></a>
                            </label>
                        </div>
                        <div class="span9 row-fluid" >
                            <div class=" fileupload-buttonbar">
                                <div class="col-lg-7">
                                    <!-- The fileinput-button span is used to style the file input field as button -->
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Add files...</span>
                                        <input type="file" name="files[]" id="articleimage" multiple />
                                    </span>
                                    <button type="submit" class="btn btn-primary start">
                                        <i class="glyphicon glyphicon-upload"></i>
                                        <span>Start upload</span>
                                    </button>
                                    <button type="reset" class="btn btn-warning cancel">
                                        <i class="glyphicon glyphicon-ban-circle"></i>
                                        <span>Cancel upload</span>
                                    </button>
                                    <button type="button" class="btn btn-danger delete">
                                        <i class="glyphicon glyphicon-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                    <input type="checkbox" class="toggle">
                                    <!-- The global file processing state -->
                                    <span class="fileupload-process"></span>
                                </div>
                                <!-- The global progress state -->
                                <div class="col-lg-5 fileupload-progress fade">
                                    <!-- The global progress bar -->
                                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                    </div>
                                    <!-- The extended global progress state -->
                                    <div class="progress-extended">&nbsp;</div>
                                </div>
                            </div>
                            <!-- The table listing the files available for upload/download -->
                            <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                            <input type="hidden" id="uploadedImages" name="uploadedImages">

                        </div>

                    </div>
		</div>
                  
                </div>
            </div>
                </div><!--end container-->
				
		<!--start container-->				
		<div class="container-fluid">                

                    <div class="control-group row-fluid">
                        <div class="span12 span-inset">
                            <label class="checkbox" >
                                <input type="checkbox" name="feature" @if($spost->feature_this =='1') checked="checked" @endif  class="uniformCheckbox" value="">
                                    <a href="#" target="_blank">Feature This</a>
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
                            <button type="submit" name="status" value="P" id="publish" class="btn btn-success">Publish</button><img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>
                            <button type="submit" name="status" value="D" id="dump" class="btn btn-danger">Dump</button><img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>								
                        </div>
                    </div>
                </div>
		<!--	end container-->			
            	{!! Form::close() !!}
        </div>



<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<script type="text/javascript" src="{{ asset('js/tmpl.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/load-image.all.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.iframe-transport.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-process.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-image.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-audio.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-video.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-validate.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-ui.js') }}"></script>
<script>
    $(document).ready(function(){
$('#validation_form').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '<?php echo url('sposts/image/upload') ?>',
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 10000000
    });
    });
     $('#validation_form').bind('fileuploaddone', function (e, data) {
    //console.log(e);
    var dataa=JSON.parse(data.jqXHR.responseText);
    //console.log(dataa['files']['0']['name']);
    $.each(dataa['files'], function(index, element) {
        //console.log(element.name);
        if($('#uploadedImages').val().trim())
            $('#uploadedImages').val($('#uploadedImages').val()+','+element.name);
        else
            $('#uploadedImages').val(element.name);    
    });
     
    });
    $('#validation_form').bind('fileuploaddestroyed', function (e, data) {
    // console.log(data);
     var file=getArg(data.url,'file');
     var images= $('#uploadedImages').val().split(',');
     images.splice(images.indexOf(file),1);
     $('#uploadedImages').val(images.join());
      //$('#imagesname').val($('#imagesname').val().replace(','+));
     
    });
    

function getArg(url,name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
    if (results==null){
       return null;
    }
    else{
       return results[1] || 0;
    }
}


</script>
@stop
