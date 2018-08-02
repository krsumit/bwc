@extends('layouts/master')

@section('title', 'Magazine issued Management - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Add Magazine Issue</small></h1>
        </div>
        <script>
          
            function addmagazineissuefunction(){
            var valid=1
            $('#new input').removeClass('error');
            if ($('select[name=channel]').val().trim() == 0){
                valid = 0;
                $('select[name=channel]').addClass('error');
                $('select[name=channel]').after(errorMessage('Please enter channel'));
                }
            if($('input[name=publish_date_m]').val().trim()==0){
            valid=0;
            $('input[name=publish_date_m]').addClass('error');
            $('input[name=publish_date_m]').after(errorMessage('Please select date'));
            }
            if ($('input[name=title]').val().trim() == 0){
                valid = 0;
                $('input[name=title]').addClass('error');
                $('input[name=title]').after(errorMessage('Please enter name'));
                }
             if ($('input[name=photo]').val().trim() == 0){
                valid = 0;
                $('input[name=photo]').addClass('error');
                $('input[name=photo]').after(errorMessage('Please enter photo'));
                }
            if(valid==0)
                return false;
            else
            return true;
            }
            function errorMessage($msg){
             return '<span class="error">'+$msg+'</span>';
            }
                
                </script>
        <div class="panel-search container-fluid" style="height: 100px">
            <form class="form-horizontal" action="">
                <input type="hidden" value="{{$currentChannelId}}" name="channel"/>
                <input id="panelSearch" required placeholder="Search" type="text" value="{{$_GET['keyword'] or ''}}" name="keyword">
                <button class="btn btn-search" type="submit"></button>
                @if(isset($_GET['keyword'])) 
                <a href="{{url("magazineissue")}}?channel={{$currentChannelId}}"><button class="btn btn-default" type="button">Reset</button></a>
                @endif

                <label class="radio">
                    <input type="radio"  @if(isset($_GET['searchin'])) @if($_GET['searchin']=='title') checked @endif @endif required name="searchin" class="uniformRadio" value="title">
                           Search by ssue Name 
                </label>
                

            </form>
        </div>

        
        <script>
            $().ready(function () {
                $(".uniformRadio").uniform({
                    radioClass: 'uniformRadio'
                });
                
                $("#selectBoxFilter20").change(function () {
                    $(this).find("option:selected").each(function () {

                        if ($(this).attr("value").trim().length != 0) {

                            window.location = '{{url("magazineissue")}}' + '?channel=' + $(this).attr("value").trim();
                        }

                        else if ($(this).attr("value") == "none") {

                            $("#quote_list").hide();

                        }

                    });

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
                    "json_data": {
                        "data": [
                            {
                                "data": {
                                    "title": "Add Magazine Issue",
                                    "attr": {"href": "#new"}
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
                <a href="javascript:;">Add Magazine Issue</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Add Magazine Issue</small></h2>

    </header>
    {!! Form::open(array('url'=>'magazineissue/add','class'=> 'form-horizontal','id'=>'validation_form', 'files' => true,'onsubmit'=>'return addmagazineissuefunction()')) !!}
    {!! csrf_field() !!}
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

            <div class="form-legend">Channel</div>

            <!--Select Box with Filter Search begin-->
            <div id="channel" class="control-group row-fluid">
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
                    });
                </script>
            </div>

            <!--Select Box with Filter Search end-->					
        </div>


        <div class="container-fluid">
            <div class="form-legend" id="new">Add Magazine Issue

            </div>


            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="datepicker">
                        Publish Date<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Click to choose date."><i class="icon-photon info-circle"></i></a>
                    </label>
                </div>
                <div class="span9">
                <div class="controls">
                    <input type="text" name="publish_date_m" id="datepicker" class="span3" />
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

            <div id="File_Upload" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Cover Photo(Size:{{config('constants.dimension_magz')}}, File Size<={{config('constants.maxfilesize').' '.config('constants.filesizein')}})</label>
                </div>
                <div class="span9">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="input-append">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input name="photo" type="file"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_magz')}}')">&nbsp;Need to crop images? Click here</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="inputField">Name</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="title" name="title" type="text">
                    </div>
                </div>
            </div>

        </div>


        <!--<div class="container-fluid">

            <div class="form-legend">Story 1</div>

            
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Title</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="story1_title" name="story1_title" type="text"/>
                    </div>   
                </div>
            </div>

            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">URL</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="story1_url" name="story1_url" type="url"/>
                    </div>   
                </div>
            </div>

            					
        </div>

        <div class="container-fluid">

            <div class="form-legend">Story 2</div>

           
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Title</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="story2_title" name="story2_title" type="text"/>
                    </div>   
                </div>
            </div>

            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">URL</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="story2_url" name="story2_url" type="url"/>
                    </div>   
                </div>
            </div>

            				
        </div>

        <div class="container-fluid">

            <div class="form-legend">Story 3</div>

            
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Title</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="story3_title" name="story3_title" type="text"/>
                    </div>   
                </div>
            </div>

            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">URL</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="story3_url" name="story3_url" type="url"/>
                    </div>   
                </div>
            </div>

            				
        </div>

        <div class="container-fluid">

            <div class="form-legend">Story 4</div>

            
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Title</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="story4_title" name="story4_title" type="text"/>
                    </div>   
                </div>
            </div>

            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">URL</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="story4_url" name="story4_url" type="url"/>
                    </div>   
                </div>
            </div>

           					
        </div>

        <div class="container-fluid">

            <div class="form-legend">Story 5</div>

            
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Title</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="story5_title" name="story5_title" type="text"/>
                    </div>   
                </div>
            </div>

            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">URL</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="story5_url" name="story5_url" type="url"/>
                    </div>   
                </div>
            </div>

            					
        </div>--->

        <div class="container-fluid">

            <div class="form-legend">FlIP URL</div>

            <!--Select Box with Filter Search begin-->
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Url</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="flip_url" name="flipbook_url" type="text"/>
                    </div>   
                </div>
            </div>

           

            <!--Select Box with Filter Search end-->					
        </div>
                <div class="container-fluid">

            <div class="form-legend">BUY DIGITAL</div>

            <!--Select Box with Filter Search begin-->
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Url</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="buy_digital" name="buy_digital" type="text"/>
                    </div>   
                </div>
            </div>

           

            <!--Select Box with Filter Search end-->					
        </div>
        <div class="container-fluid">
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="submit" class="btn btn-success">Publish</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <div class="container-fluid">

            <div class="form-legend">Previous Issues</div>
            <!--Sortable Responsive Media Table begin-->
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped table-responsive" id="tableSortableResMed">
                        <thead class="cf sorthead">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>EditMI&Title</th>
                                <th><input type="checkbox" class="uniformCheckbox" name="selectall" id="selectall" value="checkbox1"></th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($magazineissue as $a)
                            <tr class="gradeX" id="rowCur{{$a->magazine_id}}">
                
                                <td style="width:160px;"><img src="{{ config('constants.awsbaseurl').config('constants.awmagazinedir').$a->imagepath}}" alt="magazineissue" style="width:70%;" /></td>
                                <td><a href="/magazineissue/edit/?id={{$a->magazine_id}}&channel={{$currentChannelId}}">{{$a->title}}</a>
                                </td>
                                <td><a href="/magazineissue/edit/?id={{$a->magazine_id}}&channel={{$currentChannelId}}">{{$a->publish_date_m}}</a>
                                </td>
                                
                                <td><a href="/magazineissue/editmititle/?id={{$a->magazine_id}}&channel={{$currentChannelId}}">Magazine Title And image</a>
                                </td>
                                <td class="center"> <input type="checkbox" class="uniformCheckbox" value="{{$a->magazine_id}}" name="checkItem[]"></td>
                            </tr>
                           @endforeach
                            

                        </tbody>
                    </table>

                </div>
            </div>
            <!--Sortable Responsive Media Table end-->
            <div class="dataTables_paginate paging_bootstrap pagination">
                    
                 {!! $magazineissue->appends(Input::get())->render() !!}
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
            
             function deletemagazineissue() {
                        var ids = '';
                        var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                            var row = 'rowCur' + this.value;
                           
                            return this.value;
                        }).get();
                        
                       // alert(2);
                        var ids = checkedVals.join(",");
                        //alert(ids);return false;
                        $.get("{{ url('/magazineissue/delete/?channel=').$currentChannelId}}",
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
                <button type="button" onclick="deletemagazineissue();" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>						

            </div>
        </div>
</div>

@stop
