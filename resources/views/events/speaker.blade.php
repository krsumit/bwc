@extends('layouts/master')

@section('title', 'Event Attendee - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Event Attendee</small></h1>
        </div>
        <div class="panel-search container-fluid">
<!--            <form class="form-horizontal" method="get" action="">
                <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                <button class="btn btn-search" type="submit"></button>
                @if(isset($_GET['searchin'])) 
                <a href="{{url("attendee")}}"><button class="btn btn-default" type="button">Reset</button></a>
                @endif
                <label class="radio">
                    <input type="radio" @if(isset($_GET['searchin'])) @if($_GET['searchin']=='name') checked @endif @endif name="searchin" class="uniformRadio" value="name">
                           Search by attendee Name
                </label>
                <label class="radio">
                    <input type="radio" @if(isset($_GET['searchin'])) @if($_GET['searchin']=='email') checked @endif @endif name="searchin" class="uniformRadio" value="email">
                           Search by attendee Email ID
                </label>
                
                <label class="radio">
                    <input type="radio" @if(isset($_GET['searchin'])) @if($_GET['searchin']=='company') checked @endif @endif name="searchin" class="uniformRadio" value="company">
                           Company
                </label>
            </form>-->
            
            <form class="form-horizontal" method="get" action="">
                <div class="controls">
                    Name/Email/Mob
                    <input id="panelSearch" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">       
                </div>
                <div class="controls">
                    Designation
                    <input id="panelSearch" value="{{$_GET['designation'] or ''}}" type="text" name="designation">       
                </div>
                <div class="controls">
                    Company
                    <input id="panelSearch" value="{{$_GET['company'] or ''}}" type="text" name="company">       
                </div>
                <div class="controls">
                    Location
                    <input id="panelSearch" value="{{$_GET['location'] or ''}}" type="text" name="location">       
                </div>
            
                 <button type="submit" class="btn btn-info">Search</button>
                @if((isset($_GET['keyword']))||(isset($_GET['designation']))||(isset($_GET['company']))||(isset($_GET['location']))) 
                        <a href="/attendee"><button class="btn btn-default" type="button">Reset</button></a>
                @endif

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
<!--        <div class="panel-header">
            <h1><small>Page Navigation Shortcuts</small></h1>
        </div> -->
<!--        <script type="text/javascript">
            $(function () {
                $("#jstree").jstree({
                    "json_data": {
                        "data": [
                            {
                                "data": {
                                    "title": "Add New Attendee",
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
                                    "title": "Attendee List",
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
        </script>-->
<!--        <div class="sidebarMenuHolder">
            <div class="JStree">
                <div class="Jstree_shadow_top"></div>
                <div id="jstree"></div>
                <div class="Jstree_shadow_bottom"></div>
            </div>
        </div>    -->
    </div>
    <div class="panel-slider">
        <div class="panel-slider-center">
            <div class="panel-slider-arrow"></div>
        </div>
    </div>
</div>
<div class="main-content">
    
    <form class="form-horizontal" method="post" action="attendee/delete">
       
       {!! csrf_field() !!}
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
        <a href="import/attendee">
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="button">Import Attendee</button>
        </a>&nbsp;&nbsp;&nbsp;
        <a href="attendee/create">
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="button">Add Attendee</button>
        </a>
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
                            <th>Details</th>
                            <th>Action</th>
                            <th><input type="checkbox" class="uniformCheckbox" value="checkbox1"  id="selectall"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($speakers as $speaker)
                        <tr class="gradeX">
                            <td style="width:160px;"><img src="{{ config('constants.awsbaseurl').config('constants.awspeakerdir').$speaker->photo}}" alt="User Image" style="width:70%;" /></td>
                            <td>{{$speaker->name}}</td>
                            <td>{{$speaker->email}}</td>
                             <td>
                                {{$speaker->designation}}
                                @if(trim($speaker->designation)) , @endif
                                {{$speaker->company}}
                                @if(trim($speaker->designation) || trim($speaker->company)) <br> @endif
                                {{$speaker->city}}
                            </td>
                            <td class="center"> 
                                <div class="btn-group">
                                    <button type="button" class="btn dropdown-toggle btn-mini" data-toggle="dropdown">Modify Detail<span class="caret"></span></button>
                                    <ul class="dropdown-menu">                                        
                                        <li><a href="/attendee/{{$speaker->id}}/edit">Edit</a></li>
                                        <li><a href="/attendee/{{$speaker->id}}">Profile-Detail</a></li>

                                    </ul>
                                </div>
                            </td>
                           
                            </td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" value="{{$speaker->id}}" name="checkItem[]"></td>
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
        
        
        <div class="control-group row-fluid">
        <div class="span12 span-inset">
            <button type="submit" value="remove" class="btn btn-danger" id="delete-attendee" name="delete-attendee">Dump</button>
            </div>
        </div>
        
        
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
                "aoColumnDefs": [{"bSortable": false, "aTargets": [4,5]}],
                "fnInitComplete": function () {
                    $(".dataTables_wrapper select").select2({
                        dropdownCssClass: 'noSearch'
                    });
                }
            });
            
            $("#delete-attendee").click(function(){
                 if($('input[name="checkItem[]"]:checked').length==0){
                        alert("Please select atleast one attendee to delete.");
                        return false;
                 }
                  if(!confirm('Do you want to delete selected attendee?'))
                            return false;
            });
            
            $('#selectall').click(function (){
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
    </script>
</form>

</div>

@stop