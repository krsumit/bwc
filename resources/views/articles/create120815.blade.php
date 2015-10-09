@extends('layouts/master')

@section('title', 'Create Article - BWCMS')


@section('content')


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
                    $("#validation_form").validate({
                        errorElement: "span",
                        errorClass: "error",
                        onclick: true,
                        rules: {
                            "req": {
                                required: true
                            },
                            "numbers": {
                                required: false,
                                digits: true
                            },
                            "numbers_range": {
                                range: [1, 7]
                            },
                            "email": {
                                email: true
                            },
                            "url": {
                                url: true
                            },
                            "date": {
                                date: true
                            }
                        }
                    });
                });
            </script>

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
                                {
                                    "data" : {
                                        "title" : "Schedule for Upload",
                                        "attr" : { "href" : "#schedule-for-upload" }
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
            <h2><small>Article: 45345</small></h2>
            <h3><small>Dr Ratan Bhattacharjee</small></h3>
        </header>
        {!! Form::open(array('url'=>'author/addAuthor','class'=> 'form-horizontal')) !!}
        {!! csrf_field() !!}
        <div class="container-fluid">

            <div class="form-legend" id="Notifications">Notifications</div>

            <!--Notifications begin-->
            <div class="control-group row-fluid">
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
            <div class="form-legend" id="Author-Detail">Author Detail

            </div>
            <div id="Simple_Select_Box" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="simpleSelectBox">Post As</label>
                </div>
                <div class="span9">
                    <div class="controls">

                        {!! Form::select('authortype',['' => '--Please Select--'] + $p1,
                                                           ['class' => 'form-control'],['id' =>'simpleSelectAuthor' ]) !!}
                                <!--<select style="display: none;" name="simpleSelectBox" id="simpleSelectBox1">
									   <option selected="" value="All">--Please Select--</option>
									    @foreach($postAs as $postas1)
                                <option value="{{ $postas1->author_type_id }}">{{ $postas1->label }}</option>
					                    @endforeach
                                </select>-->
                    </div>
                </div>
                <script>
                    $().ready(function(){
                        $("#simpleSelectAuthor").select2({
                            dropdownCssClass: 'noSearch'
                        });
                    });
                </script>
            </div>


            <div class="bs-docs-example" id="tabarea">
                <ul class="nav nav-tabs" id="iconsTab">
                    <li class="active"><a data-toggle="tab" href="#existing">Choose From Existing</a></li>
                    <li class=""><a data-toggle="tab" href="#new">Add A New Author</a></li>
                </ul>
                <div class="tab-content">
                    <div id="existing" class="tab-pane fade iconic active in">

                        <div id="Simple_Select_Box" class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label" for="simpleSelectBox">Author Name</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <select style="display: none;" name="simpleSelectBox" id="simpleSelectBox1">
                                        <option selected="" value="">--Please Select--</option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
                                    $("#simpleSelectBox1").select2({
                                        //dropdownCssClass: 'noSearch'
                                    });
                                });
                            </script>
                        </div>

                        <div id="n2" class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label" for="simpleSelectBox">Secondary Author Name 1</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <select style="display: none;" name="simpleSelectBox" id="simpleSelectBox2">
                                        <option selected="" value="">--Please Select--</option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
                                    $("#simpleSelectBox2").select2({
                                        //dropdownCssClass: 'noSearch'
                                    });
                                });
                            </script>
                        </div>

                        <div id="n3" class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label" for="simpleSelectBox">Secondary Author Name 2</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <select style="display: none;" name="simpleSelectBox" id="simpleSelectBox3">
                                        <option selected="" value="">--Please Select--</option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $().ready(function(){
                                    $("#simpleSelectBox3").select2({
                                        //dropdownCssClass: 'noSearch'
                                    });
                                });
                            </script>
                        </div>

                        <script>
                            $(document).ready(function(e) {
                                $("#n2,#n3").hide();

                            });
                        </script>

                        <script type="text/javascript">

                            $(document).ready(function(){

                                $("#simpleSelectAuthor").change(function(){

                                    $(this).find("option:selected").each(function(){

                                        if($(this).attr("value")=="2"){
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
                                                        simpleSelectBox1.append("<option selected='' value=''>All</option>");
                                                        simpleSelectBox2.append("<option selected='' value=''>All</option>");
                                                        simpleSelectBox3.append("<option selected='' value=''>All</option>");
                                                        $.each(data, function(index, element) {
                                                            simpleSelectBox1.append("<option value='"+ element +"'>"+ index +"</option>");
                                                            simpleSelectBox2.append("<option value='"+ element +"'>"+ index +"</option>");
                                                            simpleSelectBox3.append("<option value='"+ element +"'>"+ index +"</option>");
                                                        });
                                                    });

                                        }

                                        else if($(this).attr("value")=="1"){

                                            $("#tabarea").hide();
                                        }
//if($(this).attr("value")=="none")
                                        else {
                                            if ($(this).attr("value") != "") {
                                                $("#tabarea").show();
                                                $("#n2, #n3").hide();
                                                $.get("{{ url('/article/authordd/')}}",
                                                        {option: $(this).attr("value")},
                                                        function (data) {
                                                            var simpleSelectBox1 = $('#simpleSelectBox1');
                                                            simpleSelectBox1.empty();
                                                            simpleSelectBox1.append("<option selected='' value=''>All</option>");
                                                            $.each(data, function (index, element) {
                                                                simpleSelectBox1.append("<option value='" + element + "'>" + index + "</option>");
                                                            });
                                                        });
                                            }
                                        }
                                    });

                                }).change();

                            });

                        </script>

                    </div>

                    <div id="new" class="tab-pane fade entypo ">
                        <form name="addAuthorForm" method="post" enctype="multipart/form-data" id="addAuthorForm">
                            <div class="control-group row-fluid">
                                <div class="span3">
                                    <label class="control-label" >&nbsp;</label>
                                </div>
                                <div class="span3">
                                    <label class="radio">
                                        <input type="radio" name="author_type" class="uniformRadio" value="4">
                                        Columnist
                                    </label>
                                </div>
                                <div class="span3">
                                    <label class="radio">
                                        <input  type="radio" name="author_type" class="uniformRadio" value="3">
                                        Guest Author
                                    </label>
                                </div>
                                <div class="span3">
                                    <label class="radio">
                                        <input  type="radio" name="author_type" class="uniformRadio" value="2" checked>
                                        BW Reporter
                                    </label>
                                </div>

                                <script>
                                    $().ready(function(){
                                        $(".uniformRadio").uniform({
                                            radioClass: 'uniformRadio'
                                        });

                                    });
                                </script>

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
                                            <option value="" selected>None</option>
                                            @foreach( $columns as $column)
                                                <option value="{{ $column->column_id }}">{{ $column->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <script>
                                    $().ready(function(){
                                        $("#selectBoxFilter221").select2();
                                    });
                                </script>
                            </div>

                            <div class="control-group row-fluid">
                                <div class="span12 span-inset">
                                    <button class="btn btn-warning pull-right" id="addabut" type="button" style="display:block;">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <script>
                        // magic.js
                        $(document).ready(function() {

                            //var csrf_token = $('meta[name="csrf-token"]').attr('content');
                            var token = $('input[name=_token]');
                            // process the form
                            $("#addabut").click(function(){
                                //$("#addAuthorForm").on('click',function(event){}
                                //  alert('Yay!');
                                // get the form data
                                //var formData = new FormData($("#addAuthorForm")[0]);
                                /*var fileDta = new FormData();
                                 fileDta.append('file', jQuery('#author_photo')[0].files);
                                 alert(fileDta['file']);*/
                                //var fileDta = $('#author_photo').prop('files');
                                //alert($('#author_photo').prop('files'));
                                /*jQuery.each(jQuery('#author_photo')[0].files, function(i, file){
                                 fileDta.append('file-'+i, file);
                                 })

                                 /*    var formData1 = {

                                 name : $('input[name=Name]').val(),
                                 author_type       : $('input[name=author_type]:checked').val(),
                                 email             : $('input[name=email]').val(),
                                 bio               : $('textarea[id=Bio]').val(),
                                 mobile            : $('input[name=mobile]').val(),
                                 photo             : $('input[name=photo]').val(),
                                 twitter           : $('input[name=twitter]').val(),
                                 column_id         : $('select[name=column_id]').val()

                                 }; */
                                // there are many ways to get this data using jQuery (you can use the class or id also)
                                var formData = new FormData();
                                formData.append('photo', photo.files[0]);
                                formData.append('name', $('input[name=Name]').val());
                                formData.append('author_type',$('input[name=author_type]:checked').val());
                                formData.append('email', $('input[name=email]').val());
                                formData.append('bio', $('textarea[id=Bio]').val());
                                formData.append('mobile', $('input[name=mobile]').val());
                                formData.append('twitter', $('input[name=twitter]').val());
                                formData.append('column_id', $('select[name=column_id]').val());
                                //  alert(formData);
                                //alert($('input[name=author_type]:checked').val());
                                // process the form
                                $.ajax({
                                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                                    //method      : 'POST',
                                    url         : '/article/addAuthor', // the url where we want to POST
                                    //files       :  true,
                                    data        :  formData,
                                    enctype     : 'multipart/form-data',
                                    /*
                                     data       :   {name : $('input[name=Name]').val(),
                                     author_type       : $('input[name=author_type]:checked').val(),
                                     email             : $('input[name=email]').val(),
                                     bio               : $('textarea[id=Bio]').val(),
                                     mobile            : $('input[name=mobile]').val(),
                                     photo             : fileDta,
                                     twitter           : $('input[name=twitter]').val(),
                                     column_id         : $('select[name=column_id]').val()
                                     },
                                     */
                                    //: formData, // our data object
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

                        });
                    </script>
                </div>
            </div>
        </div><!-- end container1 -->

        <div class="container-fluid">

            <div class="form-legend" id="Channel">Channel</div>

            <!--Select Box with Filter Search begin-->
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Channel</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="channel_sel" id="selectBoxFilter20">
                            <option selected="" value="">-Select-</option>
                            @foreach($channels as $channel)
                                <option value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
                                @endforeach
                                        <!--
                                        <option value="Beige">Channel2</option>
                                        <option value="Black">Channel3</option>
									-->
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
                        <textarea  name="title" rows="4" class="no-resize"></textarea>
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
                    <label class="control-label">Description</label>
                </div>
                <div class="span9">
                    <div class="controls elrte-wrapper">
                        <textarea name="description" id="maxi" rows="2" class="auto-resize"></textarea>
                        <script>
                            $('#maxi').elrte({
                                lang: "en",
                                styleWithCSS: false,
                                height: 200,
                                toolbar: 'maxi'
                            });
                        </script>
                    </div>
                </div>
            </div>
            <!--WYSIWYG Editor - Full Options end-->

        </div><!-- end container1 -->
        <div class="container-fluid">

            <div class="form-legend" id="topics-location">Topics And Location</div>
            <!--Topics begin-->
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="dualMulti" style="width:100%;">Topics</label>
                    <button type="button" class="btn btn-mini btn-inverse" style="margin-left:15px; display:block;">Generate Topics</button>
                    <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:block; margin-left:15px;"/>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select multiple name="dualMulti" id="dualMulti">
                            <option selected="" value="Beigess">Beige</option>
                            <option value="Black">Black</option>
                            <option value="Blue">Blue</option>
                            <option value="Bronze">Bronze</option>
                            <option value="Brown">Brown</option>
                        </select>
                    </div>
                </div>
                <script>
                    $().ready(function(){
                        $("#dualMulti").pickList();
                    });
                </script>
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
                            <option selected="" value="">Country - All</option>
                            @foreach($country as $countrye)
                                <option value="{{ $countrye->country_id }}">{{ $countrye->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <script>
                    $(document).ready(function(){
                        $("#selectBoxFilter6").change(function(){
                            $(this).find("option:selected").each(function(){
                                if($(this).attr("value")=="1"){
                                    $("#tabState").show();
                                }else{
                                    $("#tabState").hide();
                                }

                            });

                        }).change();

                    });
                </script>
            </div>

            <div id="tabState" class="control-group row-fluid">
                <div id="Simple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" for="selectBoxFilter"></label>
                    </div>
                    <div class="span9" >
                        <div class="controls">
                            <select name="state" id="selectBoxFilter7">
                                <option selected="" value="">State - All</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->state_id}}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <script>
                        $().ready(function(){
                            $("#selectBoxFilter7").select2();
                        });
                    </script>
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
                                        <!--
                                        <option value="Black">Opinions</option>
                                        <option value="Blue">Imaginary/ Fiction/ Personal Experience</option>
                                        <option value="Bronze">Review/ Interview</option>
                                        <option value="Brown">None of the Above</option>
										-->
                        </select>
                    </div>
                </div>

                <script>
                    $().ready(function(){
                        $("#simpleSelectBox").select2({
                            dropdownCssClass: 'noSearch'
                        });
                    });
                </script>
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
                        <select name="category1" id="selectBoxFilter2">
                            <option selected="" value="">All</option>
                            @foreach($category as $key )
                                <option value="{{ $key->category_id }}">{{ $key->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <script>
                    $(document).ready(function(){
                        $('#selectBoxFilter2').change(function(){
                            $.get("{{ url('article/dropdown')}}",
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
                    <label class="control-label" for="selectBoxFilter"></label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="category2" id="selectBoxFilter3">
                            <option selected="" value="">All</option>

                        </select>
                    </div>
                </div>
                <script>
                    $(document).ready(function(){
                        $('#selectBoxFilter3').change(function(){
                            $.get("{{ url('article/dropdown')}}",
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
                    <label class="control-label" for="selectBoxFilter"></label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="category3" id="selectBoxFilter4">
                            <option selected="" value="">All</option>
                        </select>
                    </div>
                </div>
                <script>
                    $(document).ready(function(){
                        $('#selectBoxFilter4').change(function(){
                            $.get("{{ url('article/dropdown')}}",
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
                    <label class="control-label" for="selectBoxFilter"></label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="category4" id="selectBoxFilter5">
                            <option selected="" value="">All</option>
                        </select>
                    </div>
                </div>
                <script>
                    $().ready(function(){
                        //$("#selectBoxFilter5").select2();
                    });
                </script>
            </div>
            <!--Select Box with Filter Search end-->

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
                        <select name="magazine" id="selectBoxFilter20">
                            <option selected="" value="">All</option>
                            @foreach($magazine as $magazines)
                                <option value="{{ $magazines->magazine_id }}">{{ $magazines->title }}</option>
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

            <div class="form-legend" id="assign-article-to-a-event">Assign This Article To An Event</div>

            <!--Select Box with Filter Search begin-->
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Event Name</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="event" id="selectBoxFilter20">
                            <option selected="" value="">All</option>
                            @foreach($event as $events)
                                <option value="{{ $events->event_id }}">{{ $events->title }}</option>
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

            <div class="form-legend" id="assign-article-to-a-campaign">Assign this article to a Campaign</div>

            <!--Select Box with Filter Search begin-->
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Campaign</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="campaign" id="selectBoxFilter">
                            <option selected="" value="">All</option>
                            @foreach($campaign as $campaigns)
                                <option value="{{ $campaigns->campaign_id }}">{{ $campaigns->title }}</option>
                            @endforeach
                        </select>
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
                        $("#selectBoxFilter").select2();
                    });
                </script>
            </div>
            <!--Select Box with Filter Search end-->

        </div><!--end container-->

        <div class="container-fluid">

            <div class="form-legend" id="tags">Tags</div>
            <!--Select Box with Filter Search begin-->

            <div class="control-group row-fluid" id="Multiple_Select_Box_with_Filter_Search">
                <div class="span3">
                    <label for="multiFilter" class="control-label">Tags</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select multiple name="multiFilter" id="multiFilter">
                            @foreach($tags as $tag)
                                <option value="{{ $tag->tags_id }}">{{ $tag->tag }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <script>
                    $().ready(function(){
                        $("#multiFilter").select2();
                    });
                </script>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" for="add tags">Add New Tags<br>(Separated by Coma. No spaces)</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="addtags" class="required number valid"><span for="add tags" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                        </div>
                    </div>
                    <div class="span12 span-inset">

                        <div style="float:right; width:11%; margin-bottom:5px;"><button type="" class="btn btn-primary" id="attachTag" style="display:block;">Attach</button>
                            <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:50%; display:block; margin-left:15px;"></div>
                    </div>
                </div>
                <!-- Add Tag to Tags Table - Ajax request -->
                <script>
                    $().ready(function() {
                        var token = $('input[name=_token]');
                        // process the form
                        $("#attachTag").click(function(){
                            //alert(token.val());
                            // process the form
                            $.ajax({
                                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                                url         : '/article/addTag', // the url where we want to POST
                                data        :   { tag : $('input[name=addtags]').val() },
                                dataType    : 'json', // what type of data do we expect back from the server
                                encode      : true,
                                headers: {
                                    'X-CSRF-TOKEN': token.val()
                                }
                            })
                                // using the done promise callback
                                    .done(function(data) {

                                        // log data to the console so we can see
                                        //console.log(data);
                                        alert('Tag Saved');
                                        // here we will handle errors and validation messages
                                    });

                            // stop the form from submitting the normal way and refreshing the page
                            //event.preventDefault();
                        });

                    });
                </script>
            </div>
            <!--Select Box with Filter Search end-->
        </div>





        <div class="container-fluid">

            <div class="form-legend" id="photos-videos">Photos & Videos</div>

            <!--Tabs begin-->
            <div  class="control-group row-fluid span-inset">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="dropdown active"><a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">Upload Image<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a data-toggle="tab" href="#dropdown1">Image 1</a></li>
                            <li><a data-toggle="tab" href="#dropdown2">Image 2</a></li>
                            <li><a data-toggle="tab" href="#dropdown3">Image 3</a></li>
                            <li><a data-toggle="tab" href="#dropdown4">Image 4</a></li>
                        </ul>
                    </li>
                    <li><a data-toggle="tab" href="#tab-example1">Video</a></li>
                    <li><a data-toggle="tab" href="#tab-example4">Current Photos</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-example1" class="tab-pane fade">
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Title</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <input type="text" name="inputSpan9" id="inputSpan9">
                                </div>
                            </div>
                        </div>
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Code (500/320)</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <textarea  rows="4" class="no-resize"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Source</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <input type="text" name="inputSpan9" id="inputSpan9">
                                </div>
                            </div>
                        </div>
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">URL</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <input type="text" name="inputSpan9" id="inputSpan9">
                                </div>
                            </div>
                        </div>
                        <div class="control-group row-fluid">
                            <div class="span12 span-inset">
                                <div style="float:right; width:11%; margin-bottom:5px;"><button class="btn btn-warning" type="button" style="display:block;">Submit</button>
                                    <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:50%; display:block; margin-left:15px;"/></div>
                            </div>
                        </div>
                    </div>

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
                                                <img src="{{ asset('images/photon/user1.jpg')}}" alt="user" style="width:40%;" />
                                            </td>
                                            <td>Celebrate 'Dry Holi' to save water for future</td>
                                            <td class="center">JPEG image</td>
                                            <td class="center">PTI</td>
                                            <td class="center"><button type="button" class="btn btn-mini btn-danger">Dump</button><img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:block; margin-left:15px;"/></td>
                                        </tr>
                                        <tr class="gradeX">
                                            <td>
                                                <img src="{{ asset('images/photon/user2.jpg')}}" alt="user" style="width:40%;" />
                                            </td>
                                            <td>Celebrate 'Dry Holi' to save water for future</td>
                                            <td class="center">JPEG image</td>
                                            <td class="center">PTI</td>
                                            <td class="center"><button type="button" class="btn btn-mini btn-danger">Dump</button><img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:block; margin-left:15px;"/></td>
                                        </tr>
                                        <tr class="gradeX">
                                            <td>
                                                <img src="{{ asset('images/photon/user3.jpg')}}" alt="user" style="width:40%;" />
                                            </td>
                                            <td>Celebrate 'Dry Holi' to save water for future</td>
                                            <td class="center">JPEG image</td>
                                            <td class="center">PTI</td>
                                            <td class="center"><button type="button" class="btn btn-mini btn-danger">Dump</button><img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:block; margin-left:15px;"/></td>
                                        </tr>
                                        <tr class="gradeX">
                                            <td>
                                                <img src="{{ asset('images/photon/user4.jpg')}}" alt="user" style="width:40%;" />
                                            </td>
                                            <td>Celebrate 'Dry Holi' to save water for future</td>
                                            <td class="center">JPEG image</td>
                                            <td class="center">PTI</td>
                                            <td class="center"><button type="button" class="btn btn-mini btn-danger">Dump</button><img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:block; margin-left:15px;"/></td>
                                        </tr>
                                        <tr class="gradeX">
                                            <td>
                                                <img src="{{ asset('images/photon/user5.jpg')}}" alt="user" style="width:40%;" />
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
                                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span><input type="file" /></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
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
                                    <input type="text" name="inputSpan9" id="inputSpan9">
                                </div>
                            </div>
                        </div>
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label">Description 1</label>
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
                                <div data-on-label="Enabled" data-off-label="Disabled" class="switch">
                                    <input type="checkbox" checked="checked">
                                </div>


                                <button class="btn btn-warning" type="button" style="display:block;">Submit</button>
                                <img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
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
                                <img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end container-->

        <!--start container-->

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
                    <button class="btn btn-warning" type="button" style="display:block;">Schedule</button>
                    <img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
                </div>
            </div>
            <script>
                $(function(){
                    $("#datepicker").datepicker();
                    $("#datepickerInline").datepicker();
                    $("#datepickerMulti").datepicker({
                        numberOfMonths: 3,
                        showButtonPanel: true
                    });
                    $('#timeEntry').timeEntry().change();
                });
            </script>
        </div>

        <div class="container-fluid">

            <div class="control-group row-fluid">
                <div class="span12 span-inset">

                    <label class="checkbox" >
                        <input type="checkbox" class="uniformCheckbox" value="checkbox1">
                        <a href="#" target="_blank">Publish this to Home Page.</a>
                    </label>
                    <script>
                        $().ready(function(){
                            $(".uniformCheckbox").uniform();
                        });
                    </script>

                    <label class="checkbox" >
                        <input type="checkbox" class="uniformCheckbox2" value="checkbox1">
                        <a href="#" target="_blank">This article is important.</a>
                    </label>
                    <script>
                        $().ready(function(){
                            $(".uniformCheckbox2").uniform();
                        });
                    </script>

                    <label class="checkbox" >
                        <input type="checkbox" class="uniformCheckbox3" value="checkbox1">
                        <a href="#" target="_blank">Web Exclusive.</a>
                    </label>
                    <script>
                        $().ready(function(){
                            $(".uniformCheckbox3").uniform();
                        });
                    </script>


                </div>
            </div>

            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="button" class="btn btn-default">Draft</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
                    <button type="submit" id="pageSubmit" class="btn btn-warning">Submit</button><img src="{{ asset('images/photon/preloader/76.gif')}}'" alt="loader" style="width:5%; display:none;"/>
                    <button type="button" class="btn btn-info">Save</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
                    <button type="button" class="btn btn-success">Publish</button><img src="{{ asset('images/photon/preloader/76.gif')}}'" alt="loader" style="width:5%; display:none;"/>
                    <button type="button" class="btn btn-danger">Dump</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>

                    {!! Form::submit('Contact Us!',
                            array('class'=>'btn btn-primary')) !!}

                </div>
            </div>
        </div>
        <!--	end container-->
        {!! Form::close() !!}}

    </div>
    </body>
    </html>
@stop