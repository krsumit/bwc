@extends('layouts/master')
@section('title', 'Attribute - BWCMS')
@section('content') 
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Attribute</small></h1>

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
                <a href="/attributes"><button class="btn btn-default" type="button">Reset</button></a>
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
                <a href="javascript:;">Attribute</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Attribute</small></h2>
    </header>
   <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/attributes/create" >
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="button">Create Attribute</button>
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
        @if(count($attributes)>0)
            {!! Form::open(array('url'=>'attributes/'.$attributes[0]->id,'class'=> 'form-horizontal','id'=>'group_list_from','enctype'=>'multipart/form-data')) !!}
            {!! csrf_field() !!}
            {!! method_field('DELETE') !!}  
            <div class="container-fluid">

                <!--Sortable Non-responsive Table begin-->
                <div class="row-fluid">
                    <div class="span12">
                        <table class="table table-striped" id="tableSortable">
                            <thead>
                                <tr>
                                    <th>Group ID</th>
                                    <th>Name</th>
                                    <th>Product Type</th>
                                    <th><input type="checkbox" class="uniformCheckbox" value="checkbox1"  id="selectall"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attributes as $attribute)
                                <tr class="gradeX" id="rowCur{{$attribute->id}}">
                                    <td><a href="/attributes/{{$attribute->id}}/edit">{{$attribute->id}}</a> </td>
                                    <td><a href="/attributes/{{$attribute->id}}/edit">{{$attribute->name}}</a></td>
                                    <td><a href="/attributes/{{$attribute->id}}/edit">{{$attribute->type_name}}</a></td>
                                    <td class="center"> 
                                        <input type="checkbox" class="uniformCheckbox" value="{{$attribute->id}}" name="checkItem[]">
                                        @if($attribute->type=='selectbox' || $attribute->type=='multiselect')
                                        <a href="/attribute-values/{{$attribute->id}}">
                                            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="button">Manage Values</button>
                                        </a> 
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Sortable Non-responsive Table end-->

                <div class="dataTables_paginate paging_bootstrap pagination">
                    {!! $attributes->appends(Input::get())->render() !!}
                </div>
                <script>
                    $(document).ready(function () {
                        $('#tableSortable').dataTable({
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


                    function deleteAttribute() {
                            var ids = '';
                            var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                                return this.value;
                            }).get();
                            if (checkedVals.length > 0) {
                                var ids = checkedVals.join(",");
                                $('#group_list_from').submit();

                            } else {
                                alert('Please select at least one record.');
                                }
                        }


                </script>
            </div><!-- end container -->
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="button" onclick="deleteAttribute()" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>							
                </div>
            </div>
            {!! Form::close() !!}
        @else
        <div class="container-fluid">
            No data available
        </div>
        @endif    
</div>
@stop