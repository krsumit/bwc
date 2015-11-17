@extends('layouts/master')

@section('title', 'Rights Management - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>CMS Rights</small></h1>
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
                    
                    function deleteUser(){                    
                    var id = $('input[name=delUser]:radio:checked').val();
                    //alert(id);
                    
                    $.get("{{ url('/rights/delete/')}}",
                            { option: id },
                            function(data) {
                                var row = 'rowCur' + id;
                                $("#" + row).hide();                               
                            });
                            
                }
                </script>
            </form>
        </div>
		
							<label class="radio">
                                    <input type="radio" name="optionsRadios" class="uniformRadio" value="radio1">
                                  	 	Search by Name
                            </label>
							<label class="radio">
                                    <input type="radio" name="optionsRadios" class="uniformRadio" value="radio1">
                                  	 	Search by Email ID
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
            <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
        </div> 
                    <script type="text/javascript">
        $(function () {
            $("#jstree").jstree({ 
                "json_data" : {
                    "data" : [
                                                {
                            "data" : { 
                                "title" : "Create A New Admin Profile", 
                                "attr" : { "href" : "#new" } 
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
            <a href="javascript:;">CMS Rights</a>
        </li>
    </ul>
</div>           <header>
               <i class="icon-big-notepad"></i>
               <h2><small>CMS Rights</small></h2>
              
           </header>
            {!! Form::open(array('url'=>'rights/','class'=> 'form-horizontal','id'=>'form1')) !!}
            {!! csrf_field() !!}
                
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
						<div class="form-legend" id="new">Create A New Admin Profile</div>
                        
                                        	<div class="control-group row-fluid">
                                                <div class="span3">
                                                    <label class="control-label" for="inputField">Name</label>
                                                </div>
                                                <div class="span9">
                                                    <div class="controls">
                                                        <input id="inputField" name="name" type="text" value="{{old('name')}}">
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            <div class="control-group row-fluid">
                                                <div class="span3">
                                                    <label class="control-label" for="inputField">Email </label>
                                                </div>
                                                <div class="span9">
                                                    <div class="controls">
                                                        <input  name="email" type="email" value="{{old('email')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group row-fluid">
                                                <div class="span3">
                                                    <label class="control-label" for="inputField">Password </label>
                                                </div>
                                                <div class="span9">
                                                    <div class="controls">
                                                        <input  name="password" type="password">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group row-fluid">
                                                <div class="span3">
                                                    <label class="control-label" for="inputField">Confirm Password</label>
                                                </div>
                                                <div class="span9">
                                                    <div class="controls">
                                                        <input  name="password_confirmation" type="password">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group row-fluid">
                                                <div class="span3">
                                                    <label class="control-label" for="inputField">Mobile</label>
                                                </div>
                                                <div class="span9">
                                                    <div class="controls">
                                                        <input  name="mobile" type="tel" maxlength="10" value="{{old('mobile')}}">
                                                    </div>
                                                </div>
                                            </div>
											<!--Simple Select Box begin-->
                                           <div id="Simple_Select_Box" class="control-group row-fluid">
                                                <div class="span3">
                                                    <label class="control-label" for="simpleSelectBox">Role</label>
                                                </div>
                                                <div class="span9">
                                                    <div class="controls">
                                                        <select name="role" id="simpleSelectBox" value="{{old('role')}}">
                                                            <option selected="" value="">---Please Select---</option>                                                            
                                                            @foreach($roles as $role)
                                                            <option value="{{$role->user_types_id}}">{{$role->label}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <script>
                                                    $().ready(function(){
                                                        $("#simpleSelectBox").select2({
                                                            dropdownCssClass: 'noSearch'
                                                        });
                                                    });
                                                </script>
                                            </div>
                                            <!--Simple Select Box end-->
                                             
<!--                                               <div class="control-group row-fluid">
                                                    <div class="span3">
                                                        <label class="control-label">Channel</label>
                                                    </div>
                                                    <div class="span3">
                                                        <label class="radio">
                                                            <input type="checkbox" checked name="BW" class="uniformRadio" value="1">
                                                            BW Businessworld
                                                        </label>
                                                    </div>
                                                    <div class="span3">
                                                        <label class="radio">
                                                            <input type="checkbox" name="BWH" class="uniformRadio" value="2">
                                                            BW Hotelier
                                                        </label>
                                                    </div>
                                                </div>-->
                                            
                                                 <div class="control-group row-fluid">
                                                    <div class="span3">
                                                        <label class="control-label">Channel</label>
                                                    </div>
                                                   @foreach($allchannel as $channel)
                                                    <div class="span3">
                                                        <label class="radio">
                                                            <input type="checkbox"    name="rightArr[]" class="uniformRadio" value="{{$rightChannels[$channel->channel_id]}}">
                                                           {{$channel->channel}}
                                                        </label>
                                                    </div>
                                                 @endforeach
                                                 
                                                </div>

                                            <div class="control-group row-fluid">
                                            <div class="span12 span-inset">
                                                <button class="btn btn-warning pull-right" type="submit" name="add" style="display:block;">Add</button>
                                            </div>
                                        </div>
                                  

                </div>
                
                
              <div class="container-fluid">


                       <!--Sortable Responsive Media Table begin-->
                       <div class="row-fluid">
                           <div class="span12">
                               <table class="table table-striped table-responsive" id="tableSortableResMed">
                                   <thead class="cf sorthead">
                                       <tr>
                                           <th>S.No.</th>
                                           <th>Name</th>
                                           <th>Email-ID</th>
                                           <th>Channel Name</th>
                                           <th><!--<input type="checkbox" class="uniformCheckbox" value="checkbox1">-->
                                           		<label class="radio">
                                                            Select
                                                        </label>
                                           </th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       @foreach($users as $user)
                                       <tr class="gradeX" id="rowCur{{$user->id}}">
                                           <td style="width:160px;">{{$user->id}}</td>
                                           <td ><a href="/rights/{{$user->id}}">{{$user->name}}</a></td>
                                           <td >{{$user->email}}</td>
                                           <td  class="center">{{$ucArr[$user->id]}}</td>
                                           <td  class="center"><label class="radio">
                                                <input type="radio" name="delUser" class="uniformRadio" value="{{$user->id}}">
                                                </label></td>
                                       </tr>
                                       @endforeach                                       
                                   </tbody>
                               </table>

                           </div>
                           
                       </div>
                       <!--Sortable Responsive Media Table end-->
                       
                        <div class="control-group row-fluid">
                                            <div class="span12 span-inset">
                                                <button class="btn btn-danger pull-right" onclick="deleteUser();" type="button" name="delete" style="display:block;">Delete</button> 
                                                <!--<a href="cms-right-management.html">
                                                	<button class="btn btn-warning pull-right" type="submit" name="edit" style="display:block; margin-right:10px">Modify</button>
                                                </a>-->
                                            </div>
                                        </div>
                           

           </div><!-- end container -->
		   <script>
                       $(document).ready(function() {
                           $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable( {
                               "sPaginationType": "bootstrap",
                               "aaSorting": [] ,
                              "aoColumnDefs": [ { "bSortable": false, "aTargets": [4] } ],
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
				   
				
       {!! Form::close() !!}
   </div>

@stop