@extends('layouts/master')

@section('title', 'Deleted Sponsored Post - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Deleted Sponsored Post</small></h1>
        </div>
        <div class="panel-search container-fluid">
            <form class="form-horizontal" action="javascript:;">
                 <input type="hidden" value="{{$currentChannelId}}" name="channel" />
                <input id="panelSearch" placeholder="Search" type="text" name="panelSearch">
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
		<label class="radio">
                                    <input type="radio" name="optionsRadios" class="uniformRadio" value="radio1">
										Search by Title
                            </label>
							<label class="radio">
                                    <input type="radio" name="optionsRadios" class="uniformRadio" value="radio1">
                                  	 	Search by Photo ID
                            </label>
						
							
								
								 <script>
                        $().ready(function(){
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
                "json_data" : {
                    "data" : [
                                                {
                            "data" : { 
                                "title" : "Deleted Sponsored Post", 
                                "attr" : { "href" : "#tableSortableResMed_wrapper" } 
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
            <a href="javascript:;">Deleted Sponsored Post</a>
        </li>
    </ul>
</div>           <header>
               <i class="icon-big-notepad"></i>
               <h2><small>Deleted Sponsored Post</small></h2>
              
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

                                window.location = '{{url("sposts/list/deleted")}}' + '?channel=' + $(this).attr("value").trim();
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
		<div class="container-fluid" id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')))style="display: none" @endif >

                <div class="form-legend" id="Notifications">Notifications</div>

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
              <div class="container-fluid">


                       <!--Sortable Responsive Media Table begin-->
                       <div class="row-fluid">
                           <div class="span12">
                               <table class="table table-striped" id="tableSortable">
                                   <thead>
                                       <tr>
                                           <th>Article ID</th>
                                           <th>Title</th>
                                           <th>Views</th>
                                           <th>Date,Time</th>
                                           <th><input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall" ></th>
									   </tr>
                                   </thead>
                                   <tbody>
                                       @foreach($sposts as $s)
                                       <tr class="gradeX" id="rowCur{{$s->id}}">
                                           <td>{{$s->id}} <a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Published by: Sharabani Mukherjee."><i class="icon-photon info-circle"></i></a></td>
                                           <td><a href="/sposts/{{$s->id}}">{{$s->title}}</a>
                                           </td>
                                           <td>{{$s->views}}</td>
                                           <td class="center">{{$s->publish_date}}
                                               {{$s->publish_time}}
                                           </td>
                                           <td class="center"> <input type="checkbox" class="uniformCheckbox" value="{{$s->id}}" name="checkItem[]"></td>
                                       </tr>
                                       @endforeach                                       
                                   </tbody>
                               </table>

                           </div>
                       </div>
                       <!--Sortable Responsive Media Table end-->
                       <div class="dataTables_paginate paging_bootstrap pagination">
                    
                {!! $sposts->appends(Input::get())->render() !!}
                </div>
           </div><!-- end container -->
		   <script>
                       $(document).ready(function() {
                           $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable( {
                                bInfo: false,
                              bPaginate:false,
                              "aaSorting": [] ,
                              "aoColumnDefs": [ { "bSortable": false, "aTargets": [4] } ],
                              "fnInitComplete": function(){
                                   $(".dataTables_wrapper select").select2({
                                       dropdownCssClass: 'noSearch'
                                   });
                               }
                           });
                           
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
                         
                         
                         
                           //                            $("#simpleSelectBox").select2({
                           //                                dropdownCssClass: 'noSearch'
                           //                            }); 
                       });
                        function publishSP() {
                    var ids = '';
                    var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                       // var row = 'rowCur' + this.value;
                       // $("#" + row).hide();
                        return this.value;
                    }).get();
                    if (checkedVals.length > 0) {
                        var ids = checkedVals.join(",");
                        //alert(ids);return false;
                        $.get("{{ url('/sposts/publish/?channel=').$currentChannelId}}",
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
				   
				   <div class="control-group row-fluid">
                            <div class="span12 span-inset">
<button type="button" onClick="publishSP()" class="btn btn-success">Publish</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
</div></div>
       </form>
   </div>


@stop
