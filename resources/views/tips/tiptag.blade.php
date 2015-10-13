@extends('layouts/master')

@section('title', 'Tips Tag - BWCMS')


@section('content')

    <div class="panel">
        <div class="panel-content filler">
            <div class="panel-logo"></div>
            <div class="panel-header">
                <h1><small>Tags</small></h1>
            </div>
            <div class="panel-search container-fluid">
                <form action="javascript:;" class="form-horizontal">
                    <input type="text" name="panelSearch" placeholder="Search by Tags Name" id="panelSearch" class="ui-autocomplete-input" autocomplete="off"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><span class="ui-helper-hidden-accessible" aria-live="polite" role="status"></span><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                    <button class="btn btn-search"></button>
                    <script>
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
                    </script>
                </form>
            </div>

            <br><br>

            <script type="text/javascript">
                $(function () {
                    $("#jstree").jstree({
                        "json_data" : {
                            "data" : [

                                {
                                    "data" : {
                                        "title" : "Add A New Tag",
                                        "attr" : { "href" : "#add-new-tag" }
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

                function getEditTag(id) {
                    //alert(id);
                    $.get("{{ url('/tiptags/edit/')}}",
                            {option: id},
                            function (data) {
                                //add to relevant fields
                                //alert(data);
                                $.each(data, function(index, element) {
                                    //alert(element);
                                    //alert(element);
                                    if (index == 'ttag_id') {
                                        $('#ttagid').val(element);
                                    }
                                    if (index == 'tag') {
                                            $('#ttag').val(element);
                                    }
                                    if (index == 'sponsored_by') {
                                            $('#sponsoredby').val(element);
                                    }
                                    if (index == 'url') {
                                        $('#url').val(element);
                                    }
                                });

                            });
                }
                    
        function addtipstagfunction(){
            var valid=1;
             $('.author_error').remove();
            $('#new input').removeClass('error');
            if($('input[name=ttag]').val().trim()==0){
            valid=0;
            $('input[name=ttag]').addClass('error');
            $('input[name=ttag]').after(errorMessage('Please fill Tag'));
            }
           
            if(valid==0)
                return false;
            else
            return true;
            }
            function errorMessage($msg){
             return '<span class="error author_error">'+$msg+'</span>';
            }
            </script>
            <div class="sidebarMenuHolder mCustomScrollbar _mCS_1" style="height: 268px;"><div class="Jstree_shadow_top"></div><div style="position:relative; height:100%; overflow:hidden; max-width:100%;" id="mCSB_1" class="mCustomScrollBox"><div style="position:relative; top:0;" class="mCSB_container mCS_no_scrollbar"><div class="mCustomScrollBox" id="mCSB_1" style="position:relative; height:100%; overflow:hidden; max-width:100%;"><div class="mCSB_container mCS_no_scrollbar" style="position:relative; top:0;"><div style="position:relative; height:100%; overflow:hidden; max-width:100%;" id="mCSB_1" class="mCustomScrollBox"><div style="position:relative; top:0;" class="mCSB_container mCS_no_scrollbar">
                                        <div class="JStree">

                                            <div id="jstree" class="jstree jstree-0 jstree-focused jstree-default"><ul><li class="jstree-leaf"><ins class="jstree-icon">&nbsp;</ins><a href="#currently-displayed" class=""><ins class="jstree-icon">&nbsp;</ins>Currently Displayed</a></li><li class="jstree-leaf"><ins class="jstree-icon">&nbsp;</ins><a href="#tableSortableResMed" class=""><ins class="jstree-icon">&nbsp;</ins>Logo Management</a></li><li class="jstree-last jstree-leaf"><ins class="jstree-icon">&nbsp;</ins><a href="#new-logo" class=""><ins class="jstree-icon">&nbsp;</ins>New Logo</a></li></ul></div>

                                        </div>
                                    </div><div style="position: absolute; display: none;" class="mCSB_scrollTools"><a style="display:block; position:relative;" class="mCSB_buttonUp"></a><div style="position:relative;" class="mCSB_draggerContainer"><div style="position: absolute; top: 0px;" class="mCSB_dragger"><div style="position:relative;" class="mCSB_dragger_bar"></div></div><div class="mCSB_draggerRail"></div></div><a style="display:block; position:relative;" class="mCSB_buttonDown"></a></div></div></div><div class="mCSB_scrollTools" style="position: absolute; display: none;"><a class="mCSB_buttonUp" style="display:block; position:relative;"></a><div class="mCSB_draggerContainer" style="position:relative;"><div class="mCSB_dragger" style="position: absolute; top: 0px;"><div class="mCSB_dragger_bar" style="position:relative;"></div></div><div class="mCSB_draggerRail"></div></div><a class="mCSB_buttonDown" style="display:block; position:relative;"></a></div></div></div><div style="position: absolute; display: none;" class="mCSB_scrollTools"><a style="display:block; position:relative;" class="mCSB_buttonUp"></a><div style="position:relative;" class="mCSB_draggerContainer"><div style="position: absolute; top: 0px;" class="mCSB_dragger"><div style="position:relative;" class="mCSB_dragger_bar"></div></div><div class="mCSB_draggerRail"></div></div><a style="display:block; position:relative;" class="mCSB_buttonDown"></a></div></div><div class="Jstree_shadow_bottom"></div></div>    </div>
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
                    <a href="javascript:;">Tags</a>
                </li>
            </ul>
        </div>           <header>
            <i class="icon-big-notepad"></i>
            <h2><small>Tip Tags</small></h2>

        </header>
        {!! Form::open(array('url'=>'tip-tags/','class'=> 'form-horizontal','id'=>'validation_form', 'files' => true , 'onsubmit'=>'return addtipstagfunction()')) !!}
        {!! csrf_field() !!}
        <!--<form class="form-horizontal">-->
            <div class="container-fluid">
                <input type="hidden" name="id" value="{{$uid}}">
                <input type="hidden" id="ttagid" name="tid" value="">
                <div id="Notifications" class="form-legend">Notifications</div>

                <!--Notifications begin-->
                <div class="control-group row-fluid" style="display: none">
                    <div class="span12 span-inset">
                        <div class="alert alert-success alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>This is Success Notification</strong>
                            <span>Your data has been successfully modified.</span>
                        </div>
                        <div class="alert alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>This is Alert Notification</strong>
                            <span>No result found.</span>
                        </div>
                        <div class="alert alert-error alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>This is Error Notification</strong>
                            <span>Please select a valid search criteria.</span>
                        </div>
                        <div class="alert alert-error alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>This is Error Notification</strong>
                            <span>Please enter a valid email id.</span>
                        </div>
                    </div>
                </div>
                <!--Notifications end-->
            </div>



            <div class="container-fluid" >
                <div class="form-legend" >Add A New Tip Tag</div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Add a New Tag </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name=ttag id="ttag" >
                        </div>
                        @if (Session::has('message'))
                        <span class="error author_error">{{ Session::get('message') }}</span>
                        @endif
                    </div>
                </div>

                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label"> Sponsored  By </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="sponsoredby" id="sponsoredby">
                        </div>
                    </div>
                </div>

                <div class="control-group row-fluid" >
                    <div class="span3">
                        <label class="control-label">Upload Logo</label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span><input type="file" name="logofile" id="logofile"/></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="url" id="url">
                        </div>
                    </div>
                </div>


                <div class="control-group row-fluid">
                    <div class="span12 span-inset">
                        <button type="submit" name="addttag" class="btn btn-success">Add</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                    </div>
                </div>


            </div>


            <div class="container-fluid" id="tip_list">
                <div class="row-fluid">
                    <div class="span12">
                        <table class="table table-striped" id="tableSortable">
                            <thead>
                            <tr>
                                <th>Tag No.</th>
                                <th>Tag Name</th>
                                <th>No. of Times Used</th>
                                <th>Last Used On</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tiptagArr as $tt)
                            <tr class="gradeX" id="rowCur{{$tt->ttag_id}}">
                                <td>{{$tt->ttag_id}}</td>
                                <td><a href="#" onclick="getEditTag({{$tt->ttag_id}});">{{$tt->tag}}</a></td>
                                <td>{{$tt->used_count}}</td>
                                <td>{{$tt->last_used}}</td>
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
                            "sPaginationType": "bootstrap",
                            "fnInitComplete": function(){
                                $(".dataTables_wrapper select").select2({
                                    dropdownCssClass: 'noSearch'
                                });
                            }
                        });
                        //                            $("#simpleSelectBox").select2({
                        //                                dropdownCssClass: 'noSearch'
                        //                            });
                    });
                </script>
            </div>
        {!! Form::close() !!}
    </div>

    @stop