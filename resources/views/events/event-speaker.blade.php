@extends('layouts/master')

@section('title', 'Event '.$speaker_type->name.' - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Event {{$speaker_type->name}}</small></h1>
        </div>

        <br><br>
        <div class="panel-header">
            <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
        </div> 
        <script type="text/javascript">
            // alert(11);
            $(function () {
                $("#jstree").jstree({
                    "json_data": {
                        "data": [
                            {
                                "data": {
                                    "title": "Add New {{$speaker_type->name}}",
                                    "attr": {"href": "#new"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Feed Url",
                                    "attr": {"href": "#feed_url"}
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
                <a href="javascript:;">Event {{$speaker_type->name}}</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Event {{$speaker_type->name}}</small></h2>

    </header>
    {!! Form::open(array('url'=>'event/speaker/add/'.$event->event_id,'class'=> 'form-horizontal','id'=>'speaker-form','enctype'=>'multipart/form-data')) !!}
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

            <div class="form-legend" id="tags">{{$speaker_type->name}}</div>
            <!--Select Box with Filter Search begin-->

            <div class="control-group row-fluid" id="Multiple_Select_Box_with_Filter_Search">
                <div class="control-group row-fluid">    
                    <div class="span3">
                        <label for="multiFilter" class="control-label">Assign </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <select name="speaker_type" id="speaker_type">
                                @foreach($speaker_types as $type)
                                  <option @if($type->id == $speaker_type->id) selected="selected" @endif value="{{ $type->id }}">{{ $type->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="control-group row-fluid">    
                    <div class="span3">
                        <label for="multiFilter" class="control-label">Add {{$speaker_type->name}}</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" class="valid" name="Taglist" id="Taglist"/>
                        </div>
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
                        $("#Taglist").tokenInput("/attendee/get-json/", {
                            theme: "facebook",
                            searchDelay: 300,
                            minChars: 3,
                            preventDuplicates: true,
                        });
                         $("#speaker_type").select2();
                    });</script>
            </div>                       
            <!--Select Box with Filter Search end-->
        </div>
    @if(in_array('106',Session::get('user_rights')))
    <div class="container-fluid">

        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button class="btn btn-success pull-right" name="add-attendee" value="save" type="submit" style="display:block; margin-right:5px;">Save</button>
            </div>

        </div>
    </div>                           
    @endif

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
                            <th>Mobile</th>
                            <th>Profile</th>
                            <th>Action</th>
                            <th><input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($speakers as $speaker)
                        <tr class="gradeX">
                            <td style="width:160px;"><img src="{{ config('constants.awsbaseurl').config('constants.awspeakerdir').$speaker->photo}}" alt="User Image" style="width:70%;" /></td>
                            <td >{{$speaker->name}}</td>
                            <td >{{$speaker->email}}</td>
                            <td >{{$speaker->mobile}}</td>
                            <td >{{$speaker->designation}},<br>{{$speaker->company}}</td>
                            <td class="center"> 
                                <a href="/attendee/{{$speaker->speaker_id}}/edit"><button type="button" class="btn dropdown-toggle btn-mini">Edit</button></a>
                            </td>
                            <td  class="center"> <input type="checkbox" class="uniformCheckbox" name="checkItem[]" value="{{$speaker->id}}"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        <!--Sortable Responsive Media Table end-->
          <div class="dataTables_paginate paging_bootstrap pagination">
                {!! $speakers->appends(Input::get())->render() !!}
         </div>
    </div><!-- end container -->
    
    <div class="control-group row-fluid">
        <div class="span12 span-inset">
            @if(in_array('106',Session::get('user_rights')))
            <button type="submit" value="remove" class="btn btn-danger" name="remove-attendee">Remove</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
            @endif
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
                    
            $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable({
                bInfo: false,
                bPaginate: false,
                "aaSorting": [],
                "aoColumnDefs": [{"bSortable": false, "aTargets": [5,6]}],
                "fnInitComplete": function () {
                    $(".dataTables_wrapper select").select2({
                        dropdownCssClass: 'noSearch'
                    });
                }
            });
            
                       
            $('#speaker_type').change(function(){
               window.location = '{{url("event/manage-speaker")}}/{{$event->event_id}}' + '?type=' + $(this).val();
            });
  
            $('button[type=submit]').click(function(){   
                //add-attendee   remove-attendee
                if($(this).attr('name')=='add-attendee'){
                    
                    if($('#Taglist').val().length==0){
                        alert("Please select atleast one profile to add.");
                        return false;
                    }
                }
                else if($(this).attr('name')=='remove-attendee'){
                    if($('input[name="checkItem[]"]:checked').length==0){
                        alert("Please select atleast one profile to remove.");
                        return false;
                    }else{
                        if(!confirm('Do you want to remove selected attendee from event ?'))
                            return false;
                    }
                }
            });
            
            
        });
    </script>

    {!! Form::close() !!}

</div>

@stop