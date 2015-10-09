@extends('layouts/master')

@section('title', 'Published Album - BWCMS')

@section('content')
<?php //echo count($qbytes);exit; ?> 
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Published Album</small></h1>
        </div>
        <div class="panel-search container-fluid" style="height: 100px">
            <form class="form-horizontal" action="">
                <input id="panelSearch" required placeholder="Search" type="text" value="{{$_GET['keyword'] or ''}}" name="keyword">
                <button class="btn btn-search" type="submit"></button>
                @if(isset($_GET['searchin'])) 
                <a href="{{url("album/list/published")}}"><button class="btn btn-default" type="button">Reset</button></a>
                @endif
               
                <label class="radio">
                    <input type="radio"  @if(isset($_GET['searchin'])) @if($_GET['searchin']=='title') checked @endif @endif required name="searchin" class="uniformRadio" value="title">
                           Search by Article Title
                </label>
                <label class="radio">
                    <input type="radio" @if(isset($_GET['searchin'])) @if($_GET['searchin']=='id') checked @endif @endif required name="searchin" class="uniformRadio" value="id">
                           Search by Article ID
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
                                    "title": "Published Album",
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
                <a href="javascript:;">Published Album</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Published Album</small></h2>

    </header>
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
                                <th>Album ID</th>
                                <th>Album Photo</th>
                                <th>Title</th>
                                <th>Update Date</th>
                                <th  data-defaultsort="disabled"><input type="checkbox" class="uniformCheckbox" name="selectall" id="selectall" value="checkbox1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($albums as $qb)
                            <?php //print_r($qb);exit;?>
<?php //echo '<pre>'; print_r($qb);exit;  ?>
                            <tr class="gradeX" id="rowCur{{$qb->id}}">
                                <td>{{$qb->id}}<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="No. of Photos"><i class="icon-photon info-circle"></i></a></td>
                                <td style="width:160px;"><img src="{{ url(config('constants.albumimagedir').$qb->photopath) }}" alt="Album Image" style="width:70%;" /></td>
                                <td><a href="/album/{{$qb->id}}">{{$qb->title}}</a></td>
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
                    
                {!! $albums->appends(Input::get())->render() !!}
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
            function deleteAlbum() {
                        var ids = '';
                        var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                            var row = 'rowCur' + this.value;
                            $("#" + row).hide();
                            return this.value;
                        }).get();
                        var ids = checkedVals.join(",");
                        //alert(ids);return false;
                        $.get("{{ url('/album/delete/')}}",
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
                <button type="button" onclick="deleteAlbum();" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
            </div></div>
    </form>
</div>
@stop