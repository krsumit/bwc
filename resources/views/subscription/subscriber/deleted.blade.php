@extends('layouts/master')

@section('title', 'Deleted Subscriber  list- BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Deleted Subscriber</small></h1>

        </div>
        <div class="panel-search container-fluid">
            <form class="form-horizontal" method="get" action="">
                <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                <button class="btn btn-search" type="submit"></button>
                @if(isset($_GET['keyword'])) 
                <a href="{{url("subscribers/deleted")}}"><button class="btn btn-default" type="button">Reset</button></a>
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
                                    "title": "Deleted Subscriber list",
                                    "attr": {"href": "#Basic_Non-responsive_Table"}
                                }
                            },
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
                <a href="/dashboard">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li class="current">
                <a href="javascript:;">Deleted Subscriber list</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Deleted Subscribers list</small></h2>
    </header>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
    <a href="/subscribers/create" >
    <a href="/subscribers/create" >
        <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Create New Subscriber</button>
    </a>
    </div>
    <form class="form-horizontal">

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
            <!--Sortable Non-responsive Table begin-->
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped" id="tableSortable">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Subscriber Name</th>
										  <th>Email</th>
                                <th>Mobile</th>
                                <th>Orders</th>
                                <th>Updated Date</th>
                                <th><input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall"></th>
                            </tr>
                        </thead>
                        </thead>
                        </thead>
                        </thead>
                        </thead>
                        </thead>
                        </thead>
                        </thead>
                        </thead>
                        </thead>
                        <tbody>

                            @foreach($subscribers as $subscriber)
                            <tr class="gradeX"  id="rowCur{{$subscriber->id}}">
                                <td><a href="/subscribers/edit/{{$subscriber->id}}">{{ $subscriber->id }}</a> </td>
                                <td><a href="/subscribers/edit/{{$subscriber->id}}">{{ $subscriber->first_name }} {{ $subscriber->last_name }}</a></td>
										  <td><a href="/subscribers/edit/{{$subscriber->id}}"> {{ $subscriber->email }}</a></td>	
                                <td><a href="/subscribers/edit/{{$subscriber->id}}">{{ $subscriber->mobile }}</a></td>
                                <td><a href="/subscriber/orders/{{$subscriber->id}}"><button class="btn btn-default" type="button">Orders</button></a></td>
                                <td class="center">
                                    <a href="/subscribers/edit/{{$subscriber->id}}">{{ date('d-M-y',strtotime($subscriber->updated_at))}}</a>
                                </td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" name="checkItem[]" value="{{ $subscriber->id }}"></td>
                            </tr>
                            @endforeach
                           
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Sortable Non-responsive Table end-->


            <script>
                $(document).ready(function () {
                    $('#tableSortable').dataTable({
                        bInfo: false,
                        bPaginate: false,
                        "aaSorting": [],
                        "aoColumnDefs": [{"bSortable": false, "aTargets": [6]}],
                        "fnInitComplete": function () {
                            $(".dataTables_wrapper select").select2({
                                dropdownCssClass: 'noSearch'
                            });
                        }
                    });
                    //                            $("#simpleSelectBox").select2({
                    //                                dropdownCssClass: 'noSearch'
                    //                            });
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

                function activateSubscriber() {
                    var ids = '';
                    var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                        //var row = 'rowCur' + this.value;
                        //$("#" + row).hide();
                        return this.value;
                    }).get();
                    if (checkedVals.length > 0) {
                        if(confirm('Are you sure, want to activate the selected Subscriber(s) ? ')){
                        var ids = checkedVals.join(",");
                        //alert(ids);return false;/
                        $.get("{{ url('/subscribers/activate')}}",
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
                            <span></span>Selected records activated.</div>');
                            } else {
                                alert(data);
                            }
                            //alert(1);
                        });
                       } 
                    } else {
                        alert('Please select at least one record.');
                    }
                }

            </script>
            <div class="dataTables_paginate paging_bootstrap pagination">

                {!! $subscribers->appends(Input::get())->render() !!}
            </div>
        </div><!-- end container -->


        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button type="button" class="btn btn-success" onclick="activateSubscriber()">Activate</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
            </div>
        </div>
    </form>
</div>
@stop
