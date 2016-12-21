@extends('layouts/master')

@section('title', 'Create Article - BWCMS')


@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Create Article</small></h1>

        </div>

        <div class="panel-header">
 <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
        </div>

        <script>
            $(document).ready(function(){
            /*if($('#pageSubmit').click() || $('#dumpSubmit').click() || $('#publishSubmit').click()){
             alert('here in 3');
             return false;
             } */
            $('#draftSubmit').click(function(){
            $("#fileupload").validate().cancelSubmit = true;
            });
                    $('#dumpSubmit').click(function(){
            $('span.error').remove();
                    $('div.error.noborder').remove();
                    $('.error').removeClass('error');
            });
                    $('#pageSubmit').click(doClick);
                    $('#saveSchedule').click(doClick);
                    $('#publishSubmit').click(doClick);
                    //$('#dumpSubmit').click(doClick);
                            //$('#pageSubmit','#dumpSubmit','#publishSubmit').click(function() {}
                                    function doClick(){
                                    // alert(1);
                                    var checkvalid = 1;
                                            //alert(1);
                                            //$('.btn-success').click(function() {}
                                            var as = $('#maxi').elrte('val');
                                            $('.error.elrte-error').remove();
                                            $('.error.author-error').remove();
                                            $('.error.noborder').remove();
                                            $('#maxi').parent('div').removeClass('error');
                                            if (as.length == 0){
                                    // alert(1);
                                    $('#maxi').parent('div').addClass('error');
                                            $('.elrte-wrapper').after('<span class="error elrte-error" style="display:block;" >Article description is required. </span>');
                                            checkvalid = 0;
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
                                    if (($('#authortype').val() != '') && ($('#authortype').val() != '1') && $('#simpleSelectBox1').val() == '') {
                                    //alert('Please Select Author Name');
                                    $('#simpleSelectBox1').after('<span class="error author-error">Author name is required.</span>');
                                            $('#simpleSelectBox1').siblings('div').addClass('error');
                                            checkvalid = 0;
                                    }
//                                    if ($('#selectBoxFilter2').val() == '')
//                                    {
//                                    alert('Please Select Category from DropDown');
//                                            $('#selectBoxFilter2').focus();
//                                             checkvalid=0;
//                                    }
//                                    


                                    $("#fileupload").validate({


                                    errorElement: "span",
                                            errorClass: "error",
                                            //$("#pageSubmit").onclick: true,
                                            onclick: true,
                                            invalidHandler: function(event, validator) {

                                            for (var i in validator.errorMap) { ///alert(i);

                                            if ($('#' + i).hasClass('formattedelement')){
                                            $('#' + i).siblings('.formattedelement').addClass('error');
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
                                                    required: true,
                                                            rangelength: [10, 200]
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
                                            if (!$("#fileupload").valid())
                                            checkvalid = 0;
                                            if (checkvalid == 0){
                                    $('#submitsection').prepend('<div class="error noborder">An error has occured. Please check the above form.</div>');
                                            return false;
                                    }
                                    // else
                                    // $("#fileupload").submit();

                                    }
                            $('select.formattedelement').change(function(){
                            if ($(this).val().trim() != '')
                                    $(this).siblings('.formattedelement').removeClass('error');
                                    $(this).siblings('span.error').remove();
                            });
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
                            "title" : "Topics And Location",
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
                                    @if (in_array('12', Session::get('user_rights')))
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
                <a href="/dashboard">
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
        <h2><small>New Article</small></h2>
        <h3><small>{{ $userTup->name or '' }}</small></h3>
    </header>
    {!! Form::open(array('url'=>'article/','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
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

    <div class="container-fluid">
        <div class="form-legend" id="Author-Detail">Author Detail

        </div>
        <div id="Simple_Select_Box" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="simpleSelectBox">Post As</label>
            </div>
            <div class="span9">
                <div class="controls">

                    {!! Form::select('authortype',['' => 'Please Select'] + $p1,null,
                    ['class' => 'form-control formattedelement','id' =>'authortype' ]) !!}

                </div>
            </div>
            <script>
                                //simpleSelectAuthor
                                $().ready(function(){
                        $("#authortype").select2({
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
                        <option value="{{ $events->event_id }}">{{ $events->title }}</option>
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
                <div id="existing" class="tab-pane fade active in">

                    <div id="Simple_Select_Box" class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="simpleSelectBox">Author Name</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <select style="display: none;" name="author_id1" id="simpleSelectBox1">
                                    <option selected="" value="">Please Select</option>
                                </select>
                            </div>
                        </div>
                        <script>
                                            $().ready(function(){
                                    $("#simpleSelectBox1").select2({
                                    //dropdownCssClass: 'noSearch'
                                    });
                                    });</script>
                    </div>

                    <div id="n2" class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="simpleSelectBox">Secondary Author Name 1</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <select style="display: none;" name="author_id2" id="simpleSelectBox2">
                                    <option selected="" value="">Please Select</option>
                                </select>
                            </div>
                        </div>
                        <script>
                                            $().ready(function(){
                                    $("#simpleSelectBox2").select2({
                                    //dropdownCssClass: 'noSearch'
                                    });
                                    });</script>
                    </div>

                    <div id="n3" class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="simpleSelectBox">Secondary Author Name 2</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <select style="display: none;" name="author_id3" id="simpleSelectBox3">
                                    <option selected="" value="">Please Select</option>
                                </select>
                            </div>
                        </div>
                        <script>
                                            $().ready(function(){
                                    $("#simpleSelectBox3").select2({
                                    //dropdownCssClass: 'noSearch'
                                    });
                                    });</script>
                    </div>

                    <script>
                                        $(document).ready(function(e) {
                                $("#n2,#n3").hide();
                                });</script>

                    <script type="text/javascript">

                                        $(document).ready(function(){

                                $("#authortype").change(function(){

                                $(this).find("option:selected").each(function(){

                                if ($(this).attr("value") == "2" || $(this).attr("value") == "3"){
                                //If chose BWReporters - get New&Existing DDs
                                //Populate DDs
                                $("#tabarea").show();
                                        $("#n2, #n3").show();
                                        $.get("{{ url('/article/authordd/')}}",
                                        { option: $(this).attr("value") },
                                                function(data) {
                                                var simpleSelectBox1 = $('#simpleSelectBox1');
                                                        var simpleSelectBox2 = $('#simpleSelectBox2');
                                                        var simpleSelectBox3 = $('#simpleSelectBox3');
                                                        simpleSelectBox1.empty();
                                                        simpleSelectBox2.empty();
                                                        simpleSelectBox3.empty();
                                                        simpleSelectBox1.append("<option selected='' value=''>Please Select</option>");
                                                        simpleSelectBox2.append("<option selected='' value=''>Please Select</option>");
                                                        simpleSelectBox3.append("<option selected='' value=''>Please Select</option>");
                                                        $.each(data, function(index, element) {
                                                        simpleSelectBox1.append("<option value='" + element + "'>" + index + "</option>");
                                                                simpleSelectBox2.append("<option value='" + element + "'>" + index + "</option>");
                                                                simpleSelectBox3.append("<option value='" + element + "'>" + index + "</option>");
                                                        });
                                                        $("#simpleSelectBox1").select2();
                                                        $("#simpleSelectBox2").select2();
                                                        $("#simpleSelectBox3").select2();
                                                });
                                }

                                else if ($(this).attr("value") == "1"){

                                $("#tabarea").hide();
                                } else {

                                if ($(this).attr("value") != "") {
                                $("#tabarea").show();
                                        $("#n2, #n3").hide();
                                        $.get("{{ url('/article/authordd/')}}",
                                        {option: $(this).attr("value")},
                                                function (data) {
                                                var simpleSelectBox1 = $('#simpleSelectBox1');
                                                        simpleSelectBox1.empty();
                                                        simpleSelectBox1.append("<option selected='' value=''>Please Select</option>");
                                                        $.each(data, function (index, element) {
                                                        simpleSelectBox1.append("<option value='" + element + "'>" + index + "</option>");
                                                        });
                                                        $("#simpleSelectBox1").select2();
                                                });
                                }
                                }
                                if ($(this).attr("value") == "6"){
                                $("#tabarea").show();
                                        $("#n2, #n3").hide();
                                        var simpleSelectBox1 = $('#simpleSelectBox1');
                                        $('#event_top_div').show();
                                        $('#event_bottom_div').hide();
                                        $.get("{{ url('/article/speaker/')}}",
                                        {option: $('#event_id_author').attr("value")},
                                                function (data) {
                                                var simpleSelectBox1 = $('#simpleSelectBox1');
                                                        simpleSelectBox1.empty();
                                                        simpleSelectBox1.append("<option selected='' value=''>Please Select</option>");
                                                        $.each(data, function (index, element) {
                                                        simpleSelectBox1.append("<option value='" + element + "'>" + index + "</option>");
                                                        });
                                                        $("#simpleSelectBox1").select2();
                                                });
                                }
                                else{
                                $('#event_top_div').hide();
                                        $('#event_bottom_div').show();
                                }
                                });
                                }).change();
                               
                                $("#event_id_author").change(function(){
                                if ($("#authortype").val() == "6"){
                                        $.get("{{ url('/article/speaker/')}}",
                                        {option: $('#event_id_author').attr("value")},
                                                function (data) {
                                                var simpleSelectBox1 = $('#simpleSelectBox1');
                                                        simpleSelectBox1.empty();
                                                        simpleSelectBox1.append("<option selected='' value=''>Please Select</option>");
                                                        $.each(data, function (index, element) {
                                                        simpleSelectBox1.append("<option value='" + element + "'>" + index + "</option>");
                                                        });
                                                        $("#simpleSelectBox1").select2();
                                                });
                                }
                                });
                                        $('#simpleSelectBox1').change(function(){
                                var val1 = $(this).val();
                                        if (val1.trim()){
                                $('#simpleSelectBox2').find('option').each(function() {
                                if ($(this).val() == val1) {
                                $(this).attr("disabled", "disabled");
                                        //  $('#test').html($(this).text());
                                } else{
                                $(this).removeAttr('disabled');
                                }
                                $('#simpleSelectBox2').select2();
                                        //alert('done');
                                });
                                }
                                })
                                });</script>

                </div>

                <div id="new" class="tab-pane fade entypo ">
                    <!--<form name="addAuthorForm" method="post" enctype="multipart/form-data" id="addAuthorForm">-->
                    <!--                    <div class="control-group row-fluid">
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
                                                    <input  type="radio"  id="author_type" name="author_type" class="uniformRadio" value="3">
                                                    Guest Author
                                                </label>
                                            </div>
                                            <div class="span3">
                                                <label class="radio">
                                                    <input  type="radio"  id="author_type" name="author_type" class="uniformRadio" value="2" checked>
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
                                                    <input id="inputField" name="mobile" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="File_Upload" class="control-group row-fluid">
                                            <div class="span3">
                                                <label class="control-label">Photo</label>
                                            </div>
                                            <div class="span9 ">
                                                <div class="controls authorimagespn">
                                                    
                                                </div>
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="input-append">
                                                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div>
                                                        <span class="btn btn-file  authorimagespn">
                                                            <span class="fileupload-new">Select file</span>
                                                            <span class="fileupload-exists">Change</span>
                                                           <input type="file" name="photo" id="photo">
                                                            </span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
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
                    
                                        <div  class="control-group row-fluid" id="ch-reporter" style="display:none;">
                                            <div class="span3">
                                                <label class="control-label" for="selectBoxFilter">Choose Column</label>
                                            </div>
                                            <div class="span9">
                                                <div class="controls">
                                                    <select name="column_id" id="selectBoxFilter221">
                                                        <option value="" selected>Please Select</option>
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
                                                <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:7%; display:none; margin-left:15px;float:right;"/>
                                            </div>
                                        </div>-->
                    <!--</form>-->
                </div>
                <script>
                                    // magic.js
                                    $(document).ready(function() {
                            $(".uniformRadio").uniform({
                            radioClass: 'uniformRadio'
                            });
                                    //var csrf_token = $('meta[name="csrf-token"]').attr('content');
                                    var token = $('input[name=_token]');
                                    // process the form
                                    $("#addabut").click(function(){
                            //$("#addAuthorForm").on('click',function(event){}
                            if (validateAuthorData()){


                            //return false;
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
                                    //alert(1);
                                    //console.log(formData);
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
                                            beforeSend  :function(data){
                                            $('#addabut').hide();
                                                    $('#addabut').siblings('img').show();
                                            },
                                            success     :  function(data){
                                            if (data.status == 'success'){
                                            alert('Author saved');
                                                    $("#new input[type='text']").val('');
                                                    $("#new input[type='email']").val('');
                                                    $('#new textarea').val('');
                                                    $('input[name=photo]').remove();
                                                    $('.authorimagespn').append('<input type="file" name="photo" id="photo">');
                                                    $('#iconsTab').find('li').eq(0).find('a').trigger('click')
                                            } else{
                                            $('#addabut').before(errorMessage(data.msg));
                                            }
                                            },
                                            complete    :function(data){
                                            $('#addabut').show();
                                                    $('#addabut').siblings('img').hide();
                                            },
                                            //encode      : true,
                                            headers: {
                                            'X-CSRF-TOKEN': token.val()
                                            }
                                    });
                            }



                            });
                                    // alert()
                                    $('input[name=author_type]').click(function(){
                            if ($('input[name=author_type]:checked').val() == 4){
                            $("#ch-reporter").show();
                            } else{
                            $("#ch-reporter").hide();
                            }
                            });
                                    // check status on document ready
                                    if ($('input[name=author_type]:checked').val() == 4){
                            $("#ch-reporter").show();
                            } else{
                            $("#ch-reporter").hide();
                            }

                            });
                                    function validateAuthorData(){
                                    var valid = 1;
                                            $('.author_error').remove();
                                            $('#new input').removeClass('error');
                                            $('#new textarea').removeClass('error');
                                            if ($('input[name=Name]').val().trim() == 0){
                                    valid = 0;
                                            $('input[name=Name]').addClass('error');
                                            $('input[name=Name]').after(errorMessage('Please enter name'));
                                    }
                                    if ($('textarea[name=Bio]').val().trim() == 0){
                                    valid = 0;
                                            $('textarea[name=Bio]').addClass('error');
                                            $('textarea[name=Bio]').after(errorMessage('Please enter bio'));
                                    }
                                    if ($('input[name=email]').val().trim() == 0){
                                    valid = 0;
                                            $('input[name=email]').addClass('error');
                                            $('input[name=email]').after(errorMessage('Please enter email'));
                                    } else{

                                    if (!(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test($('input[name=email]').val()))){
                                    valid = 0;
                                            $('input[name=email]').addClass('error');
                                            $('input[name=email]').after(errorMessage('Please enter vaild email'));
                                    }
                                    }
                                    if ($('input[name=mobile]').val().trim() == 0){
                                    valid = 0;
                                            $('input[name=mobile]').addClass('error');
                                            $('input[name=mobile]').after(errorMessage('Please enter mobile'));
                                    } else {
                                    var regex = /^(\d{1,3}[- ]?)?\d{10}$/;
                                            if (!regex.test($('input[name=mobile]').val())){
                                    valid = 0;
                                            $('input[name=mobile]').addClass('error');
                                            $('input[name=mobile]').after(errorMessage('Please enter valid mobile'));
                                    }
                                    }
                                    if ($('input[name=photo]').val().trim() == 0){
                                    valid = 0;
                                            $('input[name=photo]').addClass('error');
                                            $('.authorimagespn').after(errorMessage('Please select photo'));
                                    }
                                    //alert(valid);
                                    if (valid == 0)
                                            return false;
                                            else
                                            return true;
                                    }
                            function errorMessage($msg){
                            return '<span class="error author_error">' + $msg + '</span>';
                            }
                </script>
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
                        @foreach($channels as $channel)
                        <option value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
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
                                        eventBox.append("<option value=''>Please Select</option>");
                                        $.each(data, function(index, element) {
                                        eventBox.append("<option value='" + element + "'>" + index + "</option>");
                                        eventBoxTop.append("<option value='" + element + "'>" + index + "</option>");
                                        });
                                        $("#event_id").select2();
                                        $('#event_id_author').select2();
                                        if($('#authortype').val()=="6"){
                                            $( "#event_id_author" ).trigger("change");
                                        }
                                        
                                });
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
                                $.get("{{ url('article/magazine')}}",
                                { option: $(this).attr("value") },
                                        function(data) {
                                        var Box = $('#magazine_id');
                                                Box.empty();
                                                Box.append("<option  value=''>Please Select</option>");
                                                $.each(data, function(index, element) {
                                                Box.append("<option value='" + element + "'>" + index + "</option>");
                                                });
                                                $("#magazine_id").select2();
                                        });
                                $.get("{{ url('article/dropdown1')}}",
                                { option: $(this).attr("value") + '&level=' },
                                        function(data) {
                                        var Box = $('#category1');
                                                Box.empty();
                                                Box.append("<option value=''>Please Select</option>");
                                                $.each(data, function(index, element) {
                                                Box.append("<option value='" + element + "'>" + index + "</option>");
                                                });
                                                $("#category1").select2();
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

                    <input id="ifyes" type="radio" name="canonical_options" class="uniformRadio" value="1">

                    Yes

                </label>

                <!-- <div class="controls">
                     <input type="radio" value="1" name="canonical_options" id="ifyes" />Yes
                     <input type="radio"  value="0"name="canonical_options" id="ifno" checked/>No
                 </div>-->
            </div>
            <div class="span3">
                <label class="radio">

                    <input id="ifno" checked="checked" type="radio" name="canonical_options" class="uniformRadio" value="0">

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
                        <input type="text" name="canonical_url" id="canonical_url">
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
                    <textarea  name="title" rows="2" class="no-resize  title_range valid"></textarea>
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
                    <textarea  name="summary" rows="4" class=""></textarea>
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
                    <textarea name="description" id="maxi" rows="2" class="auto-resize required formattedtextareat"></textarea>
                    <span for="description" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                    <script>

                                        elRTE.prototype.options.panels.web2pyPanel = [
                                                'pastetext', 'bold', 'italic', 'underline', 'justifyleft', 'justifyright',
                                                'justifycenter', 'justifyfull', 'forecolor', 'hilitecolor', 'fontsize', 'link',
                                                'image', 'insertorderedlist', 'insertunorderedlist'];
                                        elRTE.prototype.options.toolbars.web2pyToolbar = ['web2pyPanel', 'tables'];
                                        $('#maxi').elrte({
                                lang: "en",
                                        styleWithCSS: false,
                                        height: 200,
                                        toolbar: 'web2pyToolbar'
                                });
                                        $(document).ready(function() {

                                $("#canonical").addClass("none");
                                        $(':radio[id=ifno]').change(function() {
                                $("#canonical").addClass("none");
                                });
                                        $(':radio[id=ifyes]').change(function() {
                                $("#canonical").removeClass("none");
                                });
                                });</script>
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
                    <textarea  name="social_title" rows="2" class="no-resize  title_range valid"></textarea>
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
                    <textarea  name="social_summary" rows="2" class=""></textarea>
                </div>
            </div>
        </div>
        <!--Text Area Resizable end-->

        <!--        <div id="file_upload_social" class="control-group row-fluid">
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

        <div class="form-legend" id="topics-location">Topics And Location</div>
        <!--Topics begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="Ltopics" style="width:100%;">Topics</label>
                <button type="button" name="genTopic" id="genTopic" class="btn btn-mini btn-inverse" style="margin-left:15px; display:block;">Generate Topics</button>
                <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:none; margin-left:15px;"/>
            </div>
            <div class="span9">
                <div class="controls ltopicsparentdiv">
                    <select multiple name="Ltopics[]" id="Ltopics">
                    </select>
                </div>
            </div>
            <script>
                                $().ready(function(){
                        $("#Ltopics").pickList();
                        });
                                $("#genTopic").click(function(){
                        var token = $('input[name=_token]');
                                //alert($('#maxi').elrte('val'));
                                // process the form
                                $.ajax({
                                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                                        url         : '/article/generateTopics', // the url where we want to POST
                                        data        :  { detail : $('#maxi').elrte('val') },
                                        dataType    : 'json', // what type of data do we expect back from the server
                                        encode      : true,
                                        beforeSend  :function(data){
                                        $('#genTopic').hide();
                                                $('#genTopic').siblings('img').show();
                                        },
                                        complete    :function(data){
                                        $('#genTopic').show();
                                                $('#genTopic').siblings('img').hide();
                                        },
                                        success     :  function(data){
                                        var resplen = (data).length;
                                                //alert(resplen);
                                                var selectedarray = new Array();
                                                $('.ltopicsparentdiv').find("#Ltopics option:selected").each(function(){
                                        selectedarray.push($(this).val());
                                        });
                                                var dataoption = '<select multiple name="Ltopics[]" id="Ltopics">';
                                                var selectedop = '';
                                                $.each(data, function(index, element) {
                                                selectedop = '';
                                                        if (selectedarray.indexOf(element.id) >= 0)
                                                        selectedop = 'selected';
                                                        dataoption += "<option " + selectedop + " value='" + element.id + "'>" + element.topic + "</option>";
                                                });
                                                dataoption += '</select>';
                                                $(".ltopicsparentdiv").html(dataoption);
                                                $("#Ltopics").pickList();
                                                //$("#dualMulti").pickList();
                                        },
                                        headers: {
                                        'X-CSRF-TOKEN': token.val()
                                        }
                                })
                                // using the done promise callback
                                .done(function(data) {
                                // log data to the console so we can see
                                //console.log(data);
                                //alert(data);
                                //alert('Topic Populated');
                                // here we will handle errors and validation messages
                                });
                                // stop the form from submitting the normal way and refreshing the page
                                //event.preventDefault();
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
                        <option value="{{ $countrye->country_id }}">{{ $countrye->name }}</option>
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
                            <option value="{{ $state->state_id}}">{{ $state->name }}</option>		
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
                    <select name="newstype" id="simpleSelectBox">
                        @foreach($newstype as $newstypE)
                        <option value="{{ $newstypE->news_type_id }}"> {{ $newstypE->name }} </option>
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
                    <select name="category1" id="category1" class="formattedelement">
                        <option  value="">Please Select</option>
                        @foreach($category as $key )
                        <option value="{{ $key->category_id }}">{{ $key->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                                $(document).ready(function(){
                        $("#category1").select2();
                                $('#category1').change(function(){
                        $.get("{{ url('article/dropdown1')}}",
                        { option: $(this).attr("value") + '&level=_two' },
                                function(data) {
                                var selectBoxFilter3 = $('#selectBoxFilter3');
                                        selectBoxFilter3.empty();
                                        selectBoxFilter3.append("<option value=''>Please Select</option>");
                                        $.each(data, function(index, element) {
                                        selectBoxFilter3.append("<option value='" + element + "'>" + index + "</option>");
                                        });
                                        $("#selectBoxFilter3").select2();
                                        $('#selectBoxFilter4').html("<option value=''>Please Select</option>");
                                        $('#selectBoxFilter4').select2();
                                        $('#selectBoxFilter5').html("<option value=''>Please Select</option>");
                                        $('#selectBoxFilter5').select2();
                                });
                                if ($(this).attr("value") == '{{config('constants.ee_rating_cateogy_id')}}'){
                        $('#start_rating_div').show();
                        } else{
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
                        <option  value="">Please Select</option>

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
                        <option  value="">Please Select</option>
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
                                        selectBoxFilter5.append("<option value=''>Please Select</option>");
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
                        <option  value="">Please Select</option>
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

    <div class="container-fluid" id="start_rating_div" style="display: none;">

        <div class="form-legend" id="start_rating">Rating</div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Rating Point</label>
            </div>
            <div class="span9">
                <div class="controls"><input type="text" id="rating_point" value="0.00" name="rating_point"></div>
            </div>
        </div>     
    </div>    
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
                        <option  value="">Please Select</option>
                        @foreach($magazine as $magazines)
                        <option value="{{ $magazines->magazine_id }}">{{ $magazines->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                                $().ready(function(){
                        $("#magazine_id").select2();
                                //$("#selectBoxFilter20").select2();
                        });</script>
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
                        <option  value="">Please Select</option>
                        @foreach($event as $events)
                        <option value="{{ $events->event_id }}">{{ $events->title }}</option>
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

        <div class="form-legend" id="assign-article-to-a-campaign">Assign this QuickByte to a Campaign</div>

        <!--Select Box with Filter Search begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="campaign">Campaign</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="campaign" id="campaign_id">
                        <option  value="">Please Select</option>
                        @foreach($campaign as $campaigns)
                        <option value="{{ $campaigns->campaign_id }}">{{ $campaigns->title }}</option>
                        @endforeach
                    </select>
                    <span for="campaign" generated="true" class="error" style="display: none;">Please enter a valid text.</span>

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
                        });</script>
        </div>                       
        <!--Select Box with Filter Search end-->
    </div>





    <div class="container-fluid">

        <div class="form-legend" id="photos-videos">Photos & Videos  
        </div>

        <!--Tabs begin-->

        <div  class="control-group row-fluid span-inset">
            <ul class="nav nav-tabs" id="myTab">
                <!--                <li class="dropdown active">
                                    
                                    <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">Upload Image<b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a data-toggle="tab" href="#dropdown1">Image 1</a></li>
                                          <li><a data-toggle="tab" href="#dropdown2">Image 2</a></li>
                                          <li><a data-toggle="tab" href="#dropdown3">Image 3</a></li>
                                          <li><a data-toggle="tab" href="#dropdown4">Image 4</a></li> 
                                    </ul>
                                </li>-->
                <li class="dropdown active"><a data-toggle="tab" href="#dropdown1">Photo</a></li>
                <li><a data-toggle="tab" href="#tab-example1">Video</a></li>
                <!--	<li><a data-toggle="tab" href="#tab-example4">Current Photos</a></li>-->
            </ul>
            <div class="tab-content">
                <div id="tab-example1" class="tab-pane fade">
                    <div class="control-group row-fluid"> 
                        <div class="span3">
                            <label class="radio">

                                <input id="embedcodevideo" checked="checked" type="radio" name="vodeo" class="uniformRadio" value="1">

                                Embed  Video Code

                            </label>

                        </div>
                        <div class="span3">
                            <label class="radio">

                                <input id="videoid" type="radio" name="vodeo" class="uniformRadio" value="1">

                                Video

                            </label>

                        </div>
                    </div>

                    <div id="embedcodevideodetails" >
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
                                <label class="control-label">  Embed Code (300/300)</label>
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
                    </div>
                    <div id="videocode">
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Video ID</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <input type="text" class="valid" name="video_Id" id="video_Id"/>
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
<!--                    <p>Browse recent related images : <button class="btn btn-success"  onclick = "document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block'" name="status" type="button">Browse</button>
                        

                    </p>-->


                    <!--                    <div class="control-group row-fluid">
                                            <div class="span3">
                                                <label class="control-label">Upload Image 1</label>
                                            </div>
                                            <div class="span9">
                                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                                    <div class="input-append">
                                                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span><input type="file" name="albumPhoto" id="albumPhoto"/></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
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
                    
                    
                                                <button class="btn btn-warning" type="button" id="addphotobutton" name="addphotobutton" style="display:block;">Submit</button>
                                                <img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
                                            </div>
                                        </div>-->
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="inputField">
                                Upload Photos<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Here You can add multiple photos by Drag and Drop or Simply By clicking and selecting  photos (Size: {{config('constants.dimension_article')}}) (File Size <= {{config('constants.maxfilesize').' '.config('constants.filesizein')}})."><i class="icon-photon info-circle"></i></a>
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
                            <input type="hidden" id="uploadedImages" name="uploadedImages">

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

            </div>
        </div>
        <!-- Uploaded Image and Video Ids -->

<!--        <input type="hidden" id="uploadedVideos" name="uploadedVideos[]">-->

        <label class="checkbox" >
            <input type="checkbox" name="hide_image" class="uniformCheckbox2" value="1">
            <a href="javascript:;">Do Not Show Images On Landing Page</a>
        </label>

    </div><!--end container-->
    <script>
                                        // magic.js
                                        $.fn.MessageBox = function (msg)
                                        {
                                        var formData = new FormData();
                                                formData.append('photoId', msg);
                                                var token = $('input[name=_token]');
                                                var rowID = 'row' + msg;
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
                                         var formData = new FormData();
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
                                         });   */
                                        // process the form - For Add Image in Album
                                        $("#addphotobutton").click(function(){
                                //$("#addAuthorForm").on('click',function(event){}
                                //  alert('Yay!');

                                // get the form data
                                // there are many ways to get this data using jQuery (you can use the class or id also)
                                var formData = new FormData();
                                        formData.append('albumphoto', albumPhoto.files[0]);
                                        formData.append('title', $('input[name=photoTitle]').val());
                                        formData.append('description', $('textarea[name=photoDesc]').val());
                                        formData.append('source', $('input[name=photoSource]').val());
                                        formData.append('sourceurl', $('input[name=photoSourceURL]').val());
                                        formData.append('active', $('input[name=photoEnabled]:checked').val());
                                        formData.append('channel_id', $('select[name=channel_sel]').val());
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
                                                        alert(theResponse);
                                                        //Assign returned ID to hidden array element
                                                        alert($('#uploadedImages').val());
                                                        var isthere = $('#uploadedImages').val();
                                                        var arrP = isthere.split(',');
                                                        arrP.push(theResponse);
                                                        var newval = arrP.join(',');
                                                        $('#uploadedImages').val(newval);
                                                        /*
                                                         $("#Taglist").append($('<option>', {
                                                         value: element.tags_id,
                                                         text: element.tag
                                                         }));
                                                         */
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
                                });
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
                                });</script>

    @if(in_array('12',Session::get('user_rights')))

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
                    <input type="text" name="datepicked" id="datepicker" class="span3" />
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
                    <input type="text" name="timepicked" id="timeEntry" class="span3" />
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
                                                    $.timeEntry.setDefaults({show24Hours: true, showSeconds: true});
                                                    $('#timeEntry').timeEntry().change();
                                            });</script> 
    </div>

    @endif


    <div class="container-fluid">

        <div class="control-group row-fluid">
            <div class="span12 span-inset">

                <label class="checkbox" >
                    <input type="checkbox" name="for_homepage" class="uniformCheckbox" value="checkbox1" checked >
                    <a href="#" target="_blank">Publish this to Home Page.</a>
                </label>
                <script>
                                                    $().ready(function(){
                                            $(".uniformCheckbox").uniform();
                                            });</script>

                <label class="checkbox" >
                    <input type="checkbox" name="important" class="uniformCheckbox2" value="checkbox1">
                    <a href="#" target="_blank">This article is important.</a>
                </label>
                <script>
                                                    $().ready(function(){
                                            $(".uniformCheckbox2").uniform();
                                            });</script>

                <label class="checkbox" >
                    <input type="checkbox" name="web_exclusive" class="uniformCheckbox3" value="checkbox1">
                    <a href="#" target="_blank">Web Exclusive.</a>
                </label>
                <script>
                                                    $().ready(function(){
                                            $(".uniformCheckbox3").uniform();
                                            });</script>


            </div>
        </div>

        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <button type="submit" name="status" value="S" id="draftSubmit" class="btn btn-default">Draft</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
                <button type="submit" name="status" value="N" id="pageSubmit" name="N" class="btn btn-warning">Submit</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
                <!--<button type="button" name="N" class="btn btn-info">Save</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>-->
                @if(in_array('12',Session::get('user_rights')))
                <button type="submit" name="status" value="P" id="publishSubmit" class="btn btn-success">Publish</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
                @endif

                <button type="reset" name="status" value="D" id="dumpSubmit" class="btn btn-danger">Dump</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>

            </div>
        </div>
    </div>
    <!--	end container-->
    {!! Form::close() !!}
    <script>
                                        function editImageDetail(){
//                   alert(1);
//                          BootstrapDialog.alert('I want banana!');

                                        /* BootstrapDialog.show({
                                         title: 'Manipulating Buttons',
                                         message: function(dialog) {
                                         var $content = $('<div><input typ="text" name="imagetitle" id="imagetitle" /><button class="btn btn-success">Revert button status right now.</button></div>');
                                         var $footerButton = dialog.getButton('btn-1');
                                         $content.find('button').click({$footerButton: $footerButton}, function(event) {
                                         event.data.$footerButton.enable();
                                         event.data.$footerButton.stopSpin();
                                         dialog.setClosable(true);
                                         });
                                         
                                         return $content;
                                         },
                                         buttons: [{
                                         id: 'btn-1',
                                         label: 'Click to disable and spin.',
                                         action: function(dialog) {
                                         var $button = this; // 'this' here is a jQuery object that wrapping the <button> DOM element.
                                         $button.disable();
                                         $button.spin();
                                         dialog.setClosable(false);
                                         }
                                         }]
                                         }); */

                                        BootstrapDialog.show({
                                        message: $('<div class="shekhartest"></div>').load('http://localhost:8080/login.html')
                                        });
                                        }
                                //98768 april-4  98769 feb -29
    </script>
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
                                $('#fileupload').fileupload({
                                // Uncomment the following to send cross-domain cookies:
                                //xhrFields: {withCredentials: true},
                                url: '<?php echo url('article/image/upload') ?>',
                                        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                                        maxFileSize: 10000000
                                });
                                        $('.authorimagespn').append('<input type="file" name="photo" id="photo">');
                                });
                                        $('#fileupload').bind('fileuploaddone', function (e, data) {
                                //console.log(e);
                                var dataa = JSON.parse(data.jqXHR.responseText);
                                        //console.log(dataa['files']['0']['name']);
                                        $.each(dataa['files'], function(index, element) {
                                        //console.log(element.name);
                                        if ($('#uploadedImages').val().trim())
                                                $('#uploadedImages').val($('#uploadedImages').val() + ',' + element.name);
                                                else
                                                $('#uploadedImages').val(element.name);
                                        });
                                });
                                        $('#fileupload').bind('fileuploaddestroyed', function (e, data) {
                                // console.log(data);
                                var file = getArg(data.url, 'file');
                                        var images = $('#uploadedImages').val().split(',');
                                        images.splice(images.indexOf(file), 1);
                                        $('#uploadedImages').val(images.join());
                                        //$('#imagesname').val($('#imagesname').val().replace(','+));

                                });
                                        function getArg(url, name){
                                        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
                                                if (results == null){
                                        return null;
                                        }
                                        else{
                                        return results[1] || 0;
                                        }
                                        }


</script>

@stop