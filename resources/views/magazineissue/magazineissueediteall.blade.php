@extends('layouts/master')

@section('title', 'Magazine issued Management - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit Magazine Issue</small></h1>
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
             
            if(valid==0)
                return false;
            else
            return true;
            }
            function errorMessage($msg){
             return '<span class="error">'+$msg+'</span>';
            }
                
                </script>
     

        <script>
            $().ready(function () {
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
                <a href="javascript:;">Edit Magazine Issue</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Edit Magazine Issue</small></h2>

    </header>
    @foreach($posts as $a)
    {!! Form::open(array('url'=>'magazineissue/update','class'=> 'form-horizontal','id'=>'validation_form', 'files' => true,'onsubmit'=>'return addmagazineissuefunction()')) !!}
    {!! csrf_field() !!}
    <input type="hidden" id='magazine_id' name="magazine_id" value="{{$a->magazine_id}}">
    <input type="hidden" id='photoname' name="photoname" value="{{$a->imagepath}}">
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
                            <option value="">--select Channel--</option>
                            @foreach($channels as $channel)
                       <option value="{{ $channel->channel_id }}" @if($channel->channel_id == $a->channel_id) selected="selected" @endif >{{ $channel->channel }}</option>
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
            <div class="form-legend" id="new">Edit Magazine Issue

            </div>


            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="datepicker">
                        Publish Date<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Click to choose date."><i class="icon-photon info-circle"></i></a>
                    </label>
                </div>
                <div class="span9">
                <div class="controls">
                    <input type="text" name="publish_date_m" value="{{$a->publish_date_m}}" id="datepicker" class="span3" />
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
                    <label class="control-label">Cover Photo (Size:{{config('constants.dimension_magz')}}, File Size<={{config('constants.maxfilesize').' '.config('constants.filesizein')}})</label>
                </div>
                <div class="span9">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="input-append">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input name="photo" type="file"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_magz')}}')">&nbsp;Need to crop images? Click here</a>
                        </div>
                         
                        <img src="{{ config('constants.awsbaseurl').config('constants.awmagazinedir').$a->imagepath}}" alt="magazineissue" style="width:80px;" />
                    </div>
                </div>
            </div>

            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="inputField">Name</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="title" name="title" type="text" value="{{$a->title}}">
                    </div>
                </div>
            </div>

        </div>

        <div class="container-fluid">

            <div class="form-legend">FlIP URL</div>

            <!--Select Box with Filter Search begin-->
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Url</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="flip_url" name="flipbook_url" value="{{$a->flipbook_url}}" type="text"/>
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
                        <input id="buy_digital" name="buy_digital" value="{{$a->buy_digital}}" type="text"/>
                    </div>   
                </div>
            </div>

           

            <!--Select Box with Filter Search end-->					
        </div>

        <div class="container-fluid">
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="submit" class="btn btn-success">Update</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
       @endforeach 
</div>

@stop