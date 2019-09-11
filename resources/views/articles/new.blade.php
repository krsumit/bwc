@extends('layouts/master')

@section('title', 'New Articles - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>New Articles</small></h1>

        </div>
        <div class="panel-search container-fluid">
            <form class="form-horizontal" method="get" action="">
                <input type="hidden" name="channel" value="{{$currentChannelId}}"/>
                <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                <button class="btn btn-search" type="submit"></button>
                @if(isset($_GET['searchin'])) 
                <a href="{{url("article/list/new")}}?channel={{$currentChannelId}}"><button class="btn btn-default" type="button">Reset</button></a>
                @endif
                <label class="radio">
                    <input type="radio"  @if(isset($_GET['searchin'])) @if($_GET['searchin']=='title') checked @endif @endif required name="searchin" class="uniformRadio" value="title">
                           Search by Article Title
                </label>
                <label class="radio">
                    <input type="radio" @if(isset($_GET['searchin'])) @if($_GET['searchin']=='article_id') checked @endif @endif required name="searchin" class="uniformRadio" value="article_id">
                           Search by Article ID
                </label>
                <label class="radio">
                    <input type="radio" @if(isset($_GET['searchin'])) @if($_GET['searchin']=='author') checked @endif @endif required name="searchin" class="uniformRadio" value="author">
                           Search by Reporter Name
                </label>

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

                            window.location = '{{url("article/list/new")}}' + '?channel=' + $(this).attr("value").trim();
                        }

                        else if ($(this).attr("value") == "none") {

                            $("#quote_list").hide();

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
                                    "title": "New Articles",
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
                <a href="javascript:;">New Articles</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>New Articles</small></h2>
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
                    });</script>
            </div>

            <!--Select Box with Filter Search end-->
        </div>
    </div>


    <form class="form-horizontal">

        <div class="container-fluid" id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')))style="display: none" @endif >

             <div class="form-legend" id="Notifications">Notifications</div>

            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    @if (Session::has('message'))
                    <div class="alert alert-success alert-block" style="">
                        <i class="icon-alert icon-alert-info"></i>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>This is Success Notification</strong>
                        <span>{{ Session::get('message') }}</span>
                    </div>
                    @endif
                    <div class="alert alert-block" style="display:none">
                        <i class="icon-alert icon-alert-info"></i>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>This is Alert Notification</strong>
                        <span>No result found.</span>
                    </div>
                    @if (Session::has('error'))
                    <div class="alert alert-error alert-block">
                        <i class="icon-alert icon-alert-info"></i>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>This is Error Notification</strong>
                        <span>{{ Session::get('error') }}</span>
                    </div>
                    @endif
                    <div class="alert alert-error alert-block"style="display:none">
                        <i class="icon-alert icon-alert-info"></i>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>This is Error Notification</strong>
                        <span>Please enter a valid email id.</span>
                    </div>

                </div>
            </div>

        </div>

        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped" id="tableSortable">
                        <thead>
                            <tr>
                                <th>Article ID</th>
                                <th>Title</th>
                                <th>Editor</th>
                                <th>Reporter Name</th>
                                <th>Date,Time</th>
                                <th><input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall"></th>
                            </tr>
                        </thead>
                        <tbody>
<!--                            <tr class="gradeX">
                            <td><a href="create-new-articles.html">234567890987654</a></td>
                            <td><a href="create-new-articles.html">English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</a>
                            </td>
                            <td><a href="create-new-articles.html">Brigadier CHITRANJAN SAWANT,VSM</a></td>
                            <td class="center"><a href="create-new-articles.html">11/03/2013</a>
                                <a href="create-new-articles.html">12:13 pm</a>
                            </td>
                            <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                        </tr>-->
                            @foreach($articles as $article)
                            <tr class="gradeC" id="rowCur{{$article->article_id}}">
                                <td>{{$article->article_id}}
                                    @foreach($editor as $ed)
                                    @if($article->article_id == $ed->article_id)
                                    <a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Locked by: {{ $ed->name }}"><i class="icon-photon info-circle"></i></a>
                                    @endif
                                    @endforeach
                                </td>
                                <td>
                                    <a href="/article/{{ $article->article_id }}">
                                        {{$article->title}}
                                    </a>
                                </td>
                                <td>{{$article->username}}</td>
                                <td>{{$article->name}}</td>
                                <td class="center">{{$article->created_at}}
                                </td>
                                <td class="center"> 
                                    <input type="checkbox" class="uniformCheckbox" name="checkItem[]" value="{{ $article->article_id }}" >
                                 @if($article->locked_by>0)
                                    @if(in_array('14',Session::get('user_rights')) || Auth::user()->id==$article->locked_by)
                                    <a href="{{url('article/unlock/'.$article->article_id)}}?destination={{urlencode(Request::fullUrl())}}" title="Locked by {{$article->locker_name.'. At: '.date('h:i a,d-M-Y',strtotime($article->locked_at))}},Once you unlock,Anyone can edit." onclick="return confirm('Unlocking this article may remove unsaved changes,Do you want to continue ?')">
                                        <button type="button"  class="btn btn-success">Locked</button>
                                    </a>
                                    @else
                                    <button type="button" title="Locked by {{$article->locker_name.'. At: '.date('h:i a,d-M-Y',strtotime($article->locked_at))}}" class="btn btn-warning">Locked</button>
                                    @endif
                                    @endif
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
                    $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable({
                        bInfo: false,
                        bPaginate: false,
                        "aaSorting": [],
                        "aoColumnDefs": [{"bSortable": false, "aTargets": [5]}],
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
                function deleteArticle() {
                    var ids = '';
                    var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                        //var row = 'rowCur' + this.value;
                        //("#" + row).hide();
                        return this.value;
                    }).get();
                    if (checkedVals.length > 0) {

                        var ids = checkedVals.join(",");

                        $.get("{{ url('/article/delete/?channel=').$currentChannelId}}",
                                {option: ids},
                        function (data) {
                            if (data.trim() == 'success') {
                                $.each(checkedVals, function (i, e) {
                                    var row = 'rowCur' + e;
                                    $("#" + row).remove();
                                });
                                $('#notificationdiv').show();
                                $('#notificationdiv .control-group .span12.span-inset').html('<div class="alert alert-success alert-block">\n\
                            <i class="icon-alert icon-alert-info"></i><button type="button" class="close" data-dismiss="alert">\n\
                            &times;</button><strong>This is Success Notification</strong>\n\
                            <span></span>Selected records dumped.</div>');
                            } else {
                                alert(data);
                            }

                            //alert(1);
                        });

                    } else {
                        alert('Please select at least one record.');
                    }

                }

                function publishArticle() {
                    var ids = '';
                    var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                        //var row = 'rowCur' + this.value;
                        //$("#" + row).hide();
                        return this.value;
                    }).get();
                    if (checkedVals.length > 0) {
                        var ids = checkedVals.join(",");
                        //alert(ids);return false;
                        $.get("{{ url('/article/publish/?channel=').$currentChannelId}}",
                                {option: ids},
                        function (data) {
                            if (data.trim() == 'success') {
                                $.each(checkedVals, function (i, e) {
                                    var row = 'rowCur' + e;
                                    $("#" + row).remove();
                                });
                                $('#notificationdiv').show();
                                $('#notificationdiv .control-group .span12.span-inset').html('<div class="alert alert-success alert-block">\n\
             <i class="icon-alert icon-alert-info"></i><button type="button" class="close" data-dismiss="alert">\n\
             &times;</button><strong>This is Success Notification</strong>\n\
             <span></span>Selected records published.</div>');
                            } else {
                                alert(data);
                            }

                        });
                    } else {
                        alert('Please select at least one record.');
                    }
                }

            </script>
            <div class="dataTables_paginate paging_bootstrap pagination">

                {!! $articles->appends(Input::get())->render() !!}
            </div>
        </div><!-- end container -->
        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                @if(in_array('13',Session::get('user_rights')))
                <button type="button" onclick="deleteArticle()" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                @endif
                @if(in_array('12',Session::get('user_rights')))
                <button class="btn btn-success" onclick="publishArticle()" type="button">Publish</button>
                @endif

            </div>
        </div>
    </form>
</div>


@stop