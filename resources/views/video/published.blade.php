@extends('layouts/master')

@section('title', 'Published Video - BWCMS')

@section('content')
<?php //echo count($qbytes);exit; ?> 
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Published Video</small></h1>
        </div>
        <div class="panel-search container-fluid" style="height: 100px">
            <form class="form-horizontal" action="">
                 <input type="hidden" name="channel" value="{{$currentChannelId}}"/>
                <input id="panelSearch" required placeholder="Search" type="text" value="{{$_GET['keyword'] or ''}}" name="keyword">
                <button class="btn btn-search" type="submit"></button>
                @if(isset($_GET['searchin'])) 
                <a href="{{url("video/list?channel=").$currentChannelId}}"><button class="btn btn-default" type="button">Reset</button></a>
                @endif
               
                <label class="radio">
                    <input type="radio"  @if(isset($_GET['searchin'])) @if($_GET['searchin']=='title') checked @endif @endif required name="searchin" class="uniformRadio" value="title">
                           Search by Video Title
                </label>
                <label class="radio">
                    <input type="radio" @if(isset($_GET['searchin'])) @if($_GET['searchin']=='id') checked @endif @endif required name="searchin" class="uniformRadio" value="id">
                           Search by Video ID
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
            <h1><small>Page Navigation Shortcuts</small></h1>
        </div>
        <script type="text/javascript">
            $(function () {
                $("#jstree").jstree({
                    "json_data": {
                        "data": [
                            {
                                "data": {
                                    "title": "Published Video",
                                    "attr": {"href": "#tableSortableResMed_wrapper"}
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
                <a href="dashboard.html">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li class="current">
                <a href="javascript:;">Published Video</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Published Video</small></h2>

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
                            @foreach($channels as $channel)
                            <option @if($channel->channel_id==$currentChannelId) selected="selected" @endif value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <script>
                    $().ready(function () {
                        $("#channel_sel").select2();
                        
                        $("#channel_sel").change(function () {
                    $(this).find("option:selected").each(function () {

                        if ($(this).attr("value").trim().length != 0) {

                            window.location = '{{url("video/list")}}' + '?channel=' + $(this).attr("value").trim();
                        }

                        else if ($(this).attr("value") == "none") {

                            $("#quote_list").hide();

                        }

                    });

                });
                
                        
                    });</script>
            </div>

            <!--Select Box with Filter Search end-->
        </div>
    </div>
    
    <form class="form-horizontal">
        <div class="container-fluid " id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')))style="display: none" @endif >

             <div class="form-legend" id="Notifications" >Notifications</div>

            <!--Notifications begin-->
            <div class="control-group row-fluid" >
                <div class="span12 span-inset">
                    @if(Session::has('message'))
                    <div class="alert alert-success alert-block">
                        <i class="icon-alert icon-alert-info"></i>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>This is Success Notification</strong>
                        <span>{{ Session::get('message') }}</span>
                    </div>
                    @endif
                    @if(Session::has('error'))
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


            <!--Sortable Responsive Media Table begin-->
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped table-responsive" id="tableSortableResMed">
                        <thead class="cf sorthead">
                            <tr>
                                <th>Video ID</th>
                                <th>Thumb Image</th>
                                <th>Title</th>                
                                <th>Update Date</th>
                                <th  data-defaultsort="disabled"><input type="checkbox" class="uniformCheckbox" value="checkbox1"  id="selectall"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($videos as $qb)
                            
                            <tr class="gradeX" id="rowCur{{$qb->id}}">
                                <td>{{$qb->id}}
                                    <!--<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="No. of Photos"><i class="icon-photon info-circle"></i></a> -->
                                </td>
                                <td style="width:160px;"><img src="{{ config('constants.awsbaseurl').config('constants.awvideothumb').$qb->video_thumb_name}}" alt="user" style="width:70%;" />
                                </td>
                                <td><a href="/video/{{$qb->id}}">{{$qb->video_title}}</a></td>
                                <td>{{$qb->updated_at}}</td>
                                <td class="center"><input type="checkbox" name="checkItem[]" class="uniformCheckbox" value="{{$qb->id}}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            <!--Sortable Responsive Media Table end-->
             <div class="dataTables_paginate paging_bootstrap pagination">
                    
                {!! $videos->appends(Input::get())->render() !!}
                </div>
        </div><!-- end container -->
        <script>
            $(document).ready(function () {
                $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable({
                    bInfo: false,
                     bPaginate:false,
                     "aaSorting": [] ,
                     "aoColumnDefs": [ { "bSortable": false, "aTargets": [4] } ],

                    "fnInitComplete": function () {
                        $(".dataTables_wrapper select").select2({
                            dropdownCssClass: 'noSearch'
                        });
                    }
                });
                //                            $("#simpleSelectBox").select2({
                //                                dropdownCssClass: 'noSearch'
                //                            });
                $('#selectall').click(function(){
                   if($(this).is(':checked')) {
                       $('input[name="checkItem[]"]').each(function(){
                           $(this).attr('checked','checked');
                       });
                   }else{
                        $('input[name="checkItem[]"]').each(function(){
                           $(this).removeAttr('checked');
                       });
                   }
                });
            });
            function deleteVideo() {
                        var ids = '';
                        var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                            var row = 'rowCur' + this.value;
                            $("#" + row).hide();
                            return this.value;
                        }).get();
                        var ids = checkedVals.join(",");
                        //alert(ids);return false;
                        $.get("{{ url('/video/delete/')}}",
                                {option: ids},
                        function (data) {
                            $.each(checkedVals, function (i, e) {
                                var row = 'rowCur' + e;
                                $("#" + row).hide();
                            });
                            $('#notificationdiv').show();
                            $('#notificationdiv .control-group .span12.span-inset').html('<div class="alert alert-success alert-block">\n\
                                <i class="icon-alert icon-alert-info"></i><button type="button" class="close" data-dismiss="alert">\n\
                                &times;</button><strong>This is Success Notification</strong>\n\
                                <span></span>Selected records dumped.</div>');
                           
                            //alert(1);
                        });
               }
        </script>
        
        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                 @if(in_array('65',Session::get('user_rights')))
                <button type="button" onclick="deleteVideo();" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                 @endif
            </div></div>
    </form>
</div>
@stop