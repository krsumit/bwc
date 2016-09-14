@extends('layouts/master')

@section('title', 'Edit Newsletter - BWCMS')

@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit Newsletter</small></h1>
        </div>
    
 
        
    
        <div class="panel-header">
    <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
        </div> 
     
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
                <a href="javascript:;">Edit Newsletter</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Edit Newsletter</small></h2>

    </header>
    {!! Form::open(array('url'=>'newsletter/update','class'=> 'form-horizontal','id'=>'validation_form', 'files' => true,'onsubmit'=>'return addmagazineissuefunction()')) !!}
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

            <div class="form-legend">Channel</div>

            <!--Select Box with Filter Search begin-->
            <div id="channel" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Channel</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="channel_sel" id="channel_sel">
                            @foreach($channels as $channel)
                        <option value="{{ $channel->channel_id }}" @if($currentChannelId==$channel->channel_id) selected="selected" @endif>{{ $channel->channel }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <script>
                    $().ready(function () {
                        $("#channel_sel").select2();
                        
                        $("#channel_sel").change(function () {
							if(confirm("Are you sure, you want to change channel ?")){
                            $('#publishSubmit').trigger('click')
						}

                        });
                
                    });
                </script>
                
                
            </div>
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Title </label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input type="hidden" name="newsletterId" value="{{$newsletter->id}}" id="newsletterId"/>
                        <input type="text" name="title" id="title" value="{{$newsletter->title}}">
                    </div>
                </div>
            </div>
            <div class="span12 span-inset">
                <button type="submit" name="status" value="Create" id="publishSubmit" class="btn btn-success">Update</button>
                @if($newsletter->status=='1')
                <button type="submit" name="deactivate" value="deactivate" id="deactivate" class="btn btn-danger">Deactivate</button>
                @else
                <button type="submit" name="activate" value="activate" id="activate" class="btn btn-danger">Activate</button>
                @endif
            </div> 

            <!--Select Box with Filter Search end-->					
        </div>


  
        
        {!! Form::close() !!}
        <div class="form-horizontal">
             {!! Form::open(array('url'=>'newsletter/assign','class'=> 'form-horizontal','id'=>'validation_form', 'files' => true,'onsubmit'=>'return createNewsletter()')) !!}
    {!! csrf_field() !!}
            <input type="hidden" name="newsletterId" value="{{$newsletter->id}}" id="newsletterId1"/>

              <div class="container-fluid" style="margin-bottom:0 !important;">


            <div class="form-legend" id="tags3">Add in Newsletter</div> 

            <div class="row-fluid">
                
                <div class="span12">

                    <div class="controls pull-right">
                        <select name="daysfilter" id="daysfilter" >
                            <option value="1" @if($margin==1) selected="selected" @endif >24 Hours</option>
                            <option value="2" @if($margin==2) selected="selected" @endif >2 Days</option>
                            <option value="3" @if($margin==3) selected="selected" @endif >3 Days</option>
                            <option value="5" @if($margin==5) selected="selected" @endif >5 Days</option>
                            <option value="7" @if($margin==7) selected="selected" @endif >7 Days</option>
                            <option value="15" @if($margin==15) selected="selected" @endif >15 Days</option>
                            <option value="30" @if($margin==30) selected="selected" @endif >1 Month</option>
                        </select>
                    </div>
                    <script>
                        $().ready(function () {
                            $("#daysfilter").select2();
                            $("#daysfilter").change(function(){
                                 window.location = '{{url("/newsletter/manage/".$newsletter->id)}}' + '?margin=' + $(this).attr("value").trim();
                            });
                        });
                    </script>

                    <table class="table table-striped" id="tableSortable2">
                        <thead>
                            <tr>
                                <th>Article ID</th>
                                <th>Title</th>
                                <th>Date of Publish</th>
                                <th>Author</th>
                                <th><input type="checkbox" class="uniformCheckbox" id="selectall" value="checkbox1"></th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($latestArticles as $article)
                            <tr class="gradeX" >
                                <td>{{$article->article_id}}</a></td>
                                <td>{{$article->title}} </td>
                                <td class="center">{{$article->publish_date}}</td>
                                <td> {{$article->name}}</td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" name="checkItem[]" value="{{$article->article_id}}"></td>
                            </tr>
                             @endforeach    
                            
                        </tbody>
                    </table>
                </div>
                
            </div>
            <!--Sortable Non-responsive Table end-->


            <script>
                $(document).ready(function () {
                    $('#tableSortable2, #tableSortableRes, #tableSortableResMed').dataTable({
                        "sPaginationType": "bootstrap",
                        "iDisplayLength": 50,
                         "aaSorting": [],
                        "aoColumnDefs": [{"bSortable": false, "aTargets": [4]}],    
                        "fnInitComplete": function () {
                            $(".dataTables_wrapper select").select2({
                                dropdownCssClass: 'noSearch'
                            });
                        }
                    });
                    //                            $("#simpleSelectBox").select2({
                    //                                dropdownCssClass: 'noSearch'
                    //                            }); 
                    
                     $('#selectall').click(function () {
                        if ($(this).is(':checked')) {
                            $('input[name="checkItem[]"]').each(function () {
                                $(this).attr('checked', 'checked');
                            });
                        } else {
                            $('input[name="checkItem[]"]').each(function () {
                                $(this).removeAttr('checked');
                            });
                        }
                    });
                    
                });
            </script>
        </div><!-- end container -->   

              <div class="container-fluid">

            <!--Sortable Non-responsive Table begin-->
            <div class="row-fluid">
                <div class="control-group row-fluid">
                    <div class="span12 span-inset text-right">
                        <button type="button" class="btn btn-warning">Preview Newsletter</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;">							
                        <button type="submit" class="btn btn-success" >Create Newsletter</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;">
                    </div>
                </div>
            </div>
            </div>    

         {!! Form::close() !!}


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
                            @foreach($assignedArticles as $article)
                            <tr class="gradeX" id="item_<?php echo $article->asigned_id;?>">
                                <td>{{$article->article_id}}</a></td>
                                <td>{{$article->title}} </td>
                                <td class="center">{{$article->publish_date}}</td>
                                <td> {{$article->name}}</td>
                                <td class="center"> <button type="button" class="btn btn-mini btn-danger" onClick="deleteArticle({{$article->asigned_id}})">Dump</button></td>
                            </tr>
                             @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--Sortable Non-responsive Table end-->


            <script>
                $(document).ready(function () {
                    $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable({
                        "sPaginationType": "bootstrap",
                        "iDisplayLength": 50,
                        "aaSorting": [],
                        "aoColumnDefs": [{"bSortable": false, "aTargets": [4]}],
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
                
                function createNewsletter() {
                    var ids = '';
                    var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                        
                        return this.value;
                    }).get();
                    if (checkedVals.length > 0) {
                       return true;
                    } else {
                        alert('Please select at least one record.');
                        return false;
                    }
                }
                
                function deleteArticle(id) {
                        $.get("{{ url('/newsletter/delete/?channel=').$currentChannelId}}",
                                {option: id},
                        function (data) {
                            if (data.trim() == 'success') {
                                 $('#notificationdiv').show();
                                $('#notificationdiv .control-group .span12.span-inset').html('<div class="alert alert-success alert-block">\n\
                            <i class="icon-alert icon-alert-info"></i><button type="button" class="close" data-dismiss="alert">\n\
                            &times;</button><strong>This is Success Notification</strong>\n\
                            <span></span>Selected records un-assigned.</div>');
                               window.location.reload();
                            } else {
                                alert(data);
                            }
                            //alert(1);
                        });
                    
                }
                
            </script>
            
<script>
//alert(1);
  $("#tableSortable tbody").sortable({
      appendTo: "parent",
      helper: "clone",
      update: function (event, ui) {
        //alert($(this).html());
        var data = $(this).sortable('serialize');
        //alert(data);    
        // POST to server using $.post or $.ajax
                $.ajax({
                    data: data,
                    type: 'POST',
                        url: '{{ url("/newsletter/sort/".$newsletter->id)}}'
                });
        
    }
  }).disableSelection();

</script>


        </div>

        </div>
     
</div>

@stop
