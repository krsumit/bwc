@extends('layouts/master')

@section('title', 'Feature Box Management - BWCMS')


@section('content')

    <div class="panel">
        <div class="panel-content filler">
            <div class="panel-logo"></div>
            <div class="panel-header">
                <h1><small>Feature Box Management</small></h1>

            </div>
            <script>
                $(document).ready(function(){
                    $("#validation_form").validate({
                        errorElement: "span",
                        errorClass: "error",
                        onclick: true,
                        rules: {
                            "req": {
                                required: true
                            }
                            
                        }
                    });
                });

                function deleteFB(){
                    //alert(id);
                    //alert($('#selectEdit').val());
                    var id = $('#selectEdit').val();

                    $.get("{{ url('/featurebox/delete/')}}",
                            { option: id },
                            function(data) {
                                var row = 'rowCur' + id;
                                $("#"+ row).hide();
                                //$('#selectEdit').prop('checked', false);
                            });

                    }

                 function getEditFB(id){
                    //alert(id);
                    //alert($('#selectEdit').val());
                    var addID = false;
                    if(id == 'edit') {
                        id = $('#selectEdit').val();
                        addID = true;
                    }
                    $.get("{{ url('/featurebox/edit/')}}",
                            { option: id },
                            function(data) {
                                //add to relevant fields
                                //alert(data);

                                var response=jQuery.parseJSON(data);
                                /*if(typeof response =='object'){
                                    var ele = JSON.parse(data);
                                }*/

                                //alert(ele.length);
                                $.each(response, function(ind, elem) {
                                    //alert(ind);
                                    //alert(elem.length);
                                    $.each(elem, function(index, element) {
                                      //  alert(index);
                                       // alert(element);
                                        if ((index == 'id') && (addID == true)) {
                                            $('#faid').val(element);
                                        }
                                        if (index == 'title') {
                                            $('#title').val(element);
                                        }
                                        if (index == 'description') {
                                            $('#description').val(element);
                                        }
                                        if (index == 'title') {
                                            $('#title').val(element);
                                        }
                                        if (index == 'photopath') {
                                            $('#photo_photo').val(element);
                                            //$('input[name=mediaSel]').val('photo');
                                        }
                                        if (index == 'code') {
                                            $('#code').val(element);
                                            //$('input[name=mediaSel]').val('video');
                                        }
                                        if (index == 'url') {
                                            $('#url').val(element);
                                        }
                                        if (index == 'channel_id') {
                                            $('#channel_id').val(element);
                                            $("#channel_id").select2();

                                           // $('#simpleSelectBox3').append("<option selected='selected' value='element'></option>");
                                            //$('#url').value = element;
                                        }
                                        if (index == 'position2_type') {
                                            $('#position2_type').val(element);
                                            $("#position2_type").select2();
                                        }
                                        if (index == 'position2_title') {
                                            $('#position2_title').val(element);
                                        }
                                        if (index == 'position2_photo') {
                                            $('#position2val_photo').val(element);
                                        
                                        }
                                        if (index == 'position2_url') {
                                            $('#position2_url').val(element);
                                        }
                                        if (index == 'position3_type') {
                                            $('#position3_type').val(element);
                                            $("#position3_type").select2();
                                        }
                                        if (index == 'position3_title') {
                                            $('#position3_title').val(element);
                                        }
                                        if (index == 'position3_photo') {
                                            $('#position3val_photo').val(element);
                                        
                                        }
                                        if (index == 'position3_url') {
                                            $('#position3_url').val(element);
                                        }
                                        if (index == 'photo_id') {
                                            //$('#p_url').selected();
                                            if (element != 0) {
                                                //$('input[name=mediaSel]').val('photo');
                                                $('#p_url').attr('checked', 'checked');
                                            }else{
                                                $('#p_url').attr('checked', false);
                                            }
                                            $.uniform.update();
                                            $("#photourl").show();
                                            $("#videoembd").hide();
                                        }
                                        if (index == 'video_id') {
                                            if (element != 0) {
                                                $('#v_embd').attr('checked', 'checked');
                                                $.uniform.update();
                                                $("#photourl").hide();
                                                $("#videoembd").show();
                                            }
                                        }
                                        //simpleSelectBox3.append("<option value='"+ element +"'>"+ index +"</option>");
                                    });
                                });
                            });

                     $('#selectEdit').prop('checked', false);
                }
            function addfeaturefunction(){
            var valid=1
            $('#new input').removeClass('error');
            if($('#channel_id').val().trim()==0){
            valid=0;
            $('#channel_id').addClass('error');
            $('#channel_id').after(errorMessage('Please select channel'));
            }
            
            if(valid==0)
                return false;
            else
            return true;
            }
            function errorMessage($msg){
             return '<div class="error">'+$msg+'</div>';
            }
                
                </script>
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
                                        "title" : "Channel",
                                        "attr" : { "href" : "#channel" }
                                    }
                                },
                                {
                                    "data" : {
                                        "title" : "Feature Box",
                                        "attr" : { "href" : "#fb" }
                                    }
                                },

                                {
                                    "data" : {
                                        "title" : "Currently Featured",
                                        "attr" : { "href" : "#cf" }
                                    }
                                },

                                {
                                    "data" : {
                                        "title" : "Previously Featured",
                                        "attr" : { "href" : "#pf" }
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
                    <a href="javascript:;">
                        Feature Box Management
                    </a>
                </li>
            </ul>
        </div>      <header>
            <i class="icon-big-notepad"></i>
            <h2><small>Feature Box Management</small></h2>
        </header>
        
        <!--<form class="form-horizontal" id="validation_form">-->
            {!! Form::open(array('url'=>'featurebox/','class'=> 'form-horizontal','id'=>'validation_form', 'files' => true,'onsubmit'=>'return addfeaturefunction()')) !!}
            {!! csrf_field() !!}
            <div class="container-fluid">
                <input type="hidden" name="id" value="{{$uid}}">
                <input type="hidden" name="photo_photo" id="photo_photo" value="">
                <input type="hidden" name="position2val_photo" id="position2val_photo" value="">
                <input type="hidden" name="position3val_photo" id="position3val_photo" value="">
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
                <input type="hidden" id="faid" name="faid" value="">
                <div class="form-legend" id="channel">Channel</div>

                <!--Select Box with Filter Search begin-->
                <div id="assign-article-to-a-Issue" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" for="channel_sel">Channel</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <select name="channel_id" id="channel_id" class="req" required>
                               
                                @foreach($channels as $channel)
                                    <option @if($channel->channel_id==$currentChannelId) selected="selected" @endif value="{{$channel->channel_id}}">{{$channel->channel}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <script>
                        $().ready(function(){
                            $("#channel_id").select2();
                            
                              $("#channel_id").change(function () {
                        $(this).find("option:selected").each(function () {

                            if ($(this).attr("value").trim().length != 0) {

                                window.location = '{{url("featurebox")}}' + '?channel=' + $(this).attr("value").trim();
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

            </input>

            <div class="container-fluid">

                <div class="form-legend" id="fb">Feature Box</div>

                <div class="form-legend" id="fb">Position1</div>
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Title </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="title" id="title" required>
                        </div>
                    </div>
                </div>

                <!--Text Area Resizable begin-->
                <div id="Text_Area_Resizable" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Description</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <textarea  rows="4" name="description" id="description" class="" required></textarea>
                        </div>
                    </div>
                </div>
                <!--Text Area Resizable end-->

                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" >&nbsp;</label>
                    </div>
                    <div class="span3">
                        <label class="radio">
                            <input id="p_url" type="radio" checked name="mediaSel" class="uniformRadio" value="photo">
                            Photo
                        </label>
                    </div>
                    <div class="span3">
                        <label class="radio">
                            <input id="v_embd" type="radio" name="mediaSel" class="uniformRadio" value="video">
                            Video  
                        </label>
                    </div>
                </div>

                <script>
                    $().ready(function(){
                        $(".uniformRadio").uniform({
                            radioClass: 'uniformRadio'
                        });

                    });
                </script>
                <!--script for photo and video selection-->
                <script>
                    $(document).ready(function(e) {
                        $("#videoembd").hide();
                        $("#p_url").click(function(e) {
                            $("#videoembd").hide();
                            $("#photourl").show();
                        });
                        $("#v_embd").click(function(e) {
                            $("#photourl").hide();
                            $("#videoembd").show();
                        });
                    });
                </script>


                <div class="control-group row-fluid" id="photourl">
                    <div class="span3">
                        <label class="control-label">Photo( Size:{{config('constants.dimension_featurebox')}}, File Size<={{config('constants.maxfilesize').' '.config('constants.filesizein')}}) </label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span>
                                    <input type="file" name="photo" id="photo" />
                                </span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_featurebox')}}')">&nbsp;Need to crop images? Click here</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="videoembd" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Embed Code </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <textarea  rows="4" name="code" id="code" class=""></textarea>
                        </div>
                    </div>
                </div>

                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="url" id="url" required>
                        </div>
                    </div>
                </div>
                <div class="form-legend" id="fb">Position 2</div>
                 <!--Select Box with Filter Search begin-->
                <div id="assign-article-to-a-Issue" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" for="channel_sel">Type</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <select name="position2_type" id="position2_type" class="req" >
                                <option  value="">Select</option>
                                <option  value="Archive">Archive</option>
                                <option  value="Quick Byte">Quick Byte</option>
                                <option  value="Interview">Interview</option>
                                <option  value="Photo Feature">Photo Feature</option>
                                <option  value="Face Off">Face Off</option>
                                <option  value="Guest Blog">Guest Blog</option>
                                <option  value="Column">Column</option>
                                <option  value="Analysis">Analysis</option>
                                <option  value="Expert Opinion">Expert Opinion</option>
                                <option  value="View">View</option>
                                <option  value="Counterview">Counterview</option>
                                <option  value="Video">Video</option>
                                <option  value="Sponsored">Sponsored</option>
                            </select>
                        </div>
                    </div>
                    <script>
                        $().ready(function(){
                            $("#position2_type").select2();
                        });
                    </script>
                </div>
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Title </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="position2_title" id="position2_title" >
                        </div>
                    </div>
                </div>

               

               


                <div class="control-group row-fluid" id="photourl">
                    <div class="span3">
                        <label class="control-label">Photo</label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span>
                                    <input type="file" name="position2_photo" id="position2_photo"  value=""/>
                                </span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>

               

                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="position2_url" id="position2_url" >
                        </div>
                    </div>
                </div>
                    <div class="form-legend" id="fb">Position 3</div>
                     <div id="assign-article-to-a-Issue" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" for="channel_sel">Type</label>
                    </div>
                     <div class="span9">
                        <div class="controls">
                            <select name="position3_type" id="position3_type" class="req" >
                                <option  value="">Select</option>
                                <option  value="Archive">Archive</option>
                                <option  value="Quick Byte">Quick Byte</option>
                                <option  value="Interview">Interview</option>
                                <option  value="Photo Feature">Photo Feature</option>
                                <option  value="Face Off">Face Off</option>
                                <option  value="Guest Blog">Guest Blog</option>
                                <option  value="Column">Column</option>
                                <option  value="Analysis">Analysis</option>
                                <option  value="Expert Opinion">Expert Opinion</option>
                                <option  value="View">View</option>
                                <option  value="Counterview">Counterview</option>
                                <option  value="Video">Video</option>
                                 <option  value="Sponsored">Sponsored</option>
                            </select>
                        </div>
                    </div>
                    <script>
                        $().ready(function(){
                            $("#position3_type").select2();
                        });
                    </script>
                </div>
                                
                <div id="Article-Details" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Title </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="position3_title" id="position3_title" >
                        </div>
                    </div>
                </div>

               

              

                
               

                <div class="control-group row-fluid" id="photourl">
                    <div class="span3">
                        <label class="control-label">Photo</label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span>
                                    <input type="file" name="position3_photo" id="position3_photo"  value=""/>
                                </span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>

              
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="url" name="position3_url" id="position3_url" >
                        </div>
                    </div>
                </div>

                <div class="control-group row-fluid">
                    <div class="span12 span-inset">
                        <button type="submit" name="saveFB" id="saveFB" class="btn btn-info">Save</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>

                    </div>
                </div>
            </div>
            {!! Form::close() !!}

            <div class="container-fluid">
                <input type="hidden" name="id" value="{{$uid}}">
                <div class="form-legend" id="cf">Currently Featured</div>
                <div class="row-fluid">
                    <div class="span12">
                        <table class="table table-striped" id="tableSortable">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Featured On</th>
                                <th>Added By</th>
                                <th>Views</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                           @if($current)
                            <tr class="gradeX" id="rowCur{{$current->id}}">
                                <td>{{$current->title}}</td>
                                <td>{{$current->featured_on}}</td>
                                <td>{{$current->name}}</td>
                                <td class="center">-----</td>
                                <td class="center"> <input type="checkbox" id="selectEdit" name="editFBID" class="uniformCheckbox" value="{{$current->id}}"></td>
                            </tr>
                           @endif

                            </tbody>
                        </table>
                    </div>
                </div>


                <script>
                    $(document).ready(function() {
                        $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable( {
                            "bFilter": false, "bInfo": false, "bPaginate": false,
                            "sPaginationType": "bootstrap",
                            "fnInitComplete": function(){
                                $(".dataTables_wrapper select").select2({
                                    dropdownCssClass: 'noSearch'

                                });
                            }
                        });
                    });

                </script>

                <div class="control-group row-fluid">
                    <div class="span12 span-inset">
                        <button type="button" onclick="getEditFB('edit');" name="status" value="edit" class="btn btn-warning">Edit</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                        <button type="button" onclick="deleteFB();" name="status" value="delete" class="btn btn-danger">Delete</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                    </div>
                </div>

            </div><!--end container-->


            <div class="container-fluid">
                <div class="form-legend" id="pf">Previously Featured</div>
                <div class="row-fluid">
                    <div class="span12">
                        <table class="table table-striped" id="tableSortable2">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Featured On</th>
                                <th>Added By</th>
                                <th>Views</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($old as $olds)
                            <tr class="gradeX">
                                <td><a href="#" onclick="getEditFB({{$olds->id}});">{{$olds->title}}</a></td>
                                <td>{{$olds->featured_on}}</td>
                                <td>{{$olds->name}}</td>
                                <td class="center">----</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                <script>
                    $(document).ready(function() {
                        $('#tableSortable2, #tableSortableRes, #tableSortableResMed').dataTable( {
                            "sPaginationType": "bootstrap",
                            "aaSorting":[],
                            "fnInitComplete": function(){
                                $(".dataTables_wrapper select").select2({
                                    dropdownCssClass: 'noSearch'
                                });
                            }
                        });
                    });
                </script>


            </div><!--end container-->

    </div>


    @stop
