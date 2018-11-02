@extends('layouts/master')

@section('title', 'Grids - BWCMS')


@section('content') 
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Grids</small></h1>

        </div>
        <div class="panel-search container-fluid">
            <form class="form-horizontal" method="get" action="">
                <div class="controls">
                    Keyword
                    <input id="panelSearch" value="{{$_GET['keyword'] or ''}}" type="text" required="required" name="keyword">      
                </div>
                 <button type="submit" class="btn btn-info">Search</button>
                <!--                <button class="btn btn-search" type="submit"></button>-->
                @if(isset($_GET['keyword'])) 
                <a href="/grids"><button class="btn btn-default" type="button">Reset</button></a>
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
                <a href="javascript:;">Grids</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Grids</small></h2>
    </header>
   <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/grids/create" >
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Create Grid</button>
        </a>
    </div>
  
        <div class="container-fluid" id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')))style="display: none" @endif >
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
                    });</script>
            </div>

            <!--Select Box with Filter Search end-->
            </div>
        </div>
        @if(count($grids)>0)
            {!! Form::open(array('url'=>'grids/'.$grids[0]->id,'class'=> 'form-horizontal','id'=>'brands_list_from','enctype'=>'multipart/form-data')) !!}
            {!! csrf_field() !!}
            {!! method_field('DELETE') !!}  
            <div class="container-fluid">
                <!--Sortable Non-responsive Table begin-->
                <div class="row-fluid">
                    <div class="span12">
                        <table class="table table-striped" id="tableSortable">
                            <thead>
                                <tr>
                                    <th>Grid ID</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th></th>
                                    <th><input type="checkbox" class="uniformCheckbox" value="checkbox1"  id="selectall"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grids as $grid)
                                <tr class="gradeX" id="rowCur{{$grid->id}}">
                                    <td><a href="/grids/{{$grid->id}}/edit">{{$grid->id}}</a> </td>
                                    <td><a href="/grids/{{$grid->id}}/edit">{{$grid->name}}</a></td>
                                    <td><a href="/grids/{{$grid->id}}">{{ucfirst($grid->type)}}</a></td>
                                    <td class="center"> 
                                        <div class="btn-group">
                                            <button type="button" class="btn dropdown-toggle btn-mini" data-toggle="dropdown">Manage Grid<span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                @if($grid->type!='review')
                                                <li><a href="/grid-rows/{{$grid->id}}">Manage Rows</a></li>
                                                <li class="divider"></li>
                                                @endif
                                                <li><a href="/grid-columns/{{$grid->id}}">Manage Column</a></li>
                                                <li class="divider"></li>
                                                <li><a href="/grid-products/{{$grid->id}}">Manage Products</a></li>
                                                <li class="divider"></li>

                                            </ul>
                                        </div>
                                    </td>
                                   <td class="center"> 
                                       <input type="checkbox" class="uniformCheckbox" value="{{$grid->id}}" name="checkItem[]"> 
                                   </td>
                                  
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Sortable Non-responsive Table end-->

                <div class="dataTables_paginate paging_bootstrap pagination">
                    {!! $grids->appends(Input::get())->render() !!}
                </div>
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


                    function deleteProductType() {
                            var ids = '';
                            var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                                return this.value;
                            }).get();
                            if (checkedVals.length > 0) {
                                var ids = checkedVals.join(",");
                                $('#brands_list_from').submit();

                            } else {
                                alert('Please select at least one record.');
                                }
                        }


                </script>
            </div><!-- end container -->
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="button" onclick="deleteProductType()" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>							
                </div>
            </div>
            {!! Form::close() !!}
        @else
        <div class="container-fluid">
            No data available
        </div>
        @endif    
        <script>
            $("#channel_sel").change(function (){
                //alert('{{url("product-types")}}' + '?channel=' + $(this).val());
                window.location = '{{url("grids")}}' + '?channel=' + $(this).val();
            });
        </script>
</div>
@stop