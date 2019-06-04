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
            <h1><small>Create New Album </small></h1>

        </div>

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
                                    "title": "Channel",
                                    "attr": {"href": "#Channel"}
                                }
                         },
                            {
                                "data": {
                                    "title": "Album Feature",
                                    "attr": {"href": "#al-feature"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Tags",
                                    "attr": {"href": "#tags"}
                                }
                            }, ]
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
                <a href="/dashboard">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li>
                <a href="#">Album &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                <ul class="breadcrumb-sub-nav">
                    <li>
                        <a href="<?php echo url('album/create') ?>">Create New Album</a>
                    </li>
                    <li>
                        <a href="<?php echo url('album/list/deleted') ?>">Published Album</a>
                    </li>
                    <li>
                        <a href="<?php echo url('album/list/deleted') ?>">Deleted Album</a>
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
                <a href="javascript:;">Create New Album</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>New Album</small></h2>
    </header>
    <!--            <form class="form-horizontal" id="fileupload" action="" method="POST" enctype="multipart/form-data">-->
    {!! Form::open(array('url'=>'album/','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}    

    <div class="container-fluid"  style="display:none">
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

                    {!! Form::select('authortype',$p1,null,
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

        <div class="bs-docs-example" id="tabarea">
          
            <div class="tab-content">
                <div id="existing" class="tab-pane fade active in">

                    <div id="Simple_Select_Box" class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="simpleSelectBox">Author Name</label>
                        </div>

                        <div class="span9">

                        </div>

                        <div class="span9">

                            <div class="controls">
                                <input type="text" class="valid" name="author" id="author"/>
                            </div>
                            <script>

                                         $().ready(function() {
                                        $("#author").tokenInput(function(){
                                            return "/article/authordd?option=" + $("#authortype").val();
                                        },
                                        {
                                        theme: "facebook",
                                                searchDelay: 300,
                                                minChars: 3,
                                                preventDuplicates: true,
                                                tokenLimit:3,
                                        });
                                        
                                            $("#author").show();
                                            
                                            $("#author").css({visibility:"hidden",width:"0",height:"0"});
                                        
                                        });                            
                                                                
                            </script>


                        </div>


                    </div>



                    <script type="text/javascript">

                                        $(document).ready(function(){

                                $("#authortype").change(function(){
                                $("#author").tokenInput("clear");
                                        $(this).find("option:selected").each(function(){



                                if ($(this).attr("value") == "1"){

                                $("#tabarea").hide();
                                } else {
                                $("#tabarea").show();
                                }
                               
                                });
                                }).change();
                                        $("#event_id_author").change(function(){
                                $("#author").tokenInput("clear");
                                });
                                });                        
                                                
                    </script>

                </div>

              
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
                    <select name="channel"  id="channel" class="formattedelement">
                        @foreach($channels as $channel)
                        <option @if($channel->channel_id==$currentChannelId) selected="selected" @endif value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                $().ready(function () {
                    $("#channel").select2();
                });
            </script>
        </div>

        <!--Select Box with Filter Search end-->					
    </div>

    <div class="container-fluid">  
        <div class="form-legend" id="al-feature">Album Feature 
        </div>
        <div id="Photo-feature"  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Title </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="title" id="title">
                </div>
            </div>
        </div>
        <div id="Text_Area_Resizable" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Description</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea  rows="4" class="" id="featuredesc" name="featuredesc"></textarea>
                </div>
            </div>
        </div>


        <!--Drag And Drop Upload begin-->
        <div id="Drag_And_Drop_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">
                    Upload Photos<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Here You can add multiple photos by Drag and Drop or Simply By clicking and selecting  photos (Size: {{config('constants.dimension_album')}}) (File Size <= {{config('constants.maxfilesize').' '.config('constants.filesizein')}}  ). "><i class="icon-photon info-circle"></i></a>
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
                            <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_album')}}')">Need to crop images? Click here</a>
                            <br>
                            <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/resize/crop')}}?dimension={{config('constants.dimension_album')}}')">Need to resize images? Click here</a>
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
                <input type="text" style="height: 0;visibility: hidden;width: 0;" id="uploadedImages" name="uploadedImages">
            </div>

        </div>
    </div>
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
                $().ready(function () {
                    var token = $('input[name=_token]');
                    // process the form
                    $("#attachTag").click(function () {
                        if ($('input[name=addtags]').val().trim().length == 0) {
                            alert('Please enter tag');
                            return false;
                        }

                        $.ajax({
                            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                            url: '/article/addTag', // the url where we want to POST
                            data: {tag: $('input[name=addtags]').val()},
                            dataType: 'json', // what type of data do we expect back from the server
                            encode: true,
                            beforeSend: function (data) {
                                $('#attachTag').hide();
                                $('#attachTag').siblings('img').show();
                            },
                            complete: function (data) {
                                $('#attachTag').show();
                                $('#attachTag').siblings('img').hide();
                            },
                            success: function (data) {

                                $.each(data, function (key, val) {

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
        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                 <label class="checkbox" >
                    <input type="checkbox" class="uniformCheckbox" value="checkbox1" name="for_homepage">
                    <a href="#" >Publish this to Home Page.</a>
                </label>

                <label class="checkbox" >
                    <input type="checkbox" class="uniformCheckbox" value="checkbox1" name="is_sponsored">
                    <a href="#">This Is  Sponsored</a>
                </label>

                <label class="checkbox" >
                    <input type="checkbox" class="uniformCheckbox" value="checkbox1" name="is_featured">
                    <a href="#" >This Is  Featured</a>
                </label>

                <script>
                    $().ready(function () {
                        $(".uniformCheckbox").uniform();
                    });
                </script>			

            </div>

        </div>

        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <button value="P" name="status" type="submit" class="btn btn-warning">Publish</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>	


            </div>
        </div>
    </div>



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
    <td colspan="1">Title</td>
    <td colspan="3"><input type="text" name="imagetitle[{%=file.name%}]"/></td>    
    </tr>

    <tr>
    <td colspan="1">Description</td>
    <td colspan="3"><textarea name="imagedesc[{%=file.name%}]"></textarea></td>    
    </tr>
    <tr>
    <td colspan="1">Photo Source</td>
    <td colspan="3"><input type="text" name="photosource[{%=file.name%}]"/></textarea></td>    
    </tr>
    <tr>
    <td colspan="1">Source Url</td>
    <td colspan="3"><input type="text"  name="sourceurl[{%=file.name%}]"/></textarea></td>    
    </tr>
    <tr>
    <td colspan="1">Photograph By</td>
    <td colspan="3"><input type="text" name="photographby[{%=file.name%}]"/></textarea></td>    
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
<script>
                                            $(document).ready(function () {
                                                $('#fileupload').fileupload({
                                                    // Uncomment the following to send cross-domain cookies:
                                                    //xhrFields: {withCredentials: true},
                                                    url: '<?php echo url('quickbyte/image/upload') ?>',
                                                    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                                                    maxFileSize: 2000000
                                                });
                                            });



                                            $('#fileupload').bind('fileuploaddone', function (e, data) {
                                                //console.log(e);
                                                var dataa = JSON.parse(data.jqXHR.responseText);
                                                //console.log(dataa['files']['0']['name']);
                                                $.each(dataa['files'], function (index, element) {
                                                    //console.log(element.name);
                                                    if ($('#uploadedImages').val().trim())        // validation ends here           
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


                                            function getArg(url, name) {
                                                var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
                                                if (results == null) {
                                                    return null;
                                                }
                                                else {
                                                    return results[1] || 0;
                                                }
                                            }



// Validation start hee

                                            $("#fileupload").validate({
                                                errorElement: "span",
                                                errorClass: "error",
                                                //$("#pageSubmit").onclick: true,
                                                onclick: true,
                                                invalidHandler: function (event, validator) {
                                                         $('#submitsection').show();
                                                    for (var i in validator.errorMap) {

                                                        if ($('#' + i).hasClass('formattedelement')) {
                                                            $('#' + i).siblings('.formattedelement').addClass('error');

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
                                                    "author_type": {
                                                        required: true,
                                                    },
                                                    "author": {
                                                        required: {
                                                            depends: function(element) {
                                                            return $("#authortype").val()!=1;
                                                            }
                                                        }
                                                    },
                                                    "title": {
                                                        required: true
                                                    },
                                                    "featuredesc": {
                                                        required: true
                                                    },
                                                    "uploadedImages": {
                                                        required: true
                                                    }
                                                }
                                            });
                                             $('#submitsection button').click(function(){
                                                $('#submitsection').hide();
                                             });    
                                            $('select.formattedelement').change(function () {
                                                if ($(this).val().trim() != '')
                                                    $(this).siblings('.formattedelement').removeClass('error');
                                                $(this).siblings('span.error').remove();
                                            });

                                            // Validation ends here    

</script>
@stop

