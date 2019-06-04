@extends('layouts/master')

@section('title', 'uloadaudio Podcast - BWCMS')

@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Add/Edit Album Audio</small></h1>
            
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
                                "title" : "Choose Album", 
                                "attr" : { "href" : "#Album" } 
                            }
                        },
												{
                            "data" : { 
                                "title" : "Album-Details", 
                                "attr" : { "href" : "#Album-Details" } 
                            }
                        },
                                                {
                            "data" : { 
                                "title" : "Add/Upload Audio", 
                                "attr" : { "href" : "#add-audio" } 
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
            <a href="#">Add/Edit Album Audio</a>
            
        </li>
    </ul>
</div>            
<header>
    <i class="icon-big-notepad"></i>
    <h2><small>Album ID: {{$idalbum}}</small></h2>
</header>
{!! Form::open(array('url'=>'podcast/storeaudio/','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}    
<input type="hidden" name="idalbum" value="{{$idalbum}}">
<input type="hidden" name="channel" value="{{$PodcastArr->channel_id}}">
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

        <div class="form-legend" id="Channel">Album Title</div>

        <!--Select Box with Filter Search begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Album Title</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select   id="channel" class="formattedelement" disabled>
                       <option> {{$PodcastArr->album_name}} </option>
                    </select>
                    
                </div>
            </div>
            <script>
                $().ready(function () {
                    $("#channel").select2();

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
                                {option: $(this).attr("value") + '&level='},
                        function (data) {
                            var Box = $('#category1');
                            Box.empty();
                            Box.append("<option value=''>Please Select</option>");
                            $.each(data, function (index, element) {
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
                });
            </script>
        </div>

        <!--Select Box with Filter Search end-->					
    </div>

    


    <div class="container-fluid">  
        <div class="form-legend" id="qb-feature">Quick Byte Feature 
        </div>
        
        

        <!--Drag And Drop Upload begin-->
        <div id="Drag_And_Drop_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">
                    Upload Photos<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Here You can add multiple photos by Drag and Drop or Simply By clicking and selecting  photos (Size: {{config('constants.dimension_qb')}}) (File Size <= {{config('constants.maxfilesize').' '.config('constants.filesizein')}}  )."><i class="icon-photon info-circle"></i></a>
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
                        <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_qb')}}')">Need to crop images? Click here</a>
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
        

        <!-- descruiption divs can be multiple so place them here-->
        <div id="des_area">
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Description 1</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <textarea  rows="2" class=""></textarea>
                    </div>
                </div>
            </div>

            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Description2</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <textarea  rows="2" class=""></textarea>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function (e) {
                $("#des_area").hide();
                $("#add_des").click(function (e) {
                    $("#des_area").show();
                });
            });
        </script>

    </div>

    <div class="container-fluid">
        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <button value="P" name="status" class="btn btn-warning" type="submit">Publish</button>
                <img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>	
                
            </div>
        </div>
    </div>
   


    {!! Form::close() !!}
    <!--end container-->				
	<div class="container-fluid">
            <div class="form-legend">Album List</div>
             <!--Sortable Responsive Media Table begin-->
            <div class="row-fluid">
		<div class="span12">
                    <table class="table table-striped table-responsive" id="tableSortableResMed">
			<thead class="cf sorthead">
                            <tr>
                              
                               <th>Name</th>
                               <th>Date</th>
                               
                               
                               <th><input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall"></th>
                            </tr>
			</thead>
                        <tbody>
                            @foreach($postsArr as $posts)
                                <tr  class="gradeX"  id="row_{{$posts->id}}">
                                   <td><a href="/podcast/audioedit?id={{ $posts->id }}">{{ $posts->title }}</a></td>
                                    <td >{{ $posts->updated_at }}</td>         
                                   <td><input type="checkbox" class="uniformCheckbox" name="checkItem[]" value="{{ $posts->id }}"></td>
                               </tr>
                              @endforeach  
                              <script>
                        $(document).ready(function () {

                            $('#selectall').click(function () {
                                if ($(this).is(':checked')) {
                                    $('input[name="checkItem[]"]').each(function () {
                                        $(this).attr('checked', 'checked');
                                    });
                                } else {
                                    $('input[name="checkItem[]"]').each(function () {
                                        $(this).removeAttr('checked');
                                    });
                                }
                            });
                        });

                        function deletePodcast() {
                                var ids = '';
                                var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                                    //var row = 'rowCur' + this.value;
                                    //$("#" + row).hide();
                                    return this.value;
                                }).get();
                                if (checkedVals.length > 0) {
                                    var ids = checkedVals.join(",");
                                    //alert(ids);return false;
                                    $.get("{{ url('/podcast/audiodelete/?channel=').$currentChannelId}}",
                                            {option: ids},
                                    function (data) {
                                        if (data.trim() == 'success') {
                                            $.each(checkedVals, function (i, e) {
                                                var row = 'rowCur' + e;
                                                $("#" + row).hide();
                                            });
                                            $('#notificationdiv').show();
                                            $('#notificationdiv .control-group .span12.span-inset').html('<div class="alert alert-success alert-block">\n\
                                        <i class="icon-alert icon-alert-info"></i><button type="button" class="close" data-dismiss="alert">\n\
                                        &times;</button><strong>This is Success Notification</strong>\n\
                                        <span></span>Selected records dumped.</div>');
                                        } else {
                                            alert(data);
                                        }
                                        //alert(1);
                                    });
                                } else {
                                    alert('Please select at least one recordt.');
                                }
                            }

                        
                    </script>
                       </tbody>
                    </table>
                </div>
            </div>
            <!--Sortable Responsive Media Table end-->
					
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="button" class="btn btn-danger" onclick="deletePodcast()">Dump</button>
                    <img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                    
                    
                </div>
               
            </div>
        </div>
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
    var Taglist = 0;
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
    <td colspan="1">Tags</td>
    <td colspan="3"><input type="text" class="valid" name="Taglist[{%=file.name%}]" id="Taglist{%=file.name%}"/></td> 
    
    </tr>
    <tr>
    <td colspan="1"></td>
    <td colspan="3"><input type="text" name="addtags[{%=file.name%}]" class="valid" /></td>
   
    </tr>
    <tr>
    <td><button type="button" class="btn btn-primary" id="attachTag{%=file.name%}" style="display:block;">Attach</button></td>
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
                            acceptFileTypes: /(\.|\/)(gif|jpe?g|png|mp3|mp4)$/i,
                            maxFileSize: 20000000
                        });
                    });



                    $('#fileupload').bind('fileuploaddone', function (e, data) {
                        
                        ///console.log(e);
                        //console.log(data._response.result.files[0].name);

                     setTimeout(function(){ 

                            //var id='#Taglist'+data._response.result.files[0].name).length);
                             //   +data._response.result.files[0].name
                                //$(document).find('#Taglist'+data._response.result.files[0].name).tokenInput("/tags/getJson", {
                                $("input[name='Taglist["+data._response.result.files[0].name+"]']").tokenInput("/tags/getJson", {
                                    theme: "facebook",
                                    searchDelay: 300,
                                    minChars: 4,
                                    preventDuplicates: true,
                                });

                                //alert($("#Taglist").length);

                        }, 1000);

                    
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
<!-- Add Tag to Tags Table - Ajax request -->
                <script>
                    $().ready(function () {
                            //alert('sumit1');
                        var token = $('input[name=_token]');
                        // process the form
                        
                           
                        $("button").live("click", function(){

                            var taglist=$(this).parents('table').find('input[name^="Taglist"]'); //.attr('name');

                            var addtag=$(this).parents('table').find('input[name^="addtags"]');//.attr('name');
                            //alert($(addtag).val());
                            if ($(addtag).val().trim().length == 0) {
                                alert('Please enter tag');
                                return false;
                            }
                            $.ajax({
                                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                                url: '/article/addTag', // the url where we want to POST
                                data: {tag: $(addtag).val()},
                                dataType: 'json', // what type of data do we expect back from the server
                                encode: true,
                                beforeSend: function (data) {
                                    //alert(1);
                                    $('#attachTag').hide();
                                    $('#attachTag').siblings('img').show();
                                },
                                complete: function (data) {
                                    $('#attachTag').show();
                                    $('#attachTag').siblings('img').hide();
                                },
                                success: function (data) {

                                    $.each(data, function (key, val) {

                                        $(taglist).tokenInput("add", val);
                                    });
                                    $(addtag).val('');
                                    // alert('Tag Saved');
                                },
                                headers: {
                                    'X-CSRF-TOKEN': token.val()
                                }
                            })
                           
                        });

                        
                       
                        
                    });
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
                    url: '{{ url("/podcast/sort/".$idalbum)}}',
                    headers: {
                                'X-CSRF-TOKEN': token.val()
                             }
                });
        
    }
  }).disableSelection();

</script>
@stop
