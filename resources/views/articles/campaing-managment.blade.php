@extends('layouts/master')

@section('title', 'Add-edit-sub Master category - BWCMS')


@section('content')        
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Campaign Management</small></h1>

        </div>
        <div class="panel-search container-fluid">
            <form class="form-horizontal" method="get" action="">
                <input name="channel" type="hidden" value="{{$currentChannelId}}" />
                <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                <button class="btn btn-search" type="submit"></button>
                @if(isset($_GET['keyword'])) 
                <a href="/campaing/add-management?channel={{$currentChannelId}}"><button class="btn btn-default" type="button">Reset</button></a>
                @endif

            </form>
        </div>

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
                                    "title": "Channel",
                                    "attr": {"href": "#channel"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Campaign",
                                    "attr": {"href": "#fb"}
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
                <a href="javascript:;">
                    Campaign Management
                </a>
            </li>
        </ul>
    </div>      <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Campaign Management</small></h2>
    </header>
    <form class="form-horizontal" id="validation_form" enctype= "multipart/form-data" method="POST" action="/campaing/add" onsubmit="return validatecampaingData()">
        {!! csrf_field() !!}
        <div class="container-fluid">

            <div class="form-legend" id="Notifications">Notifications</div>

            <!--Notifications begin-->
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    @if (Session::has('message'))
                    <div class="alert alert-success alert-block">
                        <i class="icon-alert icon-alert-info"></i>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>This is Success Notification</strong>
                        <span>{{ Session::get('message') }}</span>
                    </div>
                    @endif 

                </div>
            </div>
            <!--Notifications end-->
        </div>
        <div class="container-fluid">

            <div class="form-legend" id="channel">Channel</div>

            <!--Select Box with Filter Search begin-->
            <div id="assign-article-to-a-Issue" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Channel</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="channel" id="selectBoxFilter20">
                            @foreach($channels as $channel)
                            <option @if($channel->channel_id ==$currentChannelId) selected="selected" @endif value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <script>
                    $().ready(function () {
                        $("#selectBoxFilter20").select2();
                        $("#selectBoxFilter20").change(function () {
                            $(this).find("option:selected").each(function () {

                                if ($(this).attr("value").trim().length != 0) {

                                window.location = '{{url("campaing/add-management")}}' + '?channel=' + $(this).attr("value").trim();
                            }

                            else if ($(this).attr("value") == "none") {

                            $("#quote_list").hide();
                        }

                        });
                    });
                    });                </script>
            </div>

            <!--Select Box with Filter Search end-->

            <!--Select Box with Filter Search begin-->


            <!--Select Box with Filter Search end-->		
        </div>
        <input type="hidden" name="cid" id="cid" value="">
        <input type="hidden" name="p_photo" id="photo" value="">


        <div class="container-fluid">

            <div class="form-legend" id="fb">Campaign</div>


            <div id="Article-Details" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Title </label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input type="text" name="title" id="title">
                    </div>
                </div>
            </div>

            <!--Text Area Resizable begin-->
            <div id="Text_Area_Resizable" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Description</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <textarea  rows="4" class="" id="description" name="description"></textarea>
                    </div>
                </div>
            </div>
            <!--Text Area Resizable end-->
            <div id="File_Upload" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Photo</label>
                </div>
                <div class="span9">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="input-append">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input  name="photo"type="file"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end container-->

        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button type="submit" class="btn btn-info">Save</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>

            </div>
        </div>



        <div class="container-fluid">


            <!--Sortable Responsive Media Table begin-->
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped table-responsive" id="tableSortableResMed">
                        <thead class="cf sorthead">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>

                                <th><input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $a)
                            <tr class="gradeX" id="rowCur{{$a->campaign_id}}">
                                <td style="width:160px;"><img src="{{$a->url}}" alt="user" style="width:70%;" /></td>
                                <td ><a href="#"onclick="getEditcampaing({{$a->campaign_id}})">{{$a->title}}</a></td>

                                <td  class="center"><input type="checkbox" class="uniformCheckbox" value="{{$a->campaign_id}}" name="checkItem[]"></td>
                            </tr>
                            @endforeach


                        </tbody>
                    </table>

                </div>
            </div>
            <div class="dataTables_paginate paging_bootstrap pagination">

                {!! $posts->appends(Input::get())->render() !!}
            </div>
            <!--Sortable Responsive Media Table end-->

        </div><!-- end container -->

        <script>
            $(document).ready(function () {
                $('#tableSortable').dataTable({
                    "sPaginationType": "bootstrap",
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

            function deleteAuthor() {
                var ids = '';
                var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                    var row = 'rowCur' + this.value;

                    return this.value;
                }).get();

                // alert(2);
                var ids = checkedVals.join(",");
                //alert(ids);return false;
                $.get("{{ url('/campaing/delete/?channel=').$currentChannelId}}",
                        {option: ids},
                function (data) {
                    $.each(checkedVals, function (i, e) {
                        var row = 'rowCur' + e;
                        $("#" + row).remove();
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
                <button type="button" onclick="deleteAuthor()" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>							

            </div></div>
    </form>
</div>
<script>
    function validatecampaingData() {
        var valid = 1;
        $('.author_error').remove();
        $('#new input').removeClass('error');
        $('#new textarea').removeClass('error');
        if ($('select[name=channel]').val().trim() == 0) {
            valid = 0;
            $('select[name=channel]').addClass('error');
            $('select[name=channel]').after(errorMessage('Please enter channel'));
        }
        if ($('input[name=title]').val().trim() == 0) {
            valid = 0;
            $('input[name=title]').addClass('error');
            $('input[name=title]').after(errorMessage('Please enter title'));
        }


        //alert(valid);
        if (valid == 0)
            return false;
        else
            return true;
    }
    function errorMessage($msg) {
        return '<span class="error author_error">' + $msg + '</span>';
    }
    function getEditcampaing(id) {
        //alert(id);
        $.get("{{ url('/campaing/edit/')}}",
                {option: id},
        function (data) {
            //add to relevant fields
            //alert(data);

            var result = jQuery.parseJSON(data);

            var one;
            var two;
            $.each(result, function (index, element) {
                //alert(index);
                //alert(element);
                if (index == 0) {
                    one = element;
                } else {
                    two = element;
                }
            });
            $.each(one, function (ind, ele) {
                $.each(ele, function (index, element) {

                    //alert(index);
                    // alert(element);
                    //alert(element);
                    if (index == 'campaign_id') {

                        $('#cid').val(element);
                    }
                    if (index == 'title') {

                        $('#title').val(element);
                    }
                    if (index == 'description') {

                        $('#description').val(element);
                    }



                    if (index == 'url') {
                        //var p="";
                        $('#photo').val(element);

                    }

                });
            });
            //Loop on all tags, select the one selected

        });
    }
</script>
@stop
