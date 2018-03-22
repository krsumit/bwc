@extends('layouts/master')

@section('title', 'Activity logs - BWCMS')


@section('content') 
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Activity logs</small></h1>

        </div>
        <div class="panel-search container-fluid">
            <form class="form-horizontal" method="get" action="">
                <div class="controls">
                    Keyword
                    <input id="panelSearch" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">       
                </div>

                <div class="controls">
                    Start Date
                    <input type="text" id="datepicker" class="span3" name="startdate" value="@if(isset($get->startdate)){{$get->startdate}} @endif"/>
                </div>
                <div class="controls">
                    End Date
                    <input type="text" id="datepicker2" class="span3" name="enddate" value="@if(isset($get->enddate)){{$get->enddate}} @endif"/>
                </div>


                <button type="submit" class="btn btn-info">Search</button>
                <!--                <button class="btn btn-search" type="submit"></button>-->
                @if(isset($_GET['keyword'])) 
                <a href="event/logs"><button class="btn btn-default" type="button">Reset</button></a>
                @endif

            </form>
        </div>

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
                <a href="javascript:;">Activity logs</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Activity logs</small></h2>
    </header>
    <div class="form-horizontal">


    </div>
    <form class="form-horizontal" action="" method="get">
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
        <script>
            $(function () {

                $("#selectBoxFilter").select2();

                $("#selectBoxFilter6").select2();
                $("#selectBoxFilter6").change(function () {
                    $(this).find("option:selected").each(function () {
                        if ($(this).attr("value") == "1") {
                            $("#selectBoxFilter7").select2();
                            $("#tabState").show();
                        } else {
                            $("#selectBoxFilter7").select2();
                            $("#tabState").hide();
                        }

                    });
                }).change();


                $("#datepicker").datepicker();
                $("#datepickerInline").datepicker();
                $("#datepickerMulti").datepicker({
                    numberOfMonths: 3,
                    showButtonPanel: true
                });
                $('#timeEntry').timeEntry().change();


                $("#datepicker2").datepicker();
                $("#datepickerInline").datepicker();
                $("#datepickerMulti").datepicker({
                    numberOfMonths: 3,
                    showButtonPanel: true
                });
                $('#timeEntry').timeEntry().change();
            });
        </script> 
        <div class="container-fluid">

            <!--Sortable Non-responsive Table begin-->
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped" id="tableSortable">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Date/Time</th>
                                <th>Log Details</th>
                                <th>&nbsp;</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                            <tr>
                                <td>{{$activity->name}}</td>
                                <td>{{$activity->created_at}}</td>
                                <td>{{$activity->notification}}
                                        <br>
                                    <p class="noti_info" style="display:none;">
                                        <b>Details:<br></b>
                                        {{$activity->notification_info}}
                                    </p>
                                </td>
                                <td><a class="show_details" href="javascript:;">Details</a></td>
                             </tr>                         
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Sortable Non-responsive Table end-->

            <div class="dataTables_paginate paging_bootstrap pagination">

            </div>
            <script>
                $(document).ready(function () {
                    $('#tableSortable').dataTable({
                        bInfo: false,
                        bPaginate: false,
                        "aaSorting": [],
                        //"aoColumnDefs": [{"bSortable": false, "aTargets": [3]}],
                        "fnInitComplete": function () {
                            $(".dataTables_wrapper select").select2({
                                dropdownCssClass: 'noSearch'
                            });
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
                    $(".show_details").click(function(){
                        //$('.noti_info').hide();
                        $(this).parent().siblings().find('.noti_info').toggle('slow');
                    });
                });

            </script>
        </div><!-- end container -->
        <!--        <div class="control-group row-fluid">
                    <div class="span12 span-inset">
                        <button type="button" onclick="deleteAuthor()" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>							
                    </div>
                </div>-->
    </form>
</div>
@stop