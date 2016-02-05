@extends('layouts/master')

@section('title', 'Add-edit-Master category - BWCMS')
@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Category Master</small></h1>
        </div>		
        <div class="panel-search container-fluid">
             <form class="form-horizontal" method="get" action="">
                 <input type="hidden" name="channel" value="{{$currentChannelId}}"/>
                    <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                    <button class="btn btn-search" type="submit"></button>
                     @if(isset($_GET['keyword'])) 
                    <a href="{{url("category/add-master-category?channel=").$currentChannelId}}"><button class="btn btn-default" type="button">Reset</button></a>
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
                    "json_data": {
                        "data": [
                            {
                                "data": {
                                    "title": "Channel",
                                    "attr": {"href": "#Channel"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Add A New Category",
                                    "attr": {"href": "#tags"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Existing Category",
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
                <a href="javascript:;">Category Master</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Category Master</small></h2>

    </header>
    <form class="form-horizontal"method="POST" action="/category/add" onsubmit="return validateCategoryData();">
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
        <input type="hidden" value="maincategory" id="maincategory" name="maincategory">
        <div class="container-fluid">

            <div class="form-legend" id="Channel">Channel</div>

            <!--Select Box with Filter Search begin-->
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Channel</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="channel" id="selectBoxFilter20">
                            @foreach($channels as $channel)
                       <option @if($channel->channel_id==$currentChannelId) selected="selected" @endif value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
                        @endforeach
                           
                        </select>
                    </div>
                </div>
                <script>
                    $().ready(function () {
                        $("#selectBoxFilter20").select2();
                           $("#selectBoxFilter20").change(function () {
                            $(this).find("option:selected").each(function () {

                                if ($(this).attr("value").trim().length != 0) {

                                window.location = '{{url("category/add-master-category")}}' + '?channel=' + $(this).attr("value").trim();
                            }

                            else if ($(this).attr("value") == "none") {

                            $("#quote_list").hide();
                        }

                        });
                    });
                    });
                </script>
            </div>

            <!--Select Box with Filter Search end-->					
        </div>

        <div class="container-fluid">

            <div class="form-legend" id="tags">Add A New Category</div>
            <!--Topics begin-->

            <!--Topics end-->

            <!--Select Box with Filter Search begin-->

            <div class="control-group row-fluid" id="Multiple_Select_Box_with_Filter_Search">


                <script>
                    $().ready(function () {
                        $("#selectBoxFilter").select2();
                    });
                </script>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label for="add tags" class="control-label">Add New Categtary Name</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="add_mastercategory"id="add_mastercategory" class="required number valid"><span for="add tags" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                        </div>
                    </div>
                    <div class="span12 span-inset">

                        <button style="display:block;" class="btn btn-info" type="submit">Save</button>
                        <img style="width:5%; display:none; " alt="loader" src="images/photon/preloader/76.gif"></div>

                </div>
            </div>                       
        </div>

        <div class="container-fluid">
            <div class="form-legend" id="tags3">Existing Category</div>
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped" id="tableSortable">
                        <thead>
                            <tr>
                                <th>Category ID</th>
                                <th>Category</th>
                                <th>Added By</th>
                                <th>Added On</th>
                                <th><input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $a)
                            <tr id="rowCur{{$a->category_id}}">
                                
                                <td>{{$a->category_id}}</td>
                                <td><a href="/sub-category-master/add/?name={{$a->name}}&id={{$a->category_id}}">{{$a->name}}</a{></td>
                                <td>{{$a->userssname}}</td>
                                <td>{{$a->created_at}}</td>
                                <td>
                                    <input type="checkbox" class="uniformCheckbox" value="{{$a->category_id}}" name="checkItem[]">
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
                        $.get("{{ url('/mastercategory/delete/?channel=').$currentChannelId}}",
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

        </div>
      <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button type="button" onclick="deleteAuthor()" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>							

            </div></div>
    </form>
</div> 
<script>
    function validateCategoryData(){
           var valid = 1;
                $('.author_error').remove();
                $('#new input').removeClass('error');
                $('#new textarea').removeClass('error');
            if ($('select[name=channel]').val().trim() == 0){
                valid = 0;
                $('select[name=channel]').addClass('error');
                $('select[name=channel]').after(errorMessage('Please enter channel'));
                }
            if ($('input[name=add_mastercategory]').val().trim() == 0){
                valid = 0;
                $('input[name=add_mastercategory]').addClass('error');
                $('input[name=add_mastercategory]').after(errorMessage('Please enter name'));
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
@stop