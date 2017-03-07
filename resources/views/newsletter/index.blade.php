@extends('layouts/master')
@section('title', 'News letter subscribers - BWCMS')
@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Subscriber List</small></h1>

        </div>
        <div class="panel-search container-fluid">
            <form class="form-horizontal" method="get" action="">
                <input type="hidden" name="channel" value="{{$subscriberChannel}}"/>
                <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                <button class="btn btn-search" type="submit"></button>
                @if(isset($_GET['keyword'])) 
                <a href="{{url("newsletter/subscriber")}}?channel={{$subscriberChannel}}"><button class="btn btn-default" type="button">Reset</button></a>
                @endif
            </form>
        </div>



        <script>
            $().ready(function () {
                $(".uniformRadio").uniform({
                    radioClass: 'uniformRadio'
                });
                $("#channel_sel").change(function () {
                    $(this).find("option:selected").each(function () {

                        if ($(this).attr("value").trim().length != 0) {

                            window.location = '{{url("newsletter/subscriber")}}' + '?channel=' + $(this).attr("value").trim();
                        }
                        else{
                            window.location = '{{url("newsletter/subscriber")}}';
                        }

                       

                    });

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
                                    "title": "Subscriber List",
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
                <a href="javascript:;">Newsletter Subscriber</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Newsletter Subscriber</small></h2>
    </header>
    <div class="form-horizontal">

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
                            <option value="">
                                All channels
                            </option>
                            @foreach($channels as $channel)
                            <option @if($channel->channel_id==$subscriberChannel) selected="selected" @endif value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
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
    </div>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
   
    <a href="/newsletter/subscriber/exportCsv?{{http_build_query($_GET)}}" >
        <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Export CSV</button>
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
                                <th>Subscriber Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subscription Date</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribers as $subscriber)
                            <tr class="gradeX"  id="rowCur{{$subscriber->id}}">
                                <td><a href="/subscribers/edit/{{ $subscriber->id }}">{{ $subscriber->id }}</a> <a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Published by: {{ $subscriber->first_name.' '.$subscriber->last_name }}"></a></td>
                                <td><a href="/subscribers/edit/{{ $subscriber->id }}">{{ $subscriber->first_name }} {{ $subscriber->last_name }}</a>
                                </td>
                                <td><a href="/subscribers/edit/{{ $subscriber->id }}">{{ $subscriber->email }}</a></td>
                                <td class="center"><a href="/subscribers/edit/{{$subscriber->id }}">{{ $subscriber->sub_date }}</a>
                                </td>
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
                        "aoColumnDefs": [{"bSortable": false, "aTargets": [2]}],
                        "fnInitComplete": function () {
                            $(".dataTables_wrapper select").select2({
                                dropdownCssClass: 'noSearch'
                            });
                        }
                    });
                  
                });

               

            </script>
            <div class="dataTables_paginate paging_bootstrap pagination">

                {!! $subscribers->appends(Input::get())->render() !!}
            </div>
        </div><!-- end container -->

    </form>
</div>
@stop