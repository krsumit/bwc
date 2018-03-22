@extends('layouts/master')

@section('title', 'Event Attendee - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Event Attendee</small></h1>
        </div>
        <script>
           
            $().ready(function () {
                $(".uniformRadio").uniform({
                    radioClass: 'uniformRadio'
                });

            });
        </script>
        <br><br>
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
                                    "title": "Add New Speaker",
                                    "attr": {"href": "#new"}
                                },
                               
                            },
                            {
                                "data": {
                                    "title": "Add Professional Detail",
                                    "attr": {"href": "#add-professional-detail"}
                                },
                               
                            },
                            {
                                "data": {
                                    "title": "Save Speaker",
                                    "attr": {"href": "#save-speaker"}
                                },
                               
                            }
                        ]
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
                <a href="dashboard.html">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li class="current">
                <a href="javascript:;">Event Attendee</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Event Attendee</small></h2>

    </header>
    {!! Form::open(array('url'=>'attendee','class'=> 'form-horizontal','id'=>'speaker-form','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
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
        <div class="form-legend" id="new">Attendee Details

        </div>

        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label required-label" for="inputField">Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="speaker_name" name="speaker_name" type="text">
                </div>
            </div>
        </div>

        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Email</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="speaker_email" name="speaker_email" type="email">
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label required-label" for="inputField">Mobile</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="speaker_mobile" name="speaker_mobile" type="text">
                </div>
            </div>
        </div>

        <div id="File_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Photo<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Image dimension should be as per UI requirement"><i class="icon-photon info-circle"></i></a></label>

            </div>
            <div class="span9">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="input-append">
                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="speaker_image" id="speaker_image"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
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
                    <input id="speaker_twitter" name="speaker_twitter" type="text">
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Linkedin</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="speaker_linkedin" name="speaker_linkedin" type="text">
                </div>
            </div>
        </div>
        <div id="Text_Area_-_No_Resize" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Description</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea rows="4" name="speaker_desc" id="speaker_desc" class="no-resize"></textarea>
                </div>
            </div>
        </div>
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
                        alert('Please enter tage');
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

    </div>
    <div class="container-fluid" style="border:none;">
         Add Professional Detail <input type="checkbox" name="add-professional-detail" value="1" id="add-professional-detail">
    </div>
    <div class="container-fluid" style="display: none;" id="professional-detail">
         <div class="form-legend" id="tags">Professional Detail</div>
         <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Designation</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="speaker_designation" name="speaker_designation" type="text">
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Company</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="speaker_company" name="speaker_company" type="text">
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Phone(Mobile)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="speaker_phone" name="speaker_phone" type="text">
                </div>
            </div>
        </div>
          <div id="Text_Area_-_No_Resize" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Email(s)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea rows="2" name="speaker_emails" id="speaker_emails" class="no-resize"></textarea>
                </div>
            </div>
        </div>
          <div id="Text_Area_-_No_Resize" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">City</label>
            </div>
            <div class="span9">
                <div class="controls">
                   <input id="speaker_city" name="speaker_city" type="text">
                </div>
            </div>
        </div>
          <div id="Text_Area_-_No_Resize" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Address</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea rows="2" name="speaker_add" id="speaker_add" class="no-resize"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="save-speaker">

        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button class="btn btn-success pull-right" type="submit" style="display:block; margin-right:5px;">Save</button>
            </div>

        </div>
    </div>                           

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
                rules: {
                    "req": {
                        required: true
                    },
                    "speaker_name": {
                        required: true
                    },
//                    "speaker_email": {
//                        required: true,
//                        email: true
//                    },
//                    "speaker_image": {
//                        required: true,
//                        extension: "jpg|png|jpeg"
//                    },
                }
            });
            
            $('#add-professional-detail').click(function(){
                $('#professional-detail').toggle('slow');
            });
            
        });
    </script>

    {!! Form::close() !!}

</div>

@stop