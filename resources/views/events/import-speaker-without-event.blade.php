@extends('layouts/master')

@section('title', 'Import Attendee - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Import Attendee</small></h1>
        </div>

        <br><br>
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
                <a href="dashboard.html">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li class="current">
                <a href="javascript:;">Import Attendee</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Import Attendee</small></h2>

    </header>
    {!! Form::open(array('url'=>'import/attendee','class'=> 'form-horizontal','id'=>'speaker-form','enctype'=>'multipart/form-data')) !!}
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

        <!--Select Box with Filter Search begin-->

        <div class="control-group row-fluid" id="Multiple_Select_Box_with_Filter_Search">

            <!-- Add Tag to Tags Table - Ajax request -->
            <div class="control-group row-fluid">    
            <div class="span3">
                <label for="multiFilter" class="control-label">Tags(Industry)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" class="valid" name="Taglist" id="Taglist"/>
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="add tags">Add New Tags</label>
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
                        url: '/event-speaker/addTag', // the url where we want to POST
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

                        },
                        headers: {
                            'X-CSRF-TOKEN': token.val()
                        }
                    })
                });
                $("#Taglist").tokenInput("/event-speaker/getJson", {
                    theme: "facebook",
                    searchDelay: 300,
                    minChars: 2,
                    preventDuplicates: true,
                    tokenLimit: 1,
                });
            });</script>
            <div id="File_Upload" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Upload File<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="File should be only CSV"><i class="icon-photon info-circle"></i></a>
                    <a href="https://static.businessworld.in/csv/sample.csv" title="Click here to dwonload sample file.">Sample</a>
                    </label>

                </div>
                <div class="span9">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="input-append">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="speaker_file" id="speaker_file"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>   

        <!--Select Box with Filter Search end-->
    </div>
    <div class="container-fluid">

        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button class="btn btn-success pull-right" type="submit" style="display:block; margin-right:5px;">Save</button>
            </div>

        </div>
    </div>                           

    <!-- end container -->
    <script>
        $(document).ready(function () {
            $("#speaker-form").validate({
                errorElement: "span",
                errorClass: "error",
                //$("#pageSubmit").onclick: true,
                onclick: true,
                invalidHandler: function (event, validator) {

                    for (var i in validator.errorMap) { ///alert(i);

                        if ($('#' + i).hasClass('formattedelement')) {
                            $('#' + i).siblings('.formattedelement').addClass('error');
                        }

                    }
                },
                rules:{
                    "speaker_file": {
                        required: true,
                        extension: "csv"
                    },
                },
                submitHandler: function( ){
                    var filename=$('#speaker_file').val().split('\\').pop();
                            answer = confirm("Are you sure you want to add \""+filename+"\" file entries as event attendee ?" );
                            if (answer == true){
                            form.submit( );
                            }
                            else{
                            return false;
                            }
                }
            });
        });
    </script>

    {!! Form::close() !!}

</div>

@stop