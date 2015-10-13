@extends('layouts/master')

@section('title', 'Add-edit-sub Master category - BWCMS')


@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Sub Category Master</small></h1>
        </div>		
		<div class="panel-search container-fluid">
            <form class="form-horizontal" method="get" action="">
                    <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                    <input type="hidden" value="{{$_GET['name'] or ''}}"  name="name">
                    
                     <input type="hidden" value="{{$_GET['id'] or ''}}" id="p_id" name="id">
                    <button class="btn btn-search" type="submit"></button>
                     @if(isset($_GET['keyword'])) 
                     <a href="/sub-category_third_master/add/?name={{$_GET['name'] or ''}}&id={{$_GET['id'] or ''}}"><button class="btn btn-default" type="button">Reset</button></a>
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
                "json_data" : {
                    "data" : [
					
					 {
                            "data" : { 
                                "title" : "Add A New Sub Category", 
                                "attr" : { "href" : "#tags" } 
                            }
                        },
                                                {
                            "data" : { 
                                "title" : "Existing Sub Category", 
                                "attr" : { "href" : "#tableSortable_wrapper" } 
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
            <a href="javascript:;"> Sub Category Master</a>
        </li>
    </ul>
</div>           <header>
               <i class="icon-big-notepad"></i>
               <h2><small>Sub Category Master</small></h2>
           
           </header>
<form class="form-horizontal" action="/category/add" method="POST" onsubmit="return validateCategoryData()">
    {!! csrf_field() !!}
		   <div class="container-fluid">

                        <div id="Notifications" class="form-legend">Notifications</div>

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

                        <div class="form-legend" id="tags">Add A New Sub Category</div>
  <!--Topics begin-->
                       
                        <!--Topics end-->
				<div  class="control-group row-fluid">
                         <!--Select Box with Filter Search begin-->
                      
                       <div class="control-group row-fluid">
                            <div class="span3">
                                <label for="add tags" class="control-label">Categtary Name</label>
                            </div>
                           <input type="hidden" value="{{$_GET['name'] or ''}}"  name="pt_name">
                           <input type="hidden" value="foursubcategory" id="foursubcategory" name="foursubcategory">
                            <input type="hidden" value="{{$_GET['id'] or ''}}" id="ps_id" name="pt_id">
                            <div class="span9">
                                <div class="controls">
                                    <input type="text"  value="{{$_GET['name'] or ''}}" disabled class="required number valid"/>
                                </div>
                            </div>
                          </div>
                           
			<div class="control-group row-fluid">
                            <div class="span3">
                                <label for="add tags" class="control-label">Add New Sub Categtary Name</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <input type="text" name="addsubcategory"  id="addsubcategory" class="required number valid"><span for="add tags" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                                </div>
                            </div>
                            
                            <div class="control-group row-fluid">
                                <div class="span12 span-inset">
    
                                    <button style="display:block;" class="btn btn-info" type="submit">Save</button>
                                  <img style="width:5%; display:none; " alt="loader" src="images/photon/preloader/76.gif"></div>
							</div>
                            
                       </div>
					 </div>                       
                </div>
				
        <div class="container-fluid">
		  <div class="form-legend" id="tags3">Existing Sub Category</div>
<div class="row-fluid">
                           <div class="span12">
                               <table class="table table-striped" id="tableSortable">
                                   <thead>
                                      
                                       <tr>
                                           <th>Sub Category ID</th>
                                           <th>Sub Category</th>
                                           <th>Added By</th>
                                           <th>Added On</th>
                                           <th><input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall"></th>
									   </tr>
                                   </thead>
                                   <tbody>
                                       @foreach($posts as $a)
                                        <tr id="rowCur{{$a->category_four_id}}">
                                           <td>{{$a->category_four_id}}</td>
                                           <td><!--<a href="/sub-category_third_master/add/?name={{$a->name}}&id={{$a->category_four_id}}"></a>-->{{$a->name}}</td>
                                           <td>{{$a->userssname}}</td>
                                           <td>{{$a->created_at}}</td>
                                          <td><input type="checkbox" class="uniformCheckbox" value="{{$a->category_four_id}}" name="checkItem[]"></td>
                                       </tr>
                                       @endforeach
                                       
                                   </tbody>
                               </table>
                           </div>
                       </div>
                       <!--Sortable Non-responsive Table end-->


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
                        $.get("{{ url('/fourcategory/delete/')}}",
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
            function validateCategoryData(){
           var valid = 1;
                $('.author_error').remove();
                $('#new input').removeClass('error');
                $('#new textarea').removeClass('error');
            
            if ($('input[name=addsubcategory]').val().trim() == 0){
                valid = 0;
                $('input[name=addsubcategory]').addClass('error');
                $('input[name=addsubcategory]').after(errorMessage('Please enter name'));
                }
            
            
                                    //alert(valid);
            if (valid == 0)
                return false;
                else
                return true;
        }
    function errorMessage($msg){
        return '<span class="error author_error">' + $msg + '</span>';
        }        
                    
        </script>

               </div>
                <div class="control-group row-fluid">
                <div class="span12 span-inset">
                 <button type="button" onclick="deleteAuthor()" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>							
            </div>
          </div>
       </form>
   </div> 
@stop