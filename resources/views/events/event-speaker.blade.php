@extends('layouts/master')

@section('title', 'Event Speaker - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Event Speaker</small></h1>
        </div>
        <div class="panel-search container-fluid">
            <form class="form-horizontal" method="get" action="">
                <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                <button class="btn btn-search" type="submit"></button>
                 @if(isset($_GET['searchin'])) 
                <a href="{{url("event/manage-speaker/".$event->event_id)}}"><button class="btn btn-default" type="button">Reset</button></a>
                @endif
                <label class="radio">
            <input type="radio" @if(isset($_GET['searchin'])) @if($_GET['searchin']=='name') checked @endif @endif name="searchin" class="uniformRadio" value="name">
            Search by Speaker Name
        </label>
        <label class="radio">
            <input type="radio" @if(isset($_GET['searchin'])) @if($_GET['searchin']=='email') checked @endif @endif name="searchin" class="uniformRadio" value="email">
            Search by Speaker Email ID
        </label>
            </form>
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
                                }
                            },
                            {
                                "data": {
                                    "title": "Feed Url",
                                    "attr": {"href": "#feed_url"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Speaker List",
                                    "attr": {"href": "#speaker_list"}
                                }
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
                <a href="javascript:;">Event Speaker</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Event Speaker</small></h2>

    </header>
    {!! Form::open(array('url'=>'event/speaker/add','class'=> 'form-horizontal','id'=>'speaker-form','enctype'=>'multipart/form-data')) !!}
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

        <div class="form-legend" id="Input_Field">Event Detail</div>



        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Event Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="hidden" name="event_id" id="event_id" value="{{$event->event_id}}"/>
                    <input id="event_name" name="event_name" type="text" readonly value="{{$event->title}}">
                </div>
            </div>
        </div>




    </div>



    <div class="container-fluid">
        <div class="form-legend" id="new">Speaker Details

        </div>

        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Name</label>
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
    //                                        alert('Tag Saved');
    //                                        $("#Taglist").tokenInput("add", [{"id":"2","name":"Coal Scam"},{"id":"4","name":"Cuisine"},{"id":"7","name":"Education"},{"id":"15","name":"Election"},{"id":"208","name":"testtag1"},{"id":"1","name":"Modi"},{"id":"207","name":"tagtest"},{"id":"210","name":"ankita"}]);
    //                                         //$("#Taglist").tokenInput("add", {id: 9992, name: "test22"});
                                },
                                headers: {
                                    'X-CSRF-TOKEN': token.val()
                                }
                            })
                        });
                        $("#Taglist").tokenInput("/event-speaker/getJson", {
                            theme: "facebook",
                            searchDelay: 300,
                            minChars: 4,
                            preventDuplicates: true,
                            tokenLimit: 1,
                        });
                    });</script>
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


    <div class="container-fluid">

        <div class="form-legend" id="feed_url">Feed URL</div>


        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Feed URL</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="feed_url" id="feed_url" type="text" readonly value="{{url('api/event/speaker').'/'.base64_encode($event->event_id)}}">
                </div>
            </div>
        </div>

    </div>




    <div class="container-fluid">


        <!--Sortable Responsive Media Table begin-->
        <div class="row-fluid" id="speaker_list">
            <div class="span12">
                <table class="table table-striped table-responsive" id="tableSortableResMed">
                    <thead class="cf sorthead">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email-ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($speakers as $speaker)
                        <tr class="gradeX">
                            <td style="width:160px;"><img src="{{ config('constants.awsbaseurl').config('constants.awspeakerdir').$speaker->photo}}" alt="user" style="width:70%;" /></td>
                            <td >{{$speaker->name}}</td>
                            <td >{{$speaker->email}}</td>
                            <td class="center"> 
                                <div class="btn-group">
                                    <button type="button" class="btn dropdown-toggle btn-mini" data-toggle="dropdown">Modify Detail<span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="/event/edit/?id={{$event->event_id}}">Event</a></li>

                                        <li class="divider"></li>
                                        <li><a href="/event/speaker/{{$speaker->id}}">Speaker</a></li>

                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        <!--Sortable Responsive Media Table end-->

    </div><!-- end container -->
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
                    "speaker_email": {
                        required: true,
                        email: true
                    },
                    "speaker_image": {
                        required: true,
                        extension: "jpg|png|jpeg"
                    },
                }
            });


            $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable({
                bInfo: false,
                bPaginate: false,
                "aaSorting": [],
                "aoColumnDefs": [{"bSortable": false, "aTargets": [3]}],
                "fnInitComplete": function () {
                    $(".dataTables_wrapper select").select2({
                        dropdownCssClass: 'noSearch'
                    });
                }
            });
            //                            $("#simpleSelectBox").select2({
            //                                dropdownCssClass: 'noSearch'
            //                            }); 
        });
    </script>

    {!! Form::close() !!}

</div>

@stop