@extends('layouts/master')

@section('title', 'Published Sponsored Post - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Published Sponsored Posts</small></h1>
          
        </div>
        <div class="panel-search container-fluid">
            <form class="form-horizontal" action="javascript:;">
                <input id="panelSearch" placeholder="Search" type="text" name="panelSearch">
                <button class="btn btn-search"></button>
                
            </form>
        </div>
		
							<label class="radio">
                                    <input type="radio" name="optionsRadios" class="uniformRadio" value="radio1">
										Search by Article Title
                            </label>
							<label class="radio">
                                    <input type="radio" name="optionsRadios" class="uniformRadio" value="radio1">
                                  	 	Search by Article ID
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
                                "title" : "Published Articles", 
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
        
        function deleteSP(){
                    //alert($('#selectEdit').val());
                    var ids = '';
                    var checkedVals = $('.uniformCheckbox:checkbox:checked').map(function() {
                        var row = 'rowCur' + this.value;
                        $("#"+ row).hide();
                        return this.value;
                    }).get();
                    var ids = checkedVals.join(",");
                    //alert(ids);
                    $.get("{{ url('/sposts/delete/')}}",
                            { option: ids },
                            function(data) {
                                $.each(checkedVals, function (i, e) {
                                    var row = 'rowCur' + e;
                                    $("#" + row).hide();
                                });
                            });
                }
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
            <a href="javascript:;">Published Sponsored Posts</a>
        </li>
    </ul>
</div>           <header>
               <i class="icon-big-notepad"></i>
               <h2><small>Published Sponsored Posts</small></h2>
           </header>
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

                       <!--Sortable Non-responsive Table begin-->
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
                                   <tbody><!--
                                       <tr class="gradeX">
                                           <td><a href="create-new-sponsored-post.html">234567890987654</a> <a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Published by: Sharabani Mukherjee."><i class="icon-photon info-circle"></i></a></td>
                                           <td><a href="create-new-sponsored-post.html">English poetry is receiving lesser attention these days: Aju Mukhopadhyay English poetry is receiving lesser attention these days: Aju Mukhopadhyay</a>
                                           </td>
                                           <td><a href="create-new-sponsored-post.html">2546</a></td>
                                           <td class="center"><a href="create-new-sponsored-post.html">11/03/2013</a>
							  <a href="create-new-sponsored-post.html">12:13 pm</a>
					   </td>
                                           <td class="center"> <input type="checkbox" class="uniformCheckbox" value="checkbox1"></td>
                                       </tr>-->
                                       @foreach($sposts as $s)
                                       <tr class="gradeX" id="rowCur{{$s->id}}">
                                           <td>{{$s->id}} <a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Published by: ."><i class="icon-photon info-circle"></i></a></td>
                                           <td><a href="/sposts/{{$s->id}}">{{$s->title}}</a>
                                           </td>
                                           <td>{{$s->views}}</td>
                                           <td class="center">{{$s->publish_date}}
                                                {{$s->publish_time}}
					   </td>
                                           <td class="center"><input type="checkbox" name="checkItem[]" class="uniformCheckbox" value="{{$s->id}}"></td>
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
                   </script>
                    <div class="dataTables_paginate paging_bootstrap pagination">
                    
                {!! $sposts->appends(Input::get())->render() !!}
                </div>
                   
               </div><!-- end container -->
<div class="control-group row-fluid">
                            <div class="span12 span-inset">
<button type="button" onclick="deleteSP();" class="btn btn-danger">Dump</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
</div></div>
       </form>
   </div>

@stop
