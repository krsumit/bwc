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
           <form class="form-horizontal">
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
				<div class="alert alert-error alert-block" style="display:none">
                                    <i class="icon-alert icon-alert-info"></i>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>This is Error Notification</strong>
                                    <span>Please enter a valid email id.</span>
                                </div>
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
                                           <th><input type="checkbox" class="uniformCheckbox" value="checkbox1"></th>
									   </tr>
                                   </thead>
                                   <tbody>
                                       <tr class="gradeX">
                                           <td><a href="create-new-sponsored-post.html">234567890987654</a> <a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Published by: Sharabani Mukherjee."><i class="icon-photon info-circle"></i></a></td>
                                           <td><a href="create-new-sponsored-post.html">English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</a>
                                           </td>
                                           <td><a href="create-new-sponsored-post.html">2546</a></td>
                                           <td class="center"><a href="create-new-sponsored-post.html">11/03/2013</a>
										   					  <a href="create-new-sponsored-post.html">12:13 pm</a>
										   </td>
                                           <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                                       </tr>
                                       @foreach($sposts as $s)
                                       <tr class="gradeX">
                                           <td>{{$s->id}} <a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Published by: Sharabani Mukherjee."><i class="icon-photon info-circle"></i></a></td>
                                           <td><a href="/sposts/{{$s->id}}">{{$s->title}}</a>
                                           </td>
                                           <td>{{$s->views}}</td>
                                           <td class="center">{{$s->publish_date}}
                                               {{$s->publish_time}}
                                           </td>
                                           <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                                       </tr>
                                       @endforeach                                       
                                   </tbody>
                               </table>

                           </div>
                       </div>
                       <!--Sortable Responsive Media Table end-->

           </div><!-- end container -->
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
				   
				   <div class="control-group row-fluid">
                            <div class="span12 span-inset">
<button type="button" class="btn btn-success">Publish</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
</div></div>
       </form>
   </div>


@stop
