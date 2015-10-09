@extends('layouts/master')

@section('title', 'Create New Quickbyte - BWCMS')


@section('content')

    <div class="panel">
        <div class="panel-content filler">
            <div class="panel-logo"></div>
            <div class="panel-header">
                <h1><small>Create New QuickByte</small></h1>

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
                                        "title" : "Author",
                                        "attr" : { "href" : "#Author" }
                                    }
                                },
                                {
                                    "data" : {
                                        "title" : "Quick Byte Feature",
                                        "attr" : { "href" : "#qb-feature" }
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
                                        "title" : "Topics",
                                        "attr" : { "href" : "#topics" }
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
                <li>
                    <a href="#">QuickByte &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                    <ul class="breadcrumb-sub-nav">
                        <li>
                            <a href="/quickbyte/create">Create New QuickByte</a>
                        </li>
                        <li>
                            <a href="/quickbyte/list/published">Published QuickByte</a>
                        </li>
                        <li>
                            <a href="/quickbyte/list/deleted">Deleted QuickByte</a>
                        </li>
                        <li>
                            <a href="#">QuickByte for Footer</a>
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
                    <a href="javascript:;">Create New QuickByte</a>
                </li>
            </ul>
        </div>            <header>
            <i class="icon-big-notepad"></i>
            <h2><small>QuickByte ID: 45345</small></h2>
        </header>
        {!! Form::open(array('url'=>'quickbyte/','class'=> 'form-horizontal','id'=>'validation_form', 'files' => true)) !!}
        {!! csrf_field() !!}
            <div class="container-fluid">
                <div class="form-legend" id="Notifications">Notifications</div>
                <!--Notifications begin-->
                <div class="control-group row-fluid" style="display: none">
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
                        <label class="control-label" for="channel_sel">Channel</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <select name="channel_sel" id="channel_sel">
                                <option selected="" value="All">Select</option>
                                @foreach($channels as $c)
                                    <option value="{{$c->channel_id}}">{{$c->channel}}</option>
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
            </div>

            <div class="container-fluid">

                <div class="form-legend" id="Author">Author</div>

                <!--Select Box with Filter Search begin-->
                <div  class="control-group row-fluid" >
                    <div class="span3">
                        <label class="control-label" for="author_type">Author</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <select name="author_type" id="author_type">
                                <option selected="" value="1">BW Online Bureau</option>
                                <option value="2">BW Reporters</option>
                            </select>
                        </div>
                    </div>
                    <script>
                        $().ready(function(){
                            $("#author_type").select2();
                        });
                    </script>

                    <script type="text/javascript">

                        $(document).ready(function(){
                            $("#ch-reporter").hide();

                            $("select").change(function(){

                                $(this).find("option:selected").each(function(){

                                    if($(this).attr("value")=="2"){

                                        $("#ch-reporter").show();
                                    }
                                    else {
                                        $("#ch-reporter").hide();
                                    }

                                });

                            }).change();

                        });

                    </script>

                </div>

                <!--Select Box with Filter Search end-->

                <div  class="control-group row-fluid" id="ch-reporter">
                    <div class="span3">
                        <label class="control-label" for="author">Choose Reporter</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <select name="author" id="author">
                                @foreach($authors as $au)
                                    <option value="{{$au->author_id}}">{{$au->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <script>
                        $().ready(function(){
                            $("#author").select2();
                        });
                    </script>
                </div>

            </div>


            <div class="container-fluid">
                <div class="form-legend" id="qb-feature">Quick Byte Feature
                </div>
                <div id="Photo-feature"  class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Feature Title </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="title" id="title">
                        </div>
                    </div>
                </div>
                <div id="Text_Area_Resizable" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Feature Description</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <textarea  rows="4" name="description" id="description" class=""></textarea>
                        </div>
                    </div>
                </div>


                <!--Drag And Drop Upload begin-->
                <div id="Drag_And_Drop_Upload" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" for="inputField">
                            Upload Photos<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Here You can add multiple photos by Drag and Drop or Simply By clicking and selecting  photos."><i class="icon-photon info-circle"></i></a>
                        </label>
                    </div>
                    <div class="span9 row-fluid">
                        <div class="upload-boxes row-fluid">
                            <div class="span3" id="upload" name="pics[]"></div>
                            <div class="span9" name="picsUploaded[]" id="uploaded"></div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $().ready(function() {

                            var errors="";
                            var token = $('input[name=_token]');
                            alert(token.val());

                            $.ajaxSetup({
                                headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
                            });

                            $("#add_des").click(function(e){
                                e.preventDefault();
                                //var formData = new FormData(document.getElementById('modal-file-upload')[0]);

                                var fd = new FormData();

                                var ins=document.getElementById('upload').files.length;

                                for(var x=0;x<ins;x++)
                                {
                                    fd.append("fileToUpload[]", document.getElementById('fileToUpload').files[x]);
                                }



                                var folder = $("input#modal-file-upload-folder").val();
                                var token =  $("input[name=_token]").val();
                                var dataString = 'formData='+fd+'&folder='+folder+'&token='+token;


                                $.ajax({
                                    type: "POST",
                                    url: '{!! url() !!}/quickbyte/upload/',
                                    cache: false,
                                    data : dataString,
                                    processData: false, // Don't process the files
                                    contentType: false, // Set content type to false as jQuery will tell the server it
                                    dataType: 'JSON',
                                    success : function(data){
                                        console.log(data);
                                    }
                                },"json");

                            });
                            $('#upload').mfupload({

                                type        : 'jpg,png,tif,jpeg',   //all types
                                maxsize     : 2,

                                post_upload : "quickbyte/upload/",
                                folder      : "",
                                ini_text    : "Drag your file(s) here or click (max: 2MB each)",
                                over_text   : "Drop Here",
                                over_col    : '#666666',
                                over_bkcol  : '#f0f0f0',
                                contentType :  false,
                                processData :  false,
                                headers: {
                                    'X-CSRF-TOKEN': 'Toks'
                                },

                                beforeSend: function (request)
                                {
                                    request.setRequestHeader("X-CSRF-TOKEN", token);
                                },

                                onSend: function(e, options) {
                                    var accessToken = $("input[name=_token]").val();

                                    options.headers = {
                                        'X-CSRF-TOKEN': token
                                    };
                                },
                                init        : function(){
                                    $("#uploaded").empty();
                                },

                                start       : function(result){
                                    $("#uploaded").append("<div name='FILE"+result.fileno+"' id='FILE"+result.fileno+"' class='files'>"+result.filename+"<div class='progress progress-info progress-thin'><div class='bar' id='PRO"+result.fileno+"'></div></div></div>");
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
                                    //alert($("#FILE0").val());
                                }
                            });
                        });
                    </script>
                </div>
                <!--Drag And Drop Upload end-->


                <div class="control-group row-fluid">
                    <div class="span3" >
                        <a class="control-label text-info" href="edit-article-panel-page2.html" >Add Individual Photo Description</a>
                    </div>

                    <div class="span9 span-inset">
                        <button class="btn btn-info" id="add_des" type="button">Add </button>
                    </div>
                </div>

                <!-- descruiption divs can be multiple so place them here-->
                <div id="des_area">
                    <div  class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Description1</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <textarea  rows="2" name="picDesc" class=""></textarea>
                            </div>
                        </div>
                    </div>

                    <div  class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Description2</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <textarea  rows="2" name="picDesc" class=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function(e) {
                        $("#des_area").hide();
                        $("#add_des").click(function(e) {
                            $("#des_area").show();
                        });
                    });
                </script>

            </div>

            <div class="container-fluid">

                <div class="form-legend" id="tags">Tags</div>
                <!--Select Box with Filter Search begin-->

                <div class="control-group row-fluid" id="Multiple_Select_Box_with_Filter_Search">
                    <div class="span3">
                        <label for="multiFilter" class="control-label">Tags</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <select multiple name="Taglist[]" id="Taglist">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->tags_id }}">{{ $tag->tag }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <script>
                        $().ready(function(){
                            $("#Taglist").select2();
                        });
                    </script>

                </div>
                <!--Select Box with Filter Search end-->
            </div>


            <div class="container-fluid">

                <div class="form-legend" id="topics">Topics</div>
                <!--Topics begin-->
                <div  class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" for="dualMulti" style="width:100%;">Topics</label>
                        <button type="button" class="btn btn-mini btn-inverse" style="margin-left:15px; display:block;">Generate Topics</button>
                        <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:block; margin-left:15px;"/>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <select multiple name="Ltopics[]" id="Ltopics">
                                <option selected="" value="Beige">Beige</option>
                                <option value="Black">Black</option>
                                <option value="Blue">Blue</option>
                                <option value="Bronze">Bronze</option>
                                <option value="Brown">Brown</option>
                            </select>
                        </div>
                    </div>
                    <script>
                        $().ready(function(){
                            $("#Ltopics").pickList();
                        });
                    </script>
                    <div class="span12 span-inset">
                        <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;">
                    </div>
                </div>
                <!--Topics end-->

            </div>



            <div class="container-fluid">
                <div class="control-group row-fluid">
                    <div class="span12 span-inset">

                        <label class="checkbox" >
                            <input type="checkbox" class="uniformCheckbox" value="checkbox1">
                            <a href="#" target="_blank">This Is  Sponsored</a>
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
                        <button type="submit" name="status" value="P" class="btn btn-warning">Publish</button><img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>
                    </div>
                </div>
            </div>
            <!--	end container-->

        {!! Form::close() !!}
    </div>
    @stop