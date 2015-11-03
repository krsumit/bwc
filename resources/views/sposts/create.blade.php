@extends('layouts/master')

@section('title', 'Create New Sponsored Post - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Create New Sponsored Post</small></h1>
            
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
            <a href="#">Create New Sponsored Post</a>
        </li>
                
    </ul>
</div>            <header>
                <i class="icon-big-notepad"></i>
                <h2><small>Create Post</small></h2>
               
            </header>
      <script>
            function addsponsoredfunction(){
            var valid=1
            $('.author_error').remove();
            $('#add_new input').removeClass('error');
            $('#add_new select').removeClass('error');
            $('#add_new textarea').removeClass('error');
            if(!($('#title').val())){
            valid=0;
            $('#title').addClass('error');
            $('#title').after(errorMessage('Please fill title'));
            }
            if(!($('#summary').val())){
            valid=0;
            $('#summary').addClass('error');
            $('#summary').after(errorMessage('Please fill summary'));
            }
            if(!($('#maxi').val())){
            valid=0;
            $('#maxi').addClass('error');
            $('.elrte-wrapper').after(errorMessage('Please enter description.'));
            //$('#maxi').after(errorMessage('Please fill maxi'));
            }
            if(!($('input[name=photoSourceURL]').val())){
                //alert(1);
                 valid=0;
                $('input[name=photoSourceURL]').addClass('error');
                $('input[name=photoSourceURL]').after(errorMessage('Please choose tag from existing Tags'));
              
            }
            if(!($('select[name=channel_sel]').val())){
                //alert(1);
                 valid=0;
                $('select[name=channel_sel]').addClass('error');
                $('select[name=channel_sel]').after(errorMessage('Please select chennal'));
              
            }
            if(valid==0)
                return false;
            else
            return true;
            }
            function errorMessage($msg){
             return '<span class="error author_error" style="clear:both;display:inline-block">'+$msg+'</span>';
            }
                
               
                               
            </script>
                
            {!! Form::open(array('url'=>'sposts/','class'=> 'form-horizontal','id'=>'validation_form22','onsubmit'=>'return addsponsoredfunction()', 'files' => true)) !!}
            {!! csrf_field() !!}
			<div class="container-fluid" style="display:none">

                        <div class="form-legend" id="Notifications">Notifications</div>

                        <!--Notifications begin-->
                        <div class="control-group row-fluid" style="display:none">
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
                        <input type="hidden" name="id" value="{{$uid}}">
			<!--Select Box with Filter Search begin-->
                        <div  class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label" for="selectBoxFilter">Channel</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <select name="channel_sel" id="selectBoxFilter20">
                                        <option selected="" value="">Select</option>
                                        @foreach($channel_arr as $ch)                                        
                                            <option value="{{$ch->channel_id}}">{{$ch->channel}}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
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
                                    <textarea  rows="4" name="title" id="title" class="no-resize"></textarea>
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
                                    <textarea  rows="4" name="summary" id="summary" class=""></textarea>
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
                                    <textarea id="maxi" name="description" rows="2" class="auto-resize"></textarea>
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
                                            toolbar: 'web2pyToolbar',
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
                                        <option selected="" value="All">All</option>
                                        @foreach($category as $cat)
                                            <option value="{{$cat->category_id}}">{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
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
                                        <option selected="" value="All">All</option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
                                    $("#selectBoxFilter3").select2();
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
                                        <option selected="" value="All">All</option>
                                        <option value="Beige">Beige</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
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
                                        <option selected="" value="All">All</option>
                                        <option value="Beige">Beige</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
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
                                    <select name="event" id="selectBoxFilter20">
                                        <option selected="" value="All">All</option>
                                        @foreach($event as $ev)
                                        <option value="{{$ev->event_id}}">{{$ev->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
                                    $("#selectBoxFilter20").select2();
                                });
                            </script>
                        </div>
						
                        <!--Select Box with Filter Search end-->					
                </div>
				<!--end container-->
				
			<div class="container-fluid">

                        <div class="form-legend" id="photos-videos">Photos & Videos</div>
                    <!-- Uploaded Image and Video Ids -->
                    <input type="hidden" id="uploadedImages" name="uploadedImages[]">
                    <input type="hidden" id="uploadedVideos" name="uploadedVideos[]">
                    
                        <!--Tabs begin-->
                        <div  class="control-group row-fluid span-inset">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="dropdown active"><a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">Upload Image<b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a data-toggle="tab" href="#dropdown1">Image 1</a></li>
                                    <!--    <li><a data-toggle="tab" href="#dropdown2">Image 2</a></li>
					<li><a data-toggle="tab" href="#dropdown3">Image 3</a></li>
					<li><a data-toggle="tab" href="#dropdown4">Image 4</a></li>-->
                                    </ul>
				</li>
                                <li><a data-toggle="tab" href="#tab-example1">Video</a></li>
				<!--<li><a data-toggle="tab" href="#tab-example4">Current Photos</a></li>-->
                            </ul>
            <div class="tab-content">
                <div id="tab-example1" class="tab-pane fade">
		<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Title</label>
                    </div>
                    <div class="span9">
                                <div class="controls">
                                    <input type="text" name="videoTitle" id="inputSpan9">
                                </div>
                            </div>
                </div>
		<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Code (500/320)</label>
                    </div>
                    <div class="span9">
                                <div class="controls">
                                    <textarea  rows="4" name="videoCode" class="no-resize"></textarea>
                                </div>
                            </div>
                </div>
		<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Source</label>
                    </div>
                    <div class="span9">
                                <div class="controls">
                                    <input type="text" name="videoSource" id="inputSpan9">
                                </div>
                            </div>
                </div>
		<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                                <div class="controls">
                                    <input type="text" name="videoURL" id="inputSpan9">
                                </div>
                            </div>
                </div>
		<div class="control-group row-fluid">
                    <div class="span12 span-inset">
                        	<div style="float:right; width:11%; margin-bottom:5px;">
                                    <button class="btn btn-warning" id="addvideobutton" name="addvideobutton" type="button" style="display:block;">Submit</button>
                                    <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:50%; display:block; margin-left:15px;"/>
                                </div>
                            </div>
		</div> 
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

                    // process the form - For Add Image in Album
                    $("#addphotobutton").click(function(){
                        //$("#addAuthorForm").on('click',function(event){}
                        //  alert('Yay!');

                        // get the form data
                        // there are many ways to get this data using jQuery (you can use the class or id also)
                        var formData = new FormData();
                        formData.append('albumphoto', albumPhoto.files[0]);
                        formData.append('title', $('input[name=photoTitle]').val());
                        formData.append('description',$('textarea[name=photoDesc]').val());
                        formData.append('source', $('input[name=photoSource]').val());
                        formData.append('sourceurl', $('input[name=photoSourceURL]').val());
                        formData.append('active', $('input[name=photoEnabled]:checked').val());
                        formData.append('channel_id', $('select[name=channel_sel]').val());
                        formData.append('owner', 'sponsoredpost');

                        // process the form
                        $.ajax({
                            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                            //method      : 'POST',
                            url         : '/article/addPhotos', // the url where we want to POST
                            //files       :  true,
                            data        :  formData,
                            enctype     : 'multipart/form-data',
                            dataType    : 'json', // what type of data do we expect back from the server
                            contentType :  false,
                            processData :  false,
                            success     :  function(respText){
                                theResponse = respText;
                                alert(theResponse);
                                //Assign returned ID to hidden array element
                                alert($('#uploadedImages').val());
                                var isthere = $('#uploadedImages').val();
                                var arrP = isthere.split(',');
                                arrP.push(theResponse);
                                var newval = arrP.join(',');
                                $('#uploadedImages').val(newval);
                                //alert($('#uploadedImages').val());
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
                    });
                });
            </script>
            
		<div id="tab-example4" class="tab-pane fade">
                    <div class="container-fluid">

                       <!--Sortable Responsive Media Table begin-->
                       <div class="row-fluid">
                           <div class="span12">
                               <table class="table table-striped table-responsive" id="tableSortableResMed">
                                   <thead class="cf sorthead">
                                       <tr>
                                           <th>Image</th>
                                           <th>Title</th>
					   <th>Source</th>
                                           <th>Source URL</th>
					   <th>Action</th>                                      
                                        </tr>
                                   </thead>
                                   <tbody>
                                       <tr class="gradeX">
                                           <td>
                                               <img src="{{ asset('images/photon/user1.jpg') }}" alt="user" style="width:40%;" />
                                           </td>
                                           <td>Celebrate 'Dry Holi' to save water for future</td>
                                           <td class="center">JPEG image</td>
                                           <td class="center">PTI</td>
					   <td class="center"><button type="button" class="btn btn-mini btn-danger">Dump</button><img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:block; margin-left:15px;"/></td>
                                       </tr>                                       
                                   </tbody>
                               </table>

                           </div>
                       </div>
                       <!--Sortable Responsive Media Table end-->
							
                </div>
                </div>
                        <div id="dropdown1" class="tab-pane fade active in">
                            <div class="control-group row-fluid">
				<div class="span3">
                                    <label class="control-label">Upload Image 1</label>
				</div>
                                <div class="span9">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="input-append">
                                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span><input type="file" name="albumPhoto" id="albumPhoto" /></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
			<div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Title 1</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <input type="text" name="photoTitle" id="inputSpan9">
                                </div>
                            </div>
                        </div>
			<div class="control-group row-fluid">
                            <div class="span3">
				<label class="control-label">Description 1</label>
                            </div>
                            <div class="span9">
                		<div class="controls">
                                	<textarea rows="4" name="photoDesc" class=""></textarea>
				</div>
                            </div>
			</div>
			<div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Source Name</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <input type="text" name="photoSource" id="inputSpan9">
                                </div>
                            </div>
                        </div>
			<div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Source URL</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <input type="text" name="photoSourceURL" id="inputSpan9">
                                </div>
                            </div>
                        </div>
                
                    <div class="control-group row-fluid">
        		<div class="span12 span-inset">
			<div data-on-label="Enabled" data-off-label="Disabled" class="switch">
                                <input type="checkbox" name="photoEnabled" checked="checked">
                        </div>
				<button class="btn btn-warning" id="addphotobutton" name="addphotobutton" type="button" style="display:block;">Submit</button>
				 <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>
			</div>
                    </div>
		</div>
                <div id="dropdown2" class="tab-pane fade">
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Upload Image 2</label>
                        </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span><input type="file" /></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
		<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Title 2</label>
                    </div>
                    <div class="span9">
                                <div class="controls">
                                    <input type="text" name="inputSpan9" id="inputSpan9">
                                </div>
                            </div>
                </div>
		<div class="control-group row-fluid">
                    <div class="span3">
			<label class="control-label">Description 2</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <textarea rows="4" class=""></textarea>
			</div>
                    </div>
		</div>
		<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Source Name</label>
                    </div>
                    <div class="span9">
                                <div class="controls">
                                    <input type="text" name="inputSpan9" id="inputSpan9">
                                </div>
                            </div>
                </div>
		<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Source URL</label>
                    </div>
                    <div class="span9">
                                <div class="controls">
                                    <input type="text" name="inputSpan9" id="inputSpan9">
                            </div>
                            </div>
                </div>
				<div class="control-group row-fluid">
					<div class="span12 span-inset">
						<button class="btn btn-warning" type="button" style="display:block;">Submit</button>
					 <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>
					</div>
				</div>
                                </div>
		<div id="dropdown3" class="tab-pane fade">
                    <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Upload Image 3</label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span><input type="file" /></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Title 3</label>
                    </div>
                    <div class="span9">
                                <div class="controls">
                                    <input type="text" name="inputSpan9" id="inputSpan9">
                                </div>
                            </div>
                </div>
				<div class="control-group row-fluid">
									<div class="span3">
										<label class="control-label">Description 3</label>
									</div>
									<div class="span9">
												<div class="controls">
													<textarea rows="4" class=""></textarea>
												</div>
									</div>
								</div>
				<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Source Name</label>
                    </div>
                    <div class="span9">
                                <div class="controls">
                                    <input type="text" name="inputSpan9" id="inputSpan9">
                                </div>
                            </div>
                </div>
				<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Source URL</label>
                    </div>
                    <div class="span9">
                                <div class="controls">
                                    <input type="text" name="inputSpan9" id="inputSpan9">
                                </div>
                            </div>
                </div>
				<div class="control-group row-fluid">
					<div class="span12 span-inset">
						<button class="btn btn-warning" type="button" style="display:block;">Submit</button>
					 <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>
					</div>
				</div>
                                </div>
								<div id="dropdown4" class="tab-pane fade">
                                  <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Upload Image 4</label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span><input type="file" /></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Title 4</label>
                    </div>
                    <div class="span9">
                                <div class="controls">
                                    <input type="text" name="inputSpan9" id="inputSpan9">
                                </div>
                            </div>
                </div>
				<div class="control-group row-fluid">
									<div class="span3">
										<label class="control-label">Description 4</label>
									</div>
									<div class="span9">
												<div class="controls">
													<textarea rows="4" class=""></textarea>
												</div>
									</div>
								</div>
				<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Source Name</label>
                    </div>
                    <div class="span9">
                                <div class="controls">
                                    <input type="text" name="inputSpan9" id="inputSpan9">
                                </div>
                            </div>
                    </div>
		<div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Source URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="inputSpan9" id="inputSpan9">
                        </div>
                    </div>
                </div>
				<div class="control-group row-fluid">
					<div class="span12 span-inset">
						<button class="btn btn-warning" type="button" style="display:block;">Submit</button>
					 <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>
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
                                <input type="checkbox" name="feature" class="uniformCheckbox" value="is">
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

@stop
