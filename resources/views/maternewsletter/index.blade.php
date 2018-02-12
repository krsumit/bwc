@extends('layouts/master')

@section('title', 'Feature Box Management - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Newsletter Master</small></h1>
        </div>		
        <div class="panel-search container-fluid">
            <form class="form-horizontal" action="javascript:;">
                <input type="text" autocomplete="off" class="ui-autocomplete-input" id="panelSearch" placeholder="Search by Category" name="panelSearch"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><span class="ui-helper-hidden-accessible" aria-live="polite" role="status"></span>
                <button class="btn btn-search"></button>
                <script>
                    $().ready(function () {
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
                        $("#panelSearch").autocomplete({
                            source: searchTags
                        });
                    });
                </script>
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
                                    "title": "Newsletter",
                                    "attr": {"href": "#newsletter"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Existing Newsletter",
                                    "attr": {"href": "#tableSortable_wrapper"}
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
        <div style="height: 268px;" class="sidebarMenuHolder mCustomScrollbar _mCS_1"><div class="Jstree_shadow_top"></div><div style="position:relative; height:100%; overflow:hidden; max-width:100%;" id="mCSB_1" class="mCustomScrollBox"><div style="position:relative; top:0;" class="mCSB_container mCS_no_scrollbar"><div class="mCustomScrollBox" id="mCSB_1" style="position:relative; height:100%; overflow:hidden; max-width:100%;"><div class="mCSB_container mCS_no_scrollbar" style="position:relative; top:0;">
                            <div class="JStree">

                                <div class="jstree jstree-0 jstree-focused jstree-default" id="jstree"><ul><li class="jstree-leaf"><ins class="jstree-icon">&nbsp;</ins><a href="#currently-displayed" class=""><ins class="jstree-icon">&nbsp;</ins>Currently Displayed</a></li><li class="jstree-leaf"><ins class="jstree-icon">&nbsp;</ins><a href="#tableSortableResMed" class=""><ins class="jstree-icon">&nbsp;</ins>Logo Management</a></li><li class="jstree-last jstree-leaf"><ins class="jstree-icon">&nbsp;</ins><a href="#new-logo" class=""><ins class="jstree-icon">&nbsp;</ins>New Logo</a></li></ul></div>

                            </div>
                        </div><div class="mCSB_scrollTools" style="position: absolute; display: none;"><a class="mCSB_buttonUp" style="display:block; position:relative;"></a><div class="mCSB_draggerContainer" style="position:relative;"><div class="mCSB_dragger" style="position: absolute; top: 0px;"><div class="mCSB_dragger_bar" style="position:relative;"></div></div><div class="mCSB_draggerRail"></div></div><a class="mCSB_buttonDown" style="display:block; position:relative;"></a></div></div></div><div style="position: absolute; display: none;" class="mCSB_scrollTools"><a style="display:block; position:relative;" class="mCSB_buttonUp"></a><div style="position:relative;" class="mCSB_draggerContainer"><div style="position: absolute; top: 0px;" class="mCSB_dragger"><div style="position:relative;" class="mCSB_dragger_bar"></div></div><div class="mCSB_draggerRail"></div></div><a style="display:block; position:relative;" class="mCSB_buttonDown"></a></div></div><div class="Jstree_shadow_bottom"></div></div>    </div>
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
                <a href="javascript:;">Newsletter Master</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Newsletter Master</small></h2>

    </header>
    <form class="form-horizontal">
        <div class="container-fluid">

            <div id="Notifications" class="form-legend">Notifications</div>

            <!--Notifications begin-->
            <div class="control-group row-fluid">
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


        <div class="container-fluid" style="margin-bottom:0 !important;">


            <div class="form-legend" id="tags3">Existing Newsletter</div> 

            <div class="row-fluid">
                <div class="span12">

                    <div class="controls pull-right">
                        <select name="selectBoxFilter" id="selectBoxFilter6" >
                            <option selected="" value="All">---Please Select---</option>
                            <option value="Beige">24 Hours</option>
                            <option value="Black">48 Hours</option>
                        </select>
                    </div>
                    <script>
                        $().ready(function () {
                            $("#selectBoxFilter6").select2();
                        });
                    </script>

                    <table class="table table-striped" id="tableSortable2">
                        <thead>
                            <tr>
                                <th>Article ID</th>
                                <th>Title</th>
                                <th>Date of Publish</th>
                                <th>Author</th>
                                <th><input type="checkbox" class="uniformCheckbox" value="checkbox1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="gradeX">
                                <td>234567890987654</a></td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</a>
                                </td>
                                <td class="center">11/03/2013</td>
                                <td> Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                            </tr>
                            <tr class="gradeC">
                                <td>234567890987654</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay
                                </td>
                                <td class="center">11/03/2013</td>
                                <td> Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                            </tr>
                            <tr class="gradeA">
                                <td>234567890987654</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay
                                </td>
                                <td class="center">11/03/2013</td>
                                <td> Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                            </tr>
                            <tr class="gradeA">
                                <td>234567890987654</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay
                                </td>
                                <td class="center">11/03/2013</td>
                                <td> Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                            </tr>
                            <tr class="gradeA">
                                <td>234567890987654</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay
                                </td>
                                <td class="center">11/03/2013</td>
                                <td> Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                            </tr>
                            <tr class="gradeA">
                                <td>234567890987654</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay
                                </td>
                                <td class="center">11/03/2013</td>
                                <td> Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                            </tr>
                            <tr class="gradeA">
                                <td>234567890987654</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay
                                </td>
                                <td class="center">11/03/2013</td>
                                <td> Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                            </tr>
                            <tr class="gradeA">
                                <td>234567890987654</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay
                                </td>
                                <td class="center">11/03/2013</td>
                                <td> Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                            </tr>
                            <tr class="gradeA">
                                <td>234567890987654</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay
                                </td>
                                <td class="center">11/03/2013</td>
                                <td> Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Sortable Non-responsive Table end-->


            <script>
                $(document).ready(function () {
                    $('#tableSortable2, #tableSortableRes, #tableSortableResMed').dataTable({
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
                });
            </script>
        </div><!-- end container -->   

        <div class="container-fluid">

            <!--Sortable Non-responsive Table begin-->
            <div class="row-fluid">
                <div class="control-group row-fluid">
                    <div class="span12 span-inset text-right">
                        <button type="button" class="btn btn-warning">Preview Newsletter</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;">							
                        <button type="button" class="btn btn-success">Create Newsletter</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;">
                    </div>
                </div>
            </div>
        </div>    




        <div class="container-fluid">
            <div class="form-legend" id="tags3">Existing Newsletter</div>
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped" id="tableSortable">
                        <thead>
                            <tr>
                                <th>Article ID</th>
                                <th>Title</th>
                                <th>Date Of Publish</th>
                                <th>Author</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>123456789</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</td>
                                <td>11/04/2016</td>
                                <td>Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td>
                                    <button type="button" class="btn btn-mini btn-danger">Dump</button>
                                </td>
                            </tr>
                            <tr>
                                <td>123456789</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</td>
                                <td>11/04/2016</td>
                                <td>Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td>
                                    <button type="button" class="btn btn-mini btn-danger">Dump</button>
                                </td>
                            </tr>
                            <tr>
                                <td>123456789</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</td>
                                <td>11/04/2016</td>
                                <td>Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td><button type="button" class="btn btn-mini btn-danger">Dump</button>
                                </td>
                            </tr>
                            <tr>
                                <td>123456789</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</td>
                                <td>11/04/2016</td>
                                <td>Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td><button type="button" class="btn btn-mini btn-danger">Dump</button>
                                </td>
                            </tr>
                            <tr>
                                <td>123456789</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</td>
                                <td>11/04/2016</td>
                                <td>Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td><button type="button" class="btn btn-mini btn-danger">Dump</button>
                                </td>
                            </tr>
                            <tr>
                                <td>123456789</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</td>
                                <td>11/04/2016</td>
                                <td>Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td><button type="button" class="btn btn-mini btn-danger">Dump</button>
                                </td>
                            </tr>
                            <tr>
                                <td>123456789</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</td>
                                <td>11/04/2016</td>
                                <td>Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td><button type="button" class="btn btn-mini btn-danger">Dump</button>
                                </td>
                            </tr>
                            <tr>
                                <td>123456789</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</td>
                                <td>11/04/2016</td>
                                <td>Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td><button type="button" class="btn btn-mini btn-danger">Dump</button>
                                </td>
                            </tr>
                            <tr>
                                <td>123456789</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</td>
                                <td>11/04/2016</td>
                                <td>Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td><button type="button" class="btn btn-mini btn-danger">Dump</button>
                                </td>
                            </tr>
                            <tr>
                                <td>123456789</td>
                                <td>English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</td>
                                <td>11/04/2016</td>
                                <td>Brigadier CHITRANJAN SAWANT,VSM</td>
                                <td><button type="button" class="btn btn-mini btn-danger">Dump</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Sortable Non-responsive Table end-->


            <script>
                $(document).ready(function () {
                    $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable({
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
                });
            </script>
        </div>

    </form>
</div>  

@stop
