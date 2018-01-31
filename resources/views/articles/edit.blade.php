@extends('layouts/master')

@section('title', 'Edit Article - BWCMS')
@section('content')
 <style> .none { display:none; } 
 </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit Article</small></h1>

        </div>

        <div class="panel-header">
            <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
        </div>

        <script>
            $(document).ready(function(){
                
                     $('#draftSubmit').click(function(){
                        $("#validation_form").validate().cancelSubmit = true;
                      });
                      
                       $('#dumpSubmit').click(function(){
                        $("#validation_form").validate().cancelSubmit = true;
                      });
             
                    $('#saveSubmit').click(doClick);
                    $('#pageSubmit').click(doClick);
                    $('#publishSubmit').click(doClick);
                    $('#saveSchedule').click(doClick);
                    //$('#scheduleSubmit').click(doClickSchedule);
                    //$('#dumpSubmit').click(doClick);
                    //$('#pageSubmit','#dumpSubmit','#publishSubmit').click(function() {}

                            function doClickSchedule(){
                            $("#validation_form").validate({
                            errorElement: "span",
                                    errorClass: "error",
                                    //$("#pageSubmit").onclick: true,
                                    onclick: true,
                                    rules: {
                                    "req1": {
                                    required: true,
                                            date: true
                                    },
                                            "date": {
                                            date: true
                                            },
                                            "time": {
                                            time: true
                                            }
                                    }
                            });
                            }
//                    function doClick(){
//                    //$('.btn-success').click(function() {}
//                    var as = $('#maxi').elrte('val');
//                            if (as.length ==0){
//                    alert('Please entter  article Description');
//                            $('#maxi').focus();
//                            return false;
//                    }
//                    if ($('#channel_sel').val() == '')
//                    {
//                    alert('Please Select Channel');
//                            $('#channel_sel').focus();
//                            return false;
//                    }
//                    if ($('#simpleSelectAuthor').val() === "") {
//                    alert('Please Select Author Type');
//                            $('#simpleSelectAuthor').focus();
//                            return false;
//                    } else if (($('#simpleSelectAuthor').val() != '1') && $('#simpleSelectBox1').val() === "") {
//                    alert('Please Select Author Name');
//                            $('#simpleSelectBox1').focus();
//                            return false;
//                    }
//                    if ($('#selectBoxFilter2').val() == '')
//                    {
//                    alert('Please Select Category from DropDown');
//                            $('#selectBoxFilter2').focus();
//                            return false;
//                    }
//                   
//                $("#validation_form").validate({
//                    errorElement: "span",
//                            errorClass: "error",
//                            //$("#pageSubmit").onclick: true,
//                            onclick: true,
//                            rules: {
//                            "req": {
//                            required: true
//                            },
//                                    "campaign": {
//                                    required: true
//                                    },
//                                    "numbers": {
//                                    required: false,
//                                            digits: true
//                                    },
//                                    "numbers_range": {
//                                    range: [1, 7]
//                                    },
//                                    "channel_sel":{
//                                    required: true
//                                    },
//                                    "title":{
//                                    required: true,
//                                            rangelength: [10, 200]
//                                    },
//                                    "description":{
//                                    required: true,
//                                            rangelength: [500, 80000]
//                                    },
//                                    "summary":{
//                                    
//                                            rangelength: [100, 800]
//                                    },
//                                    "email": {
//                                    email: true
//                                    },
//                                    "url": {
//                                    url: true
//                                    }
//                            }
//                    });
//                    }
                    function doClick(){ 
                                        var checkvalid=1;
                                        //alert(1);
                                    //$('.btn-success').click(function() {}
                                   $('#maxi').parent('div').removeClass('error'); 
                                   var as = $('#maxi').val();
                                   $('.error.elrte-error').remove();
                                   $('.error.author-error').remove();
                                   $('.error.noborder').remove();
                                    if(as.length==0){
                                       // alert(1);
                                       $('#maxi').parent('div').addClass('error');
                                        $('.elrte-wrapper').after('<span class="error elrte-error" style="display:block;margin-top:10px;" >Article description is required. </span>');
                                        checkvalid=0;
                                    }
//                                    else if (as.length < 500 || as.length > 80000){
//                                         //alert(2);
//                                                $('.elrte-wrapper').after('<span class="error elrte-error" style="display:block;">Please enter a text between 500 and 80000 characters long in Article Description</span>');
//                                    //alert('Please enter a text between 500 and 80000 characters long in Article Description');
//                                            $('#maxi').focus();
//                                            checkvalid=0;
//                                            //return false;
//                                    }   
//                                    if($('#channel_sel').val() == '')
//                                     {
//                                     alert('Please Select Channel');
//                                     $('#channel_sel').focus();
//                                     return false;
//                                     }
                                    
//                                    if ($('#simpleSelectAuthor').val() == '') {
//                                            alert('Please Select Author Type');
//                                            $('#simpleSelectAuthor').focus();
//                                            return false;
//                                    } else 
                                    if (($('#simpleSelectAuthor').val() != '') && ($('#simpleSelectAuthor').val() != '1') && $('#author').val() == '') {
                                            //alert('Please Select Author Name');
                                            $('#author').after('<span class="error author-error">Author name is required.</span>');
                                            $('#author').siblings('ul').addClass('error');
                                           checkvalid=0;
                                    }
//                                    if ($('#selectBoxFilter2').val() == '')
//                                    {
//                                    alert('Please Select Category from DropDown');
//                                            $('#selectBoxFilter2').focus();
//                                             checkvalid=0;
//                                    }
//                                    
                                   
                                    
                                     $("#validation_form").validate({
                                    errorElement: "span",
                                            errorClass: "error",
                                            //$("#pageSubmit").onclick: true,
                                            onclick: true,
                                            invalidHandler: function(event, validator) {
                         
                                                    for (var i in validator.errorMap) { ///alert(i);

                                                            if($('#'+i).hasClass('formattedelement')){
                                                                $('#'+i).siblings('.formattedelement').addClass('error');

                                                        }

                                                }
                                             },
                                            rules: {
                                            "req": {
                                            required: true
                                            },
                                                    "category1": {
                                                    required: true
                                                    },
                                                    "channel_sel":{
                                                    required: true
                                                    },
                                                    "authortype":{
                                                      required: true  
                                                    },
                                                    "title":{
                                                    required: true
                                                    },
                                                    "description":{
                                                    required: true
                                                    },
                                                    "summary":{
                                                    required: true,    
                                                    rangelength: [50, 800]
                                                    },
                                                   
                                                 
                                            }
                                    });
                                    
                                    
                                            if(!$("#validation_form").valid())
                                                checkvalid=0;
                                            if(checkvalid==0){
                                                $('#submitsection').prepend('<div class="error noborder">An error has occured. Please check the above form.</div>');
                                                return false;
                                            }else{
                                                $('#submitsection').hide();
                                            }
                                           // else
                                                // $("#fileupload").submit();

                                    }
                    });</script>

        <script type="text/javascript">
                    $(function () {
                    $("#jstree").jstree({
                    "json_data" : {
                    "data" : [
                    {
                    "data" : {
                    "title" : "Author Detail",
                            "attr" : { "href" : "#Author-Detail" }
                    }
                    },
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
                            "title" : "Social Details",
                                    "attr" : { "href" : "#social-media-detail" }
                            }
                    },
                    {
                    "data" : {
                    "title" : "Location",
                            "attr" : { "href" : "#topics-location" }
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
                    "title" : "Assign This Article To An Issue",
                            "attr" : { "href" : "#assign-article-to-a-Issue" }
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
                    "title" : "Assign This Article To A Campaign",
                            "attr" : { "href" : "#assign-article-to-a-campaign" }
                    }
                    },
                    {
                    "data" : {
                    "title" : "Tags",
                            "attr" : { "href" : "#tags" }
                    }
                    },
                    {
                    "data" : {
                    "title" : "Photos And Videos",
                            "attr" : { "href" : "#photos-videos" }
                    }
                    },
 @if(in_array('12',Session::get('user_rights')) && $article->status!='P' )
                            {
                            "data" : {
                            "title" : "Schedule for Upload",
                                    "attr" : { "href" : "#schedule-for-upload" }
                            }
                            },
@endif


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
                <a href="dashboard.html">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li>
                <a href="#">Articles</a>
                <ul class="breadcrumb-sub-nav">
                    <li>
                        <a href="new-articles.html">New Article</a>
                    </li>
                    <li>
                        <a href="scheduled-articles.html">Scheduled Articles</a>
                    </li>
                    <li>
                        <a href="published-articles.html">Published Article</a>
                    </li>
                    <li>
                        <a href="saved-articles.html">Saved Articles</a>
                    </li>
                    <li>
                        <a href="feature-box-management.html">Feature Box Management</a>
                    </li>
                    <li>
                        <a href="campaign-managemnet.html">Campaign Managemnet</a>
                    </li>
                    <li>
                        <a href="add-a-magazine-issue.html">Add A Magazine Issue</a>
                    </li>
                    <li>
                        <a href="#">Tips</a>
                    </li>
                    <li>
                        <a href="#">Reports</a>
                    </li>
                    <li>
                        <a href="#">Help</a>
                    </li>
                </ul>
            </li>
            <li class="current">
                <a href="javascript:;">Edit Article</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Article: {{ $article->article_id }}</small></h2>
        <h3><small>{{ $userTup->name }}</small></h3>
    </header>
    {!! Form::open(array('url'=>'article/update/','class'=> 'form-horizontal','id'=>'validation_form')) !!}
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
    <input type="hidden" name="id" value="{{$article->article_id}}">
    <div class="container-fluid">
        <div class="form-legend" id="Author-Detail">Author Detail

        </div>
        <div id="Simple_Select_Box" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="simpleSelectBox">Post As</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select style="display: none;" name="authortype" id="simpleSelectAuthor" class="form-control formattedelement">
                        <option value="">Please Select</option>
                        @foreach($postAs as $postas1)
                        @if($postas1->author_type_id == $article->author_type)
                        <option selected value="{{ $postas1->author_type_id }}">{{ $postas1->label }}</option>
                        @else
                        <option value="{{ $postas1->author_type_id }}">{{ $postas1->label }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                        $().ready(function(){
                $("#simpleSelectAuthor").select2({
                dropdownCssClass: 'noSearch'
                });
                });</script>
        </div>

        <div  class="control-group row-fluid" id="event_top_div" style="display:none;">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Event Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="event_id_author" id="event_id_author">
                        @foreach($event as $events)
                            @if($article->event_id == $events->event_id)
                            <option selected value="{{ $events->event_id }}">{{ $events->title }}</option>
                            @else
                            <option value="{{ $events->event_id }}">{{ $events->title }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                                $().ready(function(){
                        $("#event_id_author").select2();
                        });</script>
        </div>

        <div class="bs-docs-example" id="tabarea">
            <ul class="nav nav-tabs" id="iconsTab">
                <li class="active"><a data-toggle="tab" href="#existing">Choose From Existing</a></li>
                <!-- Add Author Section Only if Rights -->
              
                {{-- @if(count(array_diff(array('9','44','45'), Session::get('user_rights'))) != count(array('9','44','45')))
                <li class=""><a data-toggle="tab" href="#new">Add A New Author</a></li>
                @endif
                --}}
            </ul>
            <div class="tab-content">
                <div id="existing" class="tab-pane fade  active in">

                    <div id="Simple_Select_Box" class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="simpleSelectBox">Author Name</label>
                        </div>
                        <div class="span9">

                            <div class="controls">
                                <input type="text" class="valid" name="author" id="author"/>
                            </div>
                            <script>
   
                                   $().ready(function() {
                                        $("#author").tokenInput(function(){
                                            if($("#simpleSelectAuthor").val()==6)
                                                return "/article/speaker?option="+$("#event_id_author").val(); 
                                            else   
                                                return "/article/authordd?option="+$("#simpleSelectAuthor").val(); 
                                        },
                                            {
                                                theme: "facebook",
                                                searchDelay: 300,
                                                minChars: 3,
                                                preventDuplicates: true,
                                                tokenLimit:3,
                                                prePopulate: <?php echo $authors ?>,
                                        });
                                   });                            
                            </script>


                        </div>
                        
                    
                    </div>

    

                    <script type="text/javascript">

                                $(document).ready(function(){
                                    
                        $("#simpleSelectAuthor").change(function(){
                        $(this).find("option:selected").each(function(){
                            //return false
                        if ($(this).attr("value") == "1"){
                            
                        $("#tabarea").hide();
                        }else{
                             $("#tabarea").show();
                        }
                        
                         if ($(this).attr("value") == "6"){
                                       $('#event_top_div').show();
                                       $('#event_bottom_div').hide();
                                       
                                }
                                else{
                                    $('#event_top_div').hide();
                                    $('#event_bottom_div').show();
                                }
                                
                        });
                        }).change();
                        
                        
                         $("#simpleSelectAuthor").change(function(){
                             if ($(this).attr("value") != "1"){
                               $("#author").tokenInput("clear"); 
                           }
                          }); 
                           
                         $("#event_id_author").change(function(){
                               $("#author").tokenInput("clear");   
                          });
                                
                                
                        });</script>

                </div>

                <div id="new" class="tab-pane fade entypo ">
                    <!--<form name="addAuthorForm" method="post" enctype="multipart/form-data" id="addAuthorForm">-->
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" >&nbsp;</label>
                        </div>
                        <div class="span3">
                            <label class="radio">
                                <input type="radio" id="author_type" name="author_type" class="uniformRadio" value="4">
                                Columnist
                            </label>
                        </div>
                        <div class="span3">
                            <label class="radio">
                                <input  type="radio" id="author_type"  name="author_type" class="uniformRadio" value="3">
                                Guest Author
                            </label>
                        </div>
                        <div class="span3">
                            <label class="radio">
                                <input  type="radio" id="author_type" name="author_type" class="uniformRadio" value="2" checked>
                                BW Reporter
                            </label>
                        </div>

                        <script>
                                    $().ready(function(){
                            $(".uniformRadio").uniform({
                            radioClass: 'uniformRadio'
                            });
                            });</script>

                    </div>

                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="inputField">Name</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input id="inputField" id="name" name="Name" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="inputField">Bio</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <textarea  rows="3" id="Bio" class="" name="Bio"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="inputField">Email</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input id="inputField" name="email" type="email">
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="inputField">Mobile</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input id="inputField" name="mobile" type="number">
                            </div>
                        </div>
                    </div>
                    <div id="File_Upload" class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Photo</label>
                        </div>
                        <div class="span9">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="input-append">
                                    <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="photo" id="photo"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="inputField">Twitter</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input id="inputField" name="twitter" type="text">
                            </div>
                        </div>
                    </div>

                    <div  class="control-group row-fluid" id="ch-reporter">
                        <div class="span3">
                            <label class="control-label" for="selectBoxFilter">Choose Column</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <select name="column_id" id="selectBoxFilter221">
                                    <option value="" >Please Select</option>
                                    @foreach( $columns as $column)
                                    <option value="{{ $column->column_id }}">{{ $column->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <script>
                                    $().ready(function(){
                            $("#selectBoxFilter221").select2();
                            });</script>
                    </div>

                    <div class="control-group row-fluid">
                        <div class="span12 span-inset">
                            <button class="btn btn-warning pull-right" id="addabut" type="button" style="display:block;">Add</button>
                        </div>
                    </div>
                    <!--</form>-->
                </div>
<!--                <script>
                            // magic.js
                            $(document).ready(function() {
                    //var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    var token = $('input[name=_token]');
                            // process the form
                          
                    //$("#addAuthorForm").on('click',function(event){}
                    //  alert('Yay!');

                    // get the form data
                    // there are many ways to get this data using jQuery (you can use the class or id also)
                    var formData = new FormData();
                            formData.append('photo', photo.files[0]);
                            formData.append('name', $('input[name=Name]').val());
                            formData.append('author_type', $('input[name=author_type]:checked').val());
                            formData.append('email', $('input[name=email]').val());
                            formData.append('bio', $('textarea[id=Bio]').val());
                            formData.append('mobile', $('input[name=mobile]').val());
                            formData.append('twitter', $('input[name=twitter]').val());
                            formData.append('column_id', $('select[name=column_id]').val());
                            // process the form
                            $.ajax({
                            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                                    //method      : 'POST',
                                    url         : '/article/addAuthor', // the url where we want to POST
                                    //files       :  true,
                                    data        :  formData,
                                    enctype     : 'multipart/form-data',
                                    dataType    : 'json', // what type of data do we expect back from the server
                                    contentType :  false,
                                    processData :  false,
                                    //encode      : true,
                                    headers: {
                                    'X-CSRF-TOKEN': token.val()
                                    }
                            })
                            // using the done promise callback
                            .done(function(data) {

                            // log data to the console so we can see
                            console.log(data);
                                    //alert('Author Saved');
                                    // here we will handle errors and validation messages
                            });
                            // stop the form from submitting the normal way and refreshing the page
                            //event.preventDefault();
                    });
                    });</script>-->
            </div>
        </div>
    </div><!-- end container1 -->

    <div class="container-fluid">

        <div class="form-legend" id="Channel">Channel</div>

        <!--Select Box with Filter Search begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="channel_sel">Channel</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="channel_sel" id="channel_sel" class="required channel_sel formattedelement">
                        <!--<option selected="" value=></option>-->
                        @foreach($channels as $channel)
                        <option @if($channel->channel_id==$article->channel_id) selected="selected" @endif  value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
                        @endforeach
                    </select>
                    <span for="channel_sel1" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                </div>
            </div>
            <script>
                        $().ready(function(){
                $("#channel_sel").select2();
                        // Populate Events / Campaign / Magazine / Category Drop Down
                        $('#channel_sel').change(function(){
                $.get("{{ url('article/event')}}",
                { option: $(this).attr("value") },
                        function(data) {
                        var eventBox = $('#event_id');
                        var eventBoxTop = $('#event_id_author');
                        
                                eventBox.empty();
                                eventBoxTop.empty();
                                eventBox.append("<option selected='' value=''>Please Select</option>");
                                $.each(data, function(index, element) {
                                eventBox.append("<option value='" + element + "'>" + index + "</option>");
                                eventBoxTop.append("<option value='" + element + "'>" + index + "</option>");
                                });
                                $("#event_id").select2();
                                $('#event_id_author').select2();
                                if($('#simpleSelectAuthor').val()=="6"){
                                    $( "#event_id_author" ).trigger("change");
                                }
                        });
                        $.get("{{ url('article/campaign')}}",
                        { option: $(this).attr("value") },
                                function(data) {
                                var Box = $('#campaign_id');
                                        Box.empty();
                                        Box.append("<option selected='' value=''>Please Select</option>");
                                        $.each(data, function(index, element) {
                                        Box.append("<option value='" + element + "'>" + index + "</option>");
                                        });
                                      $("#campaign_id").select2();
                                });
                        $.get("{{ url('article/magazine')}}",
                        { option: $(this).attr("value") },
                                function(data) {
                                var Box = $('#magazine_id');
                                        Box.empty();
                                        Box.append("<option selected='' value=''>Please Select</option>");
                                        $.each(data, function(index, element) {
                                        Box.append("<option value='" + element + "'>" + index + "</option>");
                                        });
                                        $("#magazine_id").select2();
                                });
                        $.get("{{ url('article/dropdown1')}}",
                        { option: $(this).attr("value") + '&level=' },
                                function(data) {
                                var Box = $('#selectBoxFilter2');
                                        Box.empty();
                                        Box.append("<option selected='' value=''>Please Select</option>");
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
    </div>
    
    <div class="container-fluid">
        <div class="form-legend" id="Channel">Canonical</div>
        <div id="Text_Area_Resizable" class="control-group row-fluid">
               <div class="span3">
                   <label class="control-label">Is this article published  elsewhere</label>
               </div>
               <div class="span3">
                                <label class="radio">

                                    <input id="ifyes" @if($article->canonical_options==1) checked="checked" @endif type="radio" name="canonical_options" class="uniformRadio" value="1">

                                    Yes

                                </label>
                   
                  <!-- <div class="controls">
                       <input type="radio" value="1" name="canonical_options" id="ifyes" />Yes
                       <input type="radio"  value="0"name="canonical_options" id="ifno" checked/>No
                   </div>-->
               </div>
                <div class="span3">
                                <label class="radio">

                                    <input id="ifno" @if($article->canonical_options==0) checked="checked" @endif  type="radio" name="canonical_options" class="uniformRadio" value="0">

                                    No

                                </label>
                   
                  <!-- <div class="controls">
                       <input type="radio" value="1" name="canonical_options" id="ifyes" />Yes
                       <input type="radio"  value="0"name="canonical_options" id="ifno" checked/>No
                   </div>-->
               </div>
           
           </div>
           <div id="canonical" class="control-group row-fluid">
               <div id="Text_Area_Resizable" class="control-group row-fluid" >
                   <div class="span3">
                       <label class="control-label">Enter Canonical Url </label>
                   </div>
                   <div class="span9">
                       <div class="controls">
                           <input type="text" name="canonical_url" id="canonical_url" value="{{$article->canonical_url}}">
                       </div>
                   </div>
               </div>
       </div>
    </div>

    <div class="container-fluid">
        <div class="form-legend" id="Article-Details">Article Details

        </div>
        <!--Text Area - No Resize begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Title (200 Characters)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea  name="title" rows="4" class="no-resize required title_range valid">{{$article->title}}</textarea>
                    <span for="title" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
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
                    <textarea  name="summary" rows="4" class="">{{$article->summary}}</textarea>
                </div>
            </div>
        </div>
        <!--Text Area Resizable end-->

        <!--WYSIWYG Editor - Full Options-->
        <div id="WYSIWYG_Editor_-_Full_Options" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="description">Description</label>
            </div>
            <div class="span9">
                <div class="controls elrte-wrapper">
                    <textarea name="description" id="maxi" rows="2" class="auto-resize required valid">{{nl2br(trim($article->description))}}</textarea>
                    <span for="description" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                    <script>
                        /*
                        
                     elRTE.prototype.options.panels.web2pyPanel = [
                            'pastetext','bold', 'italic','underline','justifyleft', 'justifyright',
                           'justifycenter', 'justifyfull','forecolor','hilitecolor','fontsize','link',
                           'image', 'insertorderedlist', 'insertunorderedlist'];
                        elRTE.prototype.options.denyTags = ['div'];
                        elRTE.prototype.options.toolbars.web2pyToolbar = ['web2pyPanel','tables'];
                                $('#maxi').elrte({
                        lang: "en",
                                styleWithCSS: false,
                                height: 200,
                                toolbar: 'web2pyToolbar'
                        });
                                 */
                                
                      
                       $(function() {
                            $('#maxi').froalaEditor({
                                    height: 400,
                                    htmlRemoveTags: [],
                                    pastePlain: true,
                                    imageUploadURL: '/photo/editor/store',
                                    imageUploadParams: {
                                    _token: $('input[name="_token"]').val()
                                    },
                                    imageMaxSize: 1024 * 1024 * 1 / 2
                            });
                            $('#maxi').on('froalaEditor.image.error', function (e, editor, error, response) {
                            alert(error.message);
                            });
                        });       
                                
                      $(document).ready(function() { 
                                      @if($article->canonical_options==0)  
                                      $("#canonical").addClass("none");
                                      @endif
                                    $(':radio[id=ifno]').change(function() {
                                        $("#canonical").addClass("none");
                                    });
                                    $(':radio[id=ifyes]').change(function() {
                                        $("#canonical").removeClass("none");
                                        
                                    });
                          //     $('.elrte-wrapper > div > div').eq(2).find('a').remove();
                                 });  
                    </script>
                    <style>
    .fr-top:nth-child(2){
        display:none;
    }
</style>
                </div>
            </div>
        </div>

        <!--WYSIWYG Editor - Full Options end-->

    </div><!-- end container1 -->
    
    
    <div class="container-fluid">
      <div class="form-legend" id="social-media-detail">Social Media Detail</div>
      <!--Text Area - No Resize begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Social Title (200 Characters)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea  name="social_title" rows="2" class="no-resize  title_range valid">{{$article->social_title}}</textarea>
                    <span for="title" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                </div>
            </div>
        </div>
        <!--Text Area - No Resize end-->

        <!--Text Area Resizable begin-->
        <div id="Text_Area_Resizable" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Social Description (800 Characters)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea  name="social_summary" rows="2" class="">{{$article->social_summary}}</textarea>
                </div>
            </div>
        </div>
        <!--Text Area Resizable end-->

<!--        <div id="File_Upload" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Social Image ( Prefered size 600 x 315  )</label>
                </div>
                <div class="span9">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="input-append">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input name="photo" type="file"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                           
                        </div>
                    </div>
                </div>
            </div>-->
        
        
    </div>
    
    
    
    <div class="container-fluid">

        <div class="form-legend" id="topics-location">Location</div>
        <!--Topics begin-->
        <div  class="control-group row-fluid" style="display: none;">
            <div class="span3">
                <label class="control-label" for="dualMulti" style="width:100%;">Topics</label>
                <button type="button" name="genTopic" id="genTopic" class="btn btn-mini btn-inverse" style="margin-left:15px; display:block;">Generate Topics</button>
                <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:none; margin-left:15px;"/>
            </div>
            <div class="span9">
                <div class="controls ltopicsparentdiv">
                    <select multiple name="Ltopics[]" id="Ltopics">
                        @foreach($arrTopics as $topic)
                        <option selected="selected" value="{{$topic->topic_id}}">{{$topic->topic}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                        $().ready(function() {
                //$("#Ltopics").pickList();
                //});
                //if($('#genTopic').click()){}
                //alert('hete');
                //$("#Ltopics").pickList.empty();
                //$('#genTopics').click(GTopics);
                $("#genTopic").click(function() {
                   // alert($('#maxi').val());
                //function GTopics() {}
                var token = $('input[name=_token]');
                        //alert($('#maxi').elrte('val'));
                        // process the form
                        $.ajax({
                        type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                                url: '/article/generateTopics', // the url where we want to POST
                                data: {detail: $('#maxi').val()},
                                dataType: 'json', // what type of data do we expect back from the server
                                encode: true,
                                beforeSend  :function(data){
                                $('#genTopic').hide();
                                        $('#genTopic').siblings('img').show();
                                },
                                complete    :function(data){
                                $('#genTopic').show();
                                        $('#genTopic').siblings('img').hide();
                                },
                                success: function (data) {

                                var resplen = (data).length;
                                        var selectedarray = new Array();
                                        $('.ltopicsparentdiv').find("#Ltopics option:selected").each(function(){
                                selectedarray.push(parseInt($(this).val()));
                                });
                                        var dataoption = '<select multiple name="Ltopics[]" id="Ltopics">';
                                        var selectedop = '';
                                        
                                        $.each(data, function (index, element) {
                                        selectedop = '';
                                                if (selectedarray.indexOf(parseInt(element.id)) >= 0){
                                                    //alert(1);
                                                selectedop = 'selected="selected"';
                                            }
                                                dataoption += "<option " + selectedop + " value='" + element.id + "'>" + element.topic + "</option>";
                                        });
                                        dataoption += '</select>';
                                        
                                        // $( "#myselect option:selected" )

                                        //alert(dataoption);
                                        $(".ltopicsparentdiv").html(dataoption);
                                       $("#Ltopics").pickList();
                                },
                                headers: {
                                'X-CSRF-TOKEN': token.val()
                                }
                        })
                        // using the done promise callback
                        .done(function (data) {
                        // log data to the console so we can see
                        //console.log(data);
                        //alert(data);
                        //alert('Topic Populated');
                        // here we will handle errors and validation messages
                        });
                        // stop the form from submitting the normal way and refreshing the page
                        //event.preventDefault();
                        //});
                });
                        //$("#Ltopics").pickList();
                        //$("#Ltopics").select2();
                       // $('#genTopic').trigger('click');
                });</script>
            <div class="span12 span-inset">
                <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;">
            </div>
        </div>
        <!--Topics end-->

        <!--Select Box with Filter Search begin-->
        <div id="Simple_Select_Box_with_Filter_Search" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Select Location</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="country" id="selectBoxFilter6">
                        <option  value="">Please Select</option>
                        @foreach($country as $countrye)
                        @if($article->country == $countrye->country_id)
                        <option selected value="{{ $countrye->country_id }}">{{ $countrye->name }}</option>
                        @else
                        <option value="{{ $countrye->country_id }}">{{ $countrye->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                        $(document).ready(function(){
                $("#selectBoxFilter6").select2();
                        $("#selectBoxFilter6").change(function(){
                $(this).find("option:selected").each(function(){
                if ($(this).attr("value") == "1"){
                $("#tabState").show();
                } else{
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
                             <option  value="">Please Select</option>
                            @foreach($states as $state)
                            @if($article->state == $state->state_id)
                            <option selected value="{{ $state->state_id}}">{{ $state->name }}</option>
                            @else
                            <option value="{{ $state->state_id}}">{{ $state->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <script>
                            $().ready(function(){
                    $("#selectBoxFilter7").select2();
                    });</script>
            </div>

        </div>
        <!--Select Box with Filter Search end-->
        <!--Simple Select Box begin-->
        <div id="Simple_Select_Box" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="simpleSelectBox">News Type</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="newstype" id="simpleSelectBox" class="">
                        @foreach($newstype as $newstypE)
                        @if($article->news_type == $newstypE->news_type_id)
                        <option selected value="{{ $newstypE->news_type_id }}"> {{ $newstypE->name }} </option>
                        @else
                        <option value="{{ $newstypE->news_type_id }}"> {{ $newstypE->name }} </option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                        $().ready(function(){
                $("#simpleSelectBox").select2({
                dropdownCssClass: 'noSearch'
                });
                });</script>
        </div>
        <!--Simple Select Box end-->
    </div>
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
                        if($(this).attr("value")=='{{config('constants.ee_rating_cateogy_id')}}'){
                            $('#start_rating_div').show();
                        }else{
                            $('#start_rating_div').hide();
                        }
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
    @if(isset($acateg[0]))
    <div class="container-fluid" id="start_rating_div" @if($acateg[0]['category_id']!=config('constants.ee_rating_cateogy_id')) style="display: none;" @endif>

        <div class="form-legend" id="start_rating">Rating</div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Rating Point</label>
            </div>
            <div class="span9">
                <div class="controls"><input type="text" id="rating_point" value="{{$article->rating_point}}" name="rating_point"></div>
            </div>
        </div>     
    </div>
    @endif

    <div class="container-fluid">

        <div class="form-legend" id="assign-article-to-a-Issue">Assign This Article To A Magazine Issue</div>

        <!--Select Box with Filter Search begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Magazine Issue Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="magazine" id="magazine_id">
                         <option value="">Please Select</option>
                        @foreach($magazine as $magazines)
                        @if($article->magazine_id == $magazines->magazine_id)
                        <option selected value="{{ $magazines->magazine_id }}">{{ $magazines->title }}</option>
                        @else
                        <option value="{{ $magazines->magazine_id }}">{{ $magazines->title }}</option>
                        @endif
                        @endforeach
                       
                    </select>
                </div>
            </div>
            <script>
                        $().ready(function(){
                $("#magazine_id").select2();
                        //$("#selectBoxFilter20").select2();
                });
           </script>
        </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Featured in print </label>
            </div>
            <div class="span1">
                <div style="margin:18px 0 0 5px">
                  <input type="checkbox" name="featured_in_print" class="uniformCheckbox3" @if($article->featured_in_print == 1) checked @endif value="1">
                </div>
            </div>
        </div>

        <!--Select Box with Filter Search end-->
    </div>

    <div class="container-fluid" id="event_bottom_div">
        <div class="form-legend" id="assign-article-to-a-event">Assign This Article To An Event</div>

        <!--Select Box with Filter Search begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Event Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="event" id="event_id">
                         <option value="">Please Select</option>
                        @foreach($event as $events)
                        @if($article->event_id == $events->event_id)
                        <option selected value="{{ $events->event_id }}">{{ $events->title }}</option>
                        @else
                        <option value="{{ $events->event_id }}">{{ $events->title }}</option>
                        @endif
                        @endforeach
                       
                    </select>
                </div>
            </div>
            <script>
                        $().ready(function(){
                $("#event_id").select2();
                });</script>
        </div>

        <!--Select Box with Filter Search end-->
    </div>

    <div class="container-fluid">

        <div class="form-legend" id="assign-article-to-a-campaign">Assign this article to a Campaign</div>

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
                        @if($article->campaign_id == $campaigns->campaign_id)
                        <option selected value="{{ $campaigns->campaign_id }}">{{ $campaigns->title }}</option>
                        @else
                        <option value="{{ $campaigns->campaign_id }}">{{ $campaigns->title }}</option>
                        @endif
                        @endforeach
                       
                    </select>
                    <span for="campaign" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                    <!--<div class="control-group row-fluid">
                                    <div class="span12 span-inset">
                                    <button class="btn btn-warning" type="button" style="display:block; float:left;">Delete</button>
                                     <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>
                                      <button type="button" class="btn btn-primary" style="display:block; float:left; margin-left:5px;">Attach</button>
                                      <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>
                                    </div>
                            </div>-->
                </div>
            </div>

            <script>
                        $().ready(function(){
                $("#campaign_id").select2();
                });</script>
        </div>
        <!--Select Box with Filter Search end-->

    </div><!--end container-->

    <div class="container-fluid">

        <div class="form-legend" id="tags">Tags</div>
        <!--Select Box with Filter Search begin-->

        <div  class="control-group row-fluid" id="Multiple_Select_Box_with_Filter_Search">
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
                //alert(token.val());
                // process the form
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
                                // alert('Tag Saved');
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
                        prePopulate: <?php echo $tags ?>,
                });
                });</script>
        </div>
        <!--Select Box with Filter Search end-->
    </div>





    <div class="container-fluid">

        <div class="form-legend" id="photos-videos">Photos & Videos</div>

        <!--Tabs begin-->
        <div  class="control-group row-fluid span-inset">
            <ul class="nav nav-tabs" id="myTab">
                
<!--                <li class="dropdown active"><a data-toggle="dropdown" class="dropdown-toggle" href="#dropdown1">Upload Image<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a data-toggle="tab" href="#dropdown1">Image 1</a></li>
                        <li><a data-toggle="tab" href="#dropdown2">Image 2</a></li>
                        <li><a data-toggle="tab" href="#dropdown3">Image 3</a></li>
                        <li><a data-toggle="tab" href="#dropdown4">Image 4</a></li>
                    </ul>
                </li>
                <li><a data-toggle="tab" href="#tab-example1">Video</a></li>-->
                <li class="dropdown active"><a data-toggle="tab" href="#dropdown1">Photo</a></li>
                <li><a data-toggle="tab" href="#tab-example1">Video</a></li>
<!--                <li><a data-toggle="tab" href="#tab-example4">Current Photos</a></li>-->
            </ul>
            <div class="tab-content">
                 <div id="tab-example1" class="tab-pane fade">
                   <div class="control-group row-fluid"> 
                    <div class="span3">
                            <label class="radio">

                                <input id="embedcodevideo" type="radio" name="vodeo" class="uniformRadio" value="1">

                                Embed  Video Code

                            </label>

                    </div>
                    <div class="span3">
                            <label class="radio">

                                <input id="videoid" type="radio" name="vodeo" class="uniformRadio" value="2">

                                Video

                            </label>

                    </div>
                   </div>
                    
                    <div id="embedcodevideodetails" >
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
                    </div>
                    <div id="videocode">
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Video ID</label>
                        </div>
                     <div class="span9">
                            <div class="controls">
                                <input type="text" class="valid" name="video_Id" id="video_Id" value="{{$article->video_Id}}"/>
                            </div>
                        </div>
                    </div>
                    </div>
<!--                    <div class="control-group row-fluid">
                        <div class="span12 span-inset">
                            <div style="float:right; width:11%; margin-bottom:5px;"><button class="btn btn-warning" id="addvideobutton" name="addvideobutton" type="button" style="display:block;">Submit</button>
                                <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:50%; display:block; margin-left:15px;"/></div>
                        </div>
                    </div> -->
                </div>

<!--                <div id="tab-example4" class="tab-pane fade">
                    <div class="container-fluid">


                        

                    </div>
                </div>-->

                <div id="dropdown1" class="tab-pane fade active in">
                    
                    <div class="related_image " >
                        <div>
                            Browse recent related images : <input type="text" name="related_image_search" id="related_image_search" />
                            <button class="btn btn-success" onclick="searchRelated()" id="related_image_button"  name="status" type="button" style="margin-bottom:0px !important;">Search</button>
                            
                        </div>
                        <div class="relaed_image_box_outer hide" >
                            <img src="{{ asset('images/photon/preloader/76.gif')}}" class="loader-img-related-content hide" alt="loader" />
                            <div class="relaed_image_box">

                            </div>
                            <div class="related-img-selection-done"  >
                                <button class="btn btn-success hide related_action_button" onclick="relatedImageSelected()" id="related_selected_button" name="related_selected" type="button" >Upload</button>
                                <button class="btn btn-danger delete related_action_button" onclick="closeRelated()" type="button"><i class="glyphicon glyphicon-trash"></i><span>Close</span>
                                    </button>
                                <img src="{{ asset('images/photon/preloader/76.gif')}}" class="loader-img-selected hide" alt="loader" />
                            </div>
                        </div>
                    </div>
                    <!--Sortable Responsive Media Table begin-->
                        <div class="row-fluid">
                            <div class="span12">
                                @if(count($photos)>0)
                                <table class="table table-striped table-responsive uploaded-image-list" id="tableSortableResMed">
                                    <thead class="cf sorthead">
                                        <tr>
                                            <th>Image</th>
                                            <th>Title / Photo By </th>
  <!--                                          <th>Source</th>
                                            <th>Source URL</th>-->
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($photos as $photo)
                                        <tr id="row_{{$photo->photo_id}}" title="{{$photo->photopath}}">
                                            <td>
                                                <img src="{{ config('constants.awsbaseurl').config('constants.awarticleimagethumbtdir').$photo->photopath}}" alt="article" />
                                            </td>
                                            <td>{{$photo->title}}  /  {{$photo->photo_by}} </td>
<!--                                            <td>{{ $photo->title }}</td>-->
                                    <input type="hidden" name="deleteImagel" id="{{ $photo->photo_id }}">
<!--                                    <td class="center">{{ $photo->source }}</td>
                                    <td class="center">{{ $photo->source_url }}</td>-->
                                    <td class="center"><button type="button" onclick="$(this).MessageBox({{ $photo->photo_id }})" name="{{ $photo->photo_id }}" id="deleteImage" class="btn btn-mini btn-danger">Dump</button>
                                        <button type="button" onclick="editImageDetail({{ $photo->photo_id }},'article')" name="image{{ $photo->photo_id }}" id="deleteImage" class="btn btn-mini btn-edit">Edit</button>
                                        <img  src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:block; margin-left:15px;display:none;"/></td>
                                    </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>
                        <!--Sortable Responsive Media Table end-->
                           <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="inputField">
                                Upload Photos<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Here You can add multiple photos by Drag and Drop or Simply By clicking and selecting  photos (Size: {{config('constants.dimension_article')}}) (File Size <= {{config('constants.maxfilesize').' '.config('constants.filesizein')}}  )."><i class="icon-photon info-circle"></i></a>
                            </label>
                        </div>
                        <div class="span9 row-fluid" >
                            <div class=" fileupload-buttonbar">
                                <div class="col-lg-7">
                                    <!-- The fileinput-button span is used to style the file input field as button -->
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Add files...</span>
                                        <input type="file" name="files[]" multiple>
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
                                     <div style="float:right;">       
                                        <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_article')}}')">Need to crop images? Click here</a>
                                        <br>
                                        <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/resize/crop')}}?dimension={{config('constants.dimension_article')}}')">Need to resize images? Click here</a>
                                    </div>
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
                        </div>
<!--                        <script type="text/javascript">
                            $().ready(function() {

                                var errors="";
                                
                                $('#upload').mfupload({
                                    
                                    type        : '',   //all types
                                    maxsize     : 2,
                                    post_upload : "./file-uploader.html",
                                    folder      : "./",
                                    ini_text    : "Drag your file(s) here or click (max: 2MB each)",
                                    over_text   : "Drop Here",
                                    over_col    : '#666666',
                                    over_bkcol  : '#f0f0f0',
                                    
                                    init        : function(){       
                                        $("#uploaded").empty();
                                    },
                                    
                                    start       : function(result){     
                                        $("#uploaded").append("<div id='FILE"+result.fileno+"' class='files'>"+result.filename+"<div class='progress progress-info progress-thin'><div class='bar' id='PRO"+result.fileno+"'></div></div></div>"); 
                                    },

                                    loaded      : function(result){
                                        $("#PRO"+result.fileno).remove();
                                        $("#FILE"+result.fileno).html("Uploaded: "+result.filename+" ("+result.size+")");           
                                    },

                                    progress    : function(result){
                                        $("#PRO"+result.fileno).css("width", result.perc+"%");
                                    },

                                    error       : function(error){
                                        
                                        errors += error.filename+": "+error.err_des+"\n";
                                    },

                                    completed   : function(){
                                        if (errors != "") {
                                            alert(errors);
                                            errors = "";
                                        }
                                    }
                                });     
                            })
                        </script>-->
                    </div>
                </div>
             
<!--                <div id="dropdown2" class="tab-pane fade">
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Upload Image 2</label>
                        </div>
                        <div class="span9">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="input-append">
                                    <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span><input type="file" name="albumPhoto2" id="albumPhoto2" /></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid" id="pID" @if(count($photos)>1) value="{{$photos[1]->photo_id}}" @endif>
                         <div class="span3">
                            <label class="control-label">Title 2</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input type="text" name="photoTitle2" id="inputSpan9" @if(count($photos)>1) value="{{$photos[1]->title}}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Description 2</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <textarea rows="4" name="photoDesc2" class="" @if(count($photos)>1) value="{{$photos[1]->description}}" @endif>@if(count($photos)>1) {{$photos[1]->description}} @endif</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Source Name</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input type="text" name="photoSource2" id="inputSpan9" @if(count($photos)>1) value="{{$photos[1]->source}}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Source URL</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input type="text" name="photoSourceURL2" id="inputSpan9" @if(count($photos)>1) value="{{$photos[1]->source_url}}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span12 span-inset">
                            <div data-on-label="Enabled" data-off-label="Disabled" class="switch">
                                <input type="checkbox" name="photoEnabled2" @if(count($photos)>1) @if($photos[1]->active == 1) checked="checked" @endif @endif>
                            </div>
                            <button class="btn btn-warning" type="button" @if(count($photos)>1) value="{{$photos[1]->photo_id}}" @endif id="addphotobutton" onclick="$(this).addPhotoFunc(this.value, this.name)" name="2" style="display:block;">Submit</button>
                            <img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
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
                                    <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span><input type="file" name="albumPhoto3" id="albumPhoto3"/></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid" id="pID" @if(count($photos)>2) value="{{$photos[2]->photo_id}}" @endif>
                         <div class="span3">
                            <label class="control-label">Title 3</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input type="text" name="photoTitle3" id="inputSpan9" @if(count($photos)>2) value="{{$photos[2]->title}}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Description 3</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <textarea rows="4" name="photoDesc3" class="" @if(count($photos)>2) value="{{$photos[2]->description}}" @endif>@if(count($photos)>2) {{$photos[2]->description}} @endif</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Source Name</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input type="text" name="photoSource3" id="inputSpan9" @if(count($photos)>2) value="{{$photos[2]->source}}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Source URL</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input type="text" name="photoSourceURL3" id="inputSpan9" @if(count($photos)>2) value="{{$photos[2]->source_url}}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span12 span-inset">
                            <div data-on-label="Enabled" data-off-label="Disabled" class="switch">
                                <input type="checkbox" name="photoEnabled3" @if(count($photos)>2) @if($photos[2]->active == 1) checked="checked" @endif @endif>
                            </div>
                            <button class="btn btn-warning" type="button" @if(count($photos)>2) value="{{$photos[2]->photo_id}}" @endif id="addphotobutton" onclick="$(this).addPhotoFunc(this.value, this.name)" name="3" style="display:block;">Submit</button>
                            <img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
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
                                    <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span><input type="file" name="albumPhoto4" id="albumPhoto4" /></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid" id="pID" @if(count($photos)>3) value="{{$photos[3]->photo_id}}" @endif>
                         <div class="span3">
                            <label class="control-label">Title 4</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input type="text" name="photoTitle4" id="inputSpan9" @if(count($photos)>3) value="{{$photos[3]->title}}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Description 4</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <textarea rows="4" name="photoDesc4" class="" @if(count($photos)>3) value="{{$photos[3]->description}}" @endif> @if(count($photos)>3) {{$photos[3]->description}} @endif </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Source Name</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input type="text" name="photoSource4" id="inputSpan9" @if(count($photos)>3) value="{{$photos[3]->source}}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Source URL</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <input type="text" name="photoSourceURL4" id="inputSpan9" @if(count($photos)>3) value="{{$photos[3]->source_url}}" @endif>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span12 span-inset">
                            <div data-on-label="Enabled" data-off-label="Disabled" class="switch">
                                <input type="checkbox" name="photoEnabled4" @if(count($photos)>3) @if($photos[3]->active == 1) checked="checked" @endif @endif>
                            </div>
                            <button class="btn btn-warning" type="button" @if(count($photos)>3) value="{{$photos[3]->photo_id}}" @endif id="addphotobutton" onclick="$(this).addPhotoFunc(this.value, this.name)" name="4" style="display:block;">Submit</button>
                            <img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
                        </div>
                    </div>
                </div>-->
                
            </div>
            <label class="checkbox" >
                               <input type="checkbox" name="hide_image" @if($article->hide_image) checked="checked" @endif class="uniformCheckbox2" value="1">
                                      <a href="javascript:;">Do Not Show Images On Landing Page</a>
                  </label>
        </div>
        <!-- Uploaded Image and Video Ids -->
        <input type="hidden" id="uploadedImages" name="uploadedImages"/>
<!--        <input type="hidden" id="uploadedVideos" name="uploadedVideos[]">-->

    </div><!--end container-->
    <script>
                // magic.js
                $.fn.MessageBox = function (msg)
                {
                var formData = new FormData();
                        formData.append('photoId', msg);
                        var token = $('input[name=_token]');
                        var rowID = 'row_' + msg;
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
                                //alert('Author Saved');
                                // here we will handle errors and validation messages
                        });
                };
                $(document).ready(function() {
        //var csrf_token = $('meta[name="csrf-token"]').attr('content');
        var token = $('input[name=_token]');
                // process the form - For Add Image in Album
        /*        $("#addvideobutton").click(function(){
        // get the form data
        //alert($('input[name=videoid]').val());
        var formData = new FormData();
                formData.append('v_id', $('input[name=videoid]').val());
                formData.append('title', $('input[name=videoTitle]').val());
                formData.append('code', $('textarea[name=videoCode]').val());
                formData.append('source', $('input[name=videoSource]').val());
                formData.append('url', $('input[name=videoURL]').val());
                formData.append('channel_id', $('select[name=channel_sel]').val());
                formData.append('owner', 'article');
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
        }); */
                // process the form - For Add Image in Album
                $.fn.addPhotoFunc = function (add_id, index){
                //$.fn.function.addPhotoFunc = function(){}
                //$("#addphotobutton").click(function(){}
                //$("#addAuthorForm").on('click',function(event){}
//                     alert('Yay 0 !');
                alert(index);
                        alert(add_id);
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
                        if (index == 1)
                {formData.append('albumphoto', albumPhoto1.files[0]); }
                else if (index == 2)
                {formData.append('albumphoto', albumPhoto2.files[0]); }
                else if (index == 3)
                {formData.append('albumphoto', albumPhoto3.files[0]); }
                else if (index == 4)
                {formData.append('albumphoto', albumPhoto4.files[0]); }

                //formData.append('albumphoto', $('+albumPhoto+').files[0]);
                formData.append('title', $('input[name=' + photoTitle + ']').val());
                        formData.append('description', $('textarea[name=' + photoDesc + ']').val());
                        formData.append('source', $('input[name=' + photoSource + ']').val());
                        formData.append('sourceurl', $('input[name=' + photoSourceURL + ']').val());
                        formData.append('active', $('input[name=' + photoEnabled + ']:checked').val());
                        formData.append('channel_id', $('select[name=channel_sel]').val());
                        formData.append('p_id', pID);
                        formData.append('owner', 'article');
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
                                //alert('Author Saved');
                                // here we will handle errors and validation messages
                        });
                        // stop the form from submitting the normal way and refreshing the page
                        //event.preventDefault();
                }//);

        });
            
    
    $(document).ready(function() {              
     $("#videocode").addClass("none");                  
   $(':radio[id=videoid]').change(function() {
      
   $("#videocode").removeClass("none");
   $("#embedcodevideodetails").addClass("none");


});
$(':radio[id=embedcodevideo]').change(function() {
    
   $("#embedcodevideodetails").removeClass("none");
   $("#videocode").addClass("none");

});
  });   
    
    
    </script>

    <!--start container-->
     @if(in_array('12',Session::get('user_rights')) && $article->status!='P')
    
    <div class="container-fluid">

        <div class="form-legend" id="schedule-for-upload">Schedule For Upload</div>

        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="datepicker">
                    Date Picker<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Click to choose date."><i class="icon-photon info-circle"></i></a>
                </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="datepicked" id="datepicker" class="span3 req1" value="{{  $article->publish_date }}" />
                </div>
            </div>
        </div>

        <div id="Time_Picker" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="timeEntry">
                    Time Picker<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Enter time."><i class="icon-photon info-circle"></i></a>
                </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="timepicked" id="timeEntry" class="span3" value="{{ $article->publish_time }}"/>
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button name="status" value="SD" id="saveSchedule" class="btn btn-warning" type="submit" style="display:block;">Schedule</button>
                <img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
            </div>
        </div>
        <script>
                    $(function(){
 
                    $("#datepicker").datepicker({
            minDate: 0,
            dateFormat: "yy-mm-dd"
        });
        
//                            $("#datepickerInline").datepicker();
//                            $("#datepickerMulti").datepicker({
//                            numberOfMonths: 3,
//                            minDate: 0,
//                            showButtonPanel: true
//                    });
                            $.timeEntry.setDefaults({show24Hours: true,showSeconds: true});   
                            $('#timeEntry').timeEntry().change();
                            //$('#timeEntry').timeEntry({minTime: '-0 +1m'}).change();
                    });</script>
    </div>
    @endif
    @if(in_array('101',Session::get('user_rights')))

    <div class="container-fluid">

        <div class="form-legend" id="schedule-for-upload">Change Article Date Time </div>

        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="datepicker">
                    Date Time<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Click to choose date."><i class="icon-photon info-circle"></i></a>
                </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="publish_date_time_change" id="datepickerp" class="span3" />
                </div>
            </div>
        </div>
        <script>
                $(function () {
                    $("#datepickerp").datepicker();
                    $("#datepickerInline").datepicker();
                    $("#datepickerMulti").datepicker({
                        numberOfMonths: 3,
                        showButtonPanel: true
                    });
                    $('#timeEntry').timeEntry().change();
                });
            </script>  
    </div>

    @endif

    <div class="container-fluid">

        <div class="control-group row-fluid">
            <div class="span12 span-inset">

                <label class="checkbox" >
                    <input type="checkbox" name="for_homepage" class="uniformCheckbox" value="checkbox1" @if($article->for_homepage == 1) checked @endif>
                           <a href="#" target="_blank">Publish this to Home Page.</a>
                </label>
                <script>
                            $().ready(function(){
                    $(".uniformCheckbox").uniform();
                    });</script>

                <label class="checkbox" >
                    <input type="checkbox" name="important" class="uniformCheckbox2" value="checkbox1" @if($article->important == 1) checked @endif>
                           <a href="#" target="_blank">This article is important.</a>
                </label>
                <script>
                            $().ready(function(){
                    $(".uniformCheckbox2").uniform();
                    });</script>

                <label class="checkbox" >
                    <input type="checkbox" name="web_exclusive" class="uniformCheckbox3" value="checkbox1" @if($article->web_exclusive == 1) checked @endif>
                           <a href="#" target="_blank">Web Exclusive(Featured)</a>
                </label>
                 <label class="checkbox" >
                    <input type="checkbox" name="exclusive_non_featured" class="uniformCheckbox3" value="1" @if($article->exclusive_non_featured == 1) checked @endif>
                           <a href="#" target="_blank">Web Exclusive(Non featured)</a>
                </label>
                
                
                <script>
                            $().ready(function(){
                    $(".uniformCheckbox3").uniform();
                    });

                </script>


            </div>
        </div>

        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <!--<button type="submit" name="status" value="S" id="draftSubmit" class="btn btn-default">Draft</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>-->
                <!--<button type="submit" name="status" value="N" id="pageSubmit" name="N" class="btn btn-warning">Submit</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>-->
                @if($article->status=='S' )
                <button type="submit" name="status" value="SV" id="draftSubmit" class="btn btn-info">Save</button>
                @else
                <button type="submit" name="status" value="SV" id="saveSubmit" class="btn btn-info">Save</button>
               @endif
                @if($article->status=='S' )
                <button class="btn btn-warning" id="pageSubmit" value="N" name="status" type="submit">Submit</button>
                @endif
               
                @if(in_array('12',Session::get('user_rights')))
                @if($article->status!='P' )
                <button type="submit" name="status" value="P" id="publishSubmit" class="btn btn-success">Publish</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
                @endif
                @endif
                @if(in_array('13',Session::get('user_rights')))
                @if($article->status!='D')
                <button type="submit" name="status" value="D" id="dumpSubmit" class="btn btn-danger">Dump</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
                @endif
                @endif
                
            </div>
        </div>
    </div>
    <!--	end container-->
    {!! Form::close() !!}

</div>
<!--</body>
</html> -->

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
    <td colspan="4">            
    <table width="100%">
    <tr>  
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
        <tr>
            <td colspan="1">Photograph By</td>
            <td colspan="3"><input type="text" name="photographby[{%=file.name%}]"/></textarea></td>    
        </tr>

        <tr>
        <td colspan="1">Title</td>
        <td colspan="3"><input type="text" name="imagetitle[{%=file.name%}]"/></td>    
        </tr>

          <tr>
            <td colspan="1">Use this image on social</td>
            <td colspan="3"><input type="radio" value="{%=file.name%}" name="social_image"/></td>    
         </tr>

    
    </table>   
    </td>   
    </tr>
{% } %}
</script>
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<!--<script src="http:js/vendor/jquery.ui.widget.js"></script>-->
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<!-- <script type="text/javascript" src="{{ elixir('output/fileuploadJS.js') }}"></script> -->
<script type="text/javascript" src="{{ asset('js/jquery.iframe-transport.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-process.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-image.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-audio.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-video.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-validate.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-ui.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/florawysiwyg/froala_editor.pkgd.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/florawysiwyg/froala_editor.pkgd.min.css') }}" media="all" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/florawysiwyg/froala_style.min.css') }}" media="all" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/florawysiwyg/font-awesome.min.css') }}" media="all" />

<script>
    $(document).ready(function(){
$('#validation_form').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '<?php echo url('article/image/upload') ?>',
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 2000000
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
<script>
//alert(1);
  var token = $('input[name=_token]');
  $("#tableSortableResMed tbody").sortable({
      appendTo: "parent",
      helper: "clone",
      update: function (event, ui) {
      
        var data = $(this).sortable('serialize');
        //alert(data);    
        // POST to server using $.post or $.ajax
                $.ajax({
                    data: data,
                    type: 'POST',
                    url: '{{ url("/article/sort/".$article->article_id)}}',
                    headers: {
                                'X-CSRF-TOKEN': token.val()
                             }
                });
        
    }
  }).disableSelection();

</script>


@stop
