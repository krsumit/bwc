@extends('layouts/master')

@section('title', 'Add-edit-events - BWCMS')


@section('content') 
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Published Events</small></h1>

        </div>
        <div class="panel-search container-fluid">
            <form class="form-horizontal" method="get" action="">
                    <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                    
                    <button class="btn btn-search" type="submit"></button>
                     @if(isset($_GET['keyword'])) 
                     <a href="/event/published"><button class="btn btn-default" type="button">Reset</button></a>
                   @endif

             </form>
        </div>

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
                                    "title": "Sort By",
                                    "attr": {"href": "#Simple_Select_Box_with_Filter_Search"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Published Events",
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
                <a href="javascript:;">Published Events</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Published Events</small></h2>
    </header>
    <form class="form-horizontal" action="" method="get">

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

            <div class="form-legend">Sort By</div>

            <!--Select Box with Filter Search begin-->
            <div id="Simple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Sort By Region</label>
                </div>
                <div class="span9">
                    <div class="controls">
                       <select name="country" id="selectBoxFilter6">
                        <option  value="">Please Select</option>
                        @foreach($country as $countrye)
                        <option value="{{ $countrye->country_id }}">{{ $countrye->name }}</option>
                        @endforeach	                                        
                    </select>
                    </div>
                </div>
                <script>
                    $().ready(function () {
                        $("#selectBoxFilter").select2();
                    });
                </script>
            </div>
            <!--Select Box with Filter Search end-->
            <!--Select Box with Filter Search begin-->
            <div id="tabState">
            <div id="Simple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Sort By Type</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="state" id="selectBoxFilter7">
                            <option value="">Please Select</option>
                            @foreach($states as $state)
                            <option value="{{ $state->state_id}}">{{ $state->name }}</option>		
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>  
            </div>
            <!--Select Box with Filter Search end-->

                    <script>
                        $(document).ready(function(){
                        $("#selectBoxFilter6").select2();
                        $("#selectBoxFilter6").change(function(){
                        $(this).find("option:selected").each(function(){
                        if ($(this).attr("value") == "1"){
                        $("#tabState").show();
                       
                        } else{
                        $("#selectBoxFilter7").select2();
                        $("#tabState").hide();
                        
                        }

                        });
                        }).change();
                        });
                                           
                </script>

            <!--Select Box with Filter Search begin-->
            <div id="Simple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="datepicker">
                        Start Date
                    </label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input type="text" id="datepicker" class="span3" name="startdate"/>
                    </div>
                </div>

            </div>
            <script>
                $(function () {
                    $("#datepicker").datepicker();
                    $("#datepickerInline").datepicker();
                    $("#datepickerMulti").datepicker({
                        numberOfMonths: 3,
                        showButtonPanel: true
                    });
                    $('#timeEntry').timeEntry().change();
                });
            </script> 
            <div id="Simple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="datepicker">
                        End Date
                    </label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input type="text" id="datepicker2" class="span3" name="enddate"/>
                    </div>
                </div>
            </div>
            <script>
                $(function () {
                    $("#datepicker2").datepicker();
                    $("#datepickerInline").datepicker();
                    $("#datepickerMulti").datepicker({
                        numberOfMonths: 3,
                        showButtonPanel: true
                    });
                    $('#timeEntry').timeEntry().change();
                });
            </script> 

            <div class="span12 span-inset">
                <button type="submit" class="btn btn-info">Search</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
            </div>
        </div>

        <div class="container-fluid">

            <!--Sortable Non-responsive Table begin-->
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped" id="tableSortable">
                        <thead>
                            <tr>
                                <th>Events ID</th>
                                <th>Title</th>
                                <th>Event Type</th>
                                <th>Location</th>
                                <th><input type="checkbox" class="uniformCheckbox" value="checkbox1"  id="selectall"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $a)
                            <tr class="gradeX" id="rowCur{{$a->event_id}}">
                                <td><a href="add-new-events.html">{{$a->event_id}}</a> <a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Submitted by: Harish Dido Harish DidoDido. Submitted on: 11-03-2013."><i class="icon-photon info-circle"></i></a></td>
                                <td><a href="/event/edit/?id={{$a->event_id}}">{{$a->title}}</a>
                                </td>
                                <td><a href="add-new-events.html"><input type="checkbox" class="uniformCheckbox" value="1" name="sponsored"></a></td>
                                <td class="center"><a href="add-new-events.html">{{$a->name}}</a></td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" value="{{$a->event_id}}" name="checkItem[]"></td>
                            </tr>
                            @endforeach
        
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Sortable Non-responsive Table end-->

<div class="dataTables_paginate paging_bootstrap pagination">
                    
                
                </div>
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
            
             function deleteAuthor() {
                        var ids = '';
                        var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                            var row = 'rowCur' + this.value;
                           
                            return this.value;
                        }).get();
                        
                       // alert(2);
                        var ids = checkedVals.join(",");
                        //alert(ids);return false;
                        $.get("{{ url('/event/delete/')}}",
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
        </div><!-- end container -->
         <div class="control-group row-fluid">
                <div class="span12 span-inset">
                 <button type="button" onclick="deleteAuthor()" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>							
            </div>
          </div>
    </form>
</div>
@stop
