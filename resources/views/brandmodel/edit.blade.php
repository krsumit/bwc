@extends('layouts/master')
@section('title', 'Edit Model - BWCMS')
@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit Model</small></h1>
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
                <a href="/brand-models">Model</a>

            </li>
            <li class="current">
                <a href="javascript:;">Edit Model</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Edit Model</small></h2>
    </header>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/brand-models" >
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Models List</button>
        </a>
    </div>
    {!! Form::open(array('url'=>'brand-models/update','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data','method'=>'post')) !!}
    {!! csrf_field() !!}
    <input type="hidden" name="id" value="{{$brandModel->id}}"/>
    
    <div class="container-fluid" id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')) && (!$errors->any()))style="display: none" @endif >

         <div class="form-legend" id="Notifications">Notifications</div>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

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
        <div class="form-legend" id="feed-detail">Model Details</div>


        <div id="Simple_Select_Box" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="simpleSelectBox">Product Type</label>
            </div>
            <div class="span9">
                <div class="controls">
                    {!! Form::select('product_type',['' => 'Please Select']+$productTypes,old('product_type')?old('product_type'):$brandModel->product_type_id,
                    ['class' => 'form-control formattedelement','id' =>'product_type','class'=>'selectBox formattedelement']) !!}

                </div>
            </div>
            <script>
                $().ready(function(){
                $(".selectBox").select2({
                dropdownCssClass: 'noSearch'
                });
                });             </script>
        </div>

        <div id="Simple_Select_Box" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="simpleSelectBox">Brand</label>
            </div>
            <div class="span9">
                <div class="controls">
                    {!! Form::select('brand',['' => 'Please Select']+$brands,old('brand')?old('brand'):$brandModel->brand_id,['class' => 'form-control formattedelement','id' =>'brand','class'=>'selectBox formattedelement']) !!}

                </div>
            </div>

        </div>

        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" id="model_name" name="model_name" required="required"  value="{{ old('model_name')?old('model_name'):$brandModel->name }}"/>
                </div>
            </div>
        </div>


        <!--WYSIWYG Editor - Full Options-->
        <div id="WYSIWYG_Editor_-_Full_Options" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="description">Description</label>
            </div>
            <div class="span9">
                <div class="controls elrte-wrapper">
                    <textarea name="description" id="maxi" rows="2" class="auto-resize  formattedtextareat">{{ old('description')?old('description'):$brandModel->description }}</textarea>
                    <span for="description" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                    <script>
                                $(function() {

                                $('#maxi').froalaEditor({
                                height: 200,
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
                                $(document).ready(function(){
                        $("#canonical").addClass("none");
                                $(':radio[id=ifno]').change(function() {
                        $("#canonical").addClass("none");
                        });
                                $(':radio[id=ifyes]').change(function() {
                        $("#canonical").removeClass("none");
                        });
                        });                        </script>
                </div>
            </div>
        </div>

        <!--WYSIWYG Editor - Full Options end-->

        <div class="control-group row-fluid">    
                <div class="span3">
                    <label class="control-label">Review Grid</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input type="text" class="valid" name="grid" id="grid"/>
                    </div>
                </div>
            <script>
                    $().ready(function(){
                         $("#grid").tokenInput(function(){ 
                            return "/grids/grid-json?product_type="+$("#product_type").val();
                        }, 
                        {
                                theme: "facebook",
                                searchDelay: 300,
                                minChars: 3,
                                tokenLimit:1,
                                preventDuplicates: true,
                                prePopulate: <?php echo $grid ?>,
                        });
                    });
            </script>
            </div>
        
          <!--Sortable Responsive Media Table begin-->
                        <div class="row-fluid">
                            <div class="span12">
                                @if(count($photos)>0)
                                <table class="table table-striped table-responsive uploaded-image-list" id="tableSortableResMed">
                                    <thead class="cf sorthead">
                                        <tr>
                                            <th>Image</th>
                                            <th>Title </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($photos as $photo)
                                        <tr id="row_{{$photo->id}}" title="{{$photo->title}}">
                                            <td>
                                                <img width="100" height="100" src="{{ config('constants.awsbaseurl').config('constants.aws_model_image').$photo->image}}" alt="{{$photo->title}}" />
                                            </td>
                                            <td>{{$photo->title}}</td>
<!--                                            <td>{{ $photo->title }}</td>-->
                                    <input type="hidden" name="deleteImagel" id="{{ $photo->id }}">
<!--                                    <td class="center">{{ $photo->source }}</td>
                                    <td class="center">{{ $photo->source_url }}</td>-->
                                    <td class="center"><button type="button" onclick="$(this).MessageBox({{ $photo->id }})" name="{{ $photo->photo_id }}" id="deleteImage" class="btn btn-mini btn-danger">Dump</button>
                                        <button type="button" onclick="editModelImage({{ $photo->id }})" name="image{{ $photo->id }}" id="deleteImage" class="btn btn-mini btn-edit">Edit</button>
                                        <img  src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:block; margin-left:15px;display:none;"/></td>
                                    </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>
            <!--Sortable Responsive Media Table end-->
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

    </div>  

    <div class="container-fluid">


        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <button type="submit" name="add" value="N" id="add_button"class="btn btn-warning" >Update</button>
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
    <td colspan="1">Title</td>
    <td colspan="3"><input type="text" name="imagetitle[{%=file.name%}]"/></td>    
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

<script type="text/javascript" src="{{ asset('js/florawysiwyg/froala_editor.pkgd.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/florawysiwyg/froala_editor.pkgd.min.css') }}" media="all" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/florawysiwyg/froala_style.min.css') }}" media="all" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/florawysiwyg/font-awesome.min.css') }}" media="all" />
<script>
                                            $(document).ready(function () {
                                    $('#fileupload').fileupload({
                                    // Uncomment the following to send cross-domain cookies:
                                    //xhrFields: {withCredentials: true},
                                    url: '<?php echo url('article/image/upload') ?>',
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
                                            $('#fileupload').bind('fileuploadcompleted', function (e, data) {
                                    var dataa = JSON.parse(data.jqXHR.responseText);
                                            $.each(dataa['files'], function (index, element) {
                                            $('body').find("input[name='photograph_tags[" + element.name + "]']").tokenInput("/tags/getJson", {
                                            theme: "facebook",
                                                    searchDelay: 300,
                                                    minChars: 3,
                                                    preventDuplicates: true,
                                            });
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
                                            
                $("#fileupload").validate({
                    errorElement: "span",
                    invalidHandler: function (event, validator) {
                        $('#add_button').removeAttr('disabled');
                    },              
                    rules: {
                    "req": {
                    required: true
                    },
                        "product_type": {
                            required: true,
                            },
                            "brand": {
                            required: true
                            },
                            "model_name": {
                            required: true
                            }
                    }
            });


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
                    url: '{{ url("/brand-models/sort/".$brandModel->id)}}',
                    headers: {
                                'X-CSRF-TOKEN': token.val()
                             }
                });
        
    }
  }).disableSelection();
  
  
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
                        url         : '/brand-models/deleteImage', // the url where we want to POST
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

                console.log(data);
                        //alert('Author Saved');
                        // here we will handle errors and validation messages
                }); 
        };
                
                
</script>    

@stop
