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
                    <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                    <button class="btn btn-search" type="submit"></button>
                     @if(isset($_GET['searchin'])) 
                    <a href="{{url("article/list/new")}}"><button class="btn btn-default" type="button">Reset</button></a>
                    @endif
<!--                    <script>
                        $().ready(function(){
                            var searchTags = [
                                "Dashboard",
                                "Form Elements",
                                "Graphs and Statistics",
                                "Typography",
                                "Grid",
                                "Tables",
                                "Maps",
                                "Sidebar Widgets",
                                "Error Pages",
                                "Help",
                                "Input Fields",
                                "Masked Input Fields",
                                "Autotabs",
                                "Text Areas",
                                "Select Menus",
                                "Other Form Elements",
                                "Form Validation",
                                "UI Elements",
                                "Graphs",
                                "Statistical Elements",
                                "400 Bad Request",
                                "401 Unauthorized",
                                "403 Forbidden",
                                "404 Page Not Found",
                                "500 Internal Server Error",
                                "503 Service Unavailable"
                            ];
                            $( "#panelSearch" ).autocomplete({
                                source: searchTags
                            });
                        });
                    </script>-->
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
                $().ready(function(){
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
                        "json_data" : {
                            "data" : [
                                {
                                    "data" : {
                                        "title" : "New Articles",
                                        "attr" : { "href" : "#Basic_Non-responsive_Table" }
                                    }
                                },

                            ]
                        },
                        "plugins" : [ "themes", "json_data", "ui" ]
                    })
                            .bind("click.jstree", function (event) {
                                var node = $(event.target).closest("li");
                                document.location.href = node.find('a').attr("href");
                                return false;
                            })
                            .delegate("a", "click", function (event, data) { event.preventDefault(); });
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
                                <th>Author</th>
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
                                <td>@if($article->locked_by == 0)<a href="/article/{{ $article->article_id }}">@endif{{$article->title}}@if($article->locked_by == 0)</a>@endif
                                </td>
                                <td>{{$article->username}}</td>
                                <td>{{$article->name}}</td>
                                <td class="center">{{$article->publish_date}}
                                    {{$article->publish_time}}</td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" name="checkItem[]" value="{{ $article->article_id }}" ></td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <!--Sortable Non-responsive Table end-->


                <script>
                    $(document).ready(function() {
                        $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable( {
                           bInfo: false,
                              bPaginate:false,
                              "aaSorting": [] ,
                              "aoColumnDefs": [ { "bSortable": false, "aTargets": [5] } ],
                            "fnInitComplete": function(){
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
                     function deleteArticle() {
                        var ids = '';
                        var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                            var row = 'rowCur' + this.value;
                            $("#" + row).hide();
                            return this.value;
                        }).get();
                        var ids = checkedVals.join(",");
                        //alert(ids);return false;
                        $.get("{{ url('/article/delete/')}}",
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
                    
           function publishArticle() {
            var ids = '';
            var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                var row = 'rowCur' + this.value;
                $("#" + row).hide();
                return this.value;
            }).get();
            var ids = checkedVals.join(",");
            //alert(ids);return false;
            $.get("{{ url('/article/publish/')}}",
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
                 <span></span>Selected records published.</div>');
            });
            }
        
                </script>
                 <div class="dataTables_paginate paging_bootstrap pagination">
                    
                {!! $articles->appends(Input::get())->render() !!}
                </div>
            </div><!-- end container -->
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="button" onclick="deleteArticle()" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                    <button class="btn btn-success" onclick="publishArticle()" type="button">Publish</button>
                   
                </div>
            </div>
        </form>
    </div>


@stop