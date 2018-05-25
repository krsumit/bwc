@extends('layouts/master')
@section('title', 'Event Streaming - BWCMS')
@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Event Streaming</small></h1>
        </div>

        <br><br>
        <div class="panel-header">
            <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
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
                <a href="dashboard.html">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li class="current">
                <a href="javascript:;">Event Streaming</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Event Streaming</small></h2>

    </header>
    {!! Form::open(array('url'=>'','class'=> 'form-horizontal','id'=>'speaker-form','enctype'=>'multipart/form-data')) !!}
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

    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/event/streaming/create">
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="button">Add Streaming</button>
        </a>
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
                        <select name="channel_sel" id="channel_sel" class="required channel_sel formattedelement">
                            @foreach($channels as $channel)
                            <option @if($channel->channel_id==$currentChannelId) selected="selected" @endif value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <script>
                    $().ready(function () {
                        $("#channel_sel").select2();
                    });</script>
            </div>

            <!--Select Box with Filter Search end-->
        </div>

    <div class="container-fluid">


        <!--Sortable Responsive Media Table begin-->
        <div class="row-fluid" id="speaker_list">
            <div class="span12">
                <table class="table table-striped table-responsive" id="tableSortableResMed">
                    <thead class="cf sorthead">
                        <tr>
                            <th>Id</th>
                            <th>Event Name</th>
                            <th>Banner</th>
                            <th>Is Active</th>
<!--                            <th><input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall"></th>-->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($streamings as $streaming)
                        <tr class="gradeX">
                            <td ><a href="/event/streaming/{{$streaming->id }}/edit">{{$streaming->id}}</a></td>
                            <td ><a href="/event/streaming/{{ $streaming->id }}/edit">{{$streaming->event_name}}</a></td>
                            <td >
                                <a href="/event/streaming/{{ $streaming->id }}/edit">
                                <img src="{{ config('constants.awsbaseurl').config('constants.eventstreaming').$streaming->banner_image}}" alt="Banner Image" style="width:60%;" />
                                </a>
                            </td>
                            <td>
                                @if($streaming->is_live=='1')
                                <span  class="text-success">Yes
                                @else  
                                <span class="text-danger">No
                                @endif    
                                </span>
                            </td>
<!--                            <td  class="center"> <input type="checkbox" class="uniformCheckbox" name="checkItem[]" value="{{$streaming->id}}"></td>-->
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        <!--Sortable Responsive Media Table end-->
        <div class="dataTables_paginate paging_bootstrap pagination">
            {!! $streamings->appends(Input::get())->render() !!}
        </div>
    </div><!-- end container -->

    <div class="control-group row-fluid">
        <div class="span12 span-inset">
<!--            <button type="submit" value="remove" class="btn btn-danger" name="remove-attendee">Remove</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>-->
            
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
                "aoColumnDefs": [{"bSortable": false, "aTargets": [3]}],
                "fnInitComplete": function () {
                    $(".dataTables_wrapper select").select2({
                        dropdownCssClass: 'noSearch'
                    });
                }
            });


            $('#speaker_type').change(function () {
                window.location = '{{url("event/manage-speaker")}}/' + '?type=' + $(this).val();
            });

            $('button[type=submit]').click(function () {
                //add-attendee   remove-attendee
                if ($(this).attr('name') == 'add-attendee') {

                    if ($('#Taglist').val().length == 0) {
                        alert("Please select atleast one profile to add.");
                        return false;
                    }
                }
                else if ($(this).attr('name') == 'remove-attendee') {
                    if ($('input[name="checkItem[]"]:checked').length == 0) {
                        alert("Please select atleast one profile to remove.");
                        return false;
                    } else {
                        if (!confirm('Do you want to remove selected attendee from event ?'))
                            return false;
                    }
                }
            });


        });
        
         $("#channel_sel").change(function () {
                    $(this).find("option:selected").each(function () {

                        if ($(this).attr("value").trim().length != 0) {

                            window.location = '{{url("event/streaming")}}' + '?channel=' + $(this).attr("value").trim();
                        }
                    });

                });
                
    </script>

    {!! Form::close() !!}

</div>

@stop