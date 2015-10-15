@extends('layouts/master')

@section('title', 'edit-events - BWCMS')


@section('content') 

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit Events</small></h1>

        </div>

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
                                    "attr": {"href": "#channel"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Event Details",
                                    "attr": {"href": "#event-details"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Event Schedule",
                                    "attr": {"href": "#event-schedule"}
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
            <li>
                <a href="#">Events</a>
                <ul class="breadcrumb-sub-nav">
                    <li>
                        <a href="new-events.html">New Events</a>
                    </li>
                    <li>
                        <a href="published-events.html">Published Events</a>
                    </li>
                    <li>
                        <a href="deleted-events.html">Deleted Events</a>
                    </li>
                    <li>
                        <a href="#">Reports</a>
                    </li>
                    <li>
                        <a href="#">Help</a>
                    </li>
                </ul>
            </li>
            <li class="current">
                <a href="javascript:;">Edit Events</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Event ID: 45345</small></h2>

    </header>
   @foreach($posts as $a)
    <form class="form-horizontal" action="/event/add" method="POST"enctype= "multipart/form-data"  onsubmit="return validateEventData()">
        <div class="container-fluid">
            {!! csrf_field() !!}
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
                           
                            <option value="{{$a->channel_id}}">@if($a->channel_id =='1'){{'BW'}}@endif</option>
                            <option value="1">BW</option>
                            <option value="0">Channel3</option>
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
            <div id="event-details" class="form-legend">Event Details</div>
            <!--Text Area - No Resize begin-->
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Title </label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="title" type="text" name="title"  value="{{$a->title}}"/>
                    </div>
                </div>
            </div>
            <!--Text Area - No Resize end-->

            <!--WYSIWYG Editor - Full Options-->
            <div id="WYSIWYG_Editor_-_Full_Options" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Event Description</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <textarea  rows="4" class="" name="descripation" id="descripation"> {{$a->description}}</textarea>
                    </div>
                </div>
            </div>
            <!--WYSIWYG Editor - Full Options end-->

            <div id="File_Upload" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Upload Image</label>
                </div>
                <div class="span9">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="input-append">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input name="photo" type="file"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                        </div>
                    </div>
                </div>
            </div>
             <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Image Url </label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="url" type="text" name="url"value="{{$a->image_url}}" />
                    </div>
                </div>
            </div>
            <div id="File_Upload" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Event Type</label>
                </div>
                <input type="hidden" name="p_photo" value="{{$a->imagepath}}">
            <div class="span9">
                    <div class="controls">
                        <select name="category" id="selectBoxFilter20">
                             <option value="{{$a->category}}" selected='selected'>{{ $a->category }}</option> 
                            <option value="ponsored">Sponsored</option>
                            <option value="bwbusinessworld"> BW Businessworld</option>
                            <option value="bwsmartcities"> BW Smartcities</option>
                            <option value="bwcio"> BW CIO</option>
                            <option value="bwhotelier"> BW Hotelier</option>
                            <option value="bwwealth"> BW Wealth</option>
                            <option value="bwdealStreet"> BW DealStreet</option>
                            <option value="bwdisrupt"> BW Disrupt</option>
                        </select>
                    </div>
                </div>
            </div>
            <script>
               $().ready(function(){
               $(".uniformCheckbox").uniform();
               });            
            </script>
        </div><!-- end container1 -->


        <div class="container-fluid">
            <div id="event-schedule" class="form-legend">Event Schedule</div>
            <!--Text Area - No Resize begin-->
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="datepicker">
                        Start Date
                    </label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input type="text" id="datepicker"  name="startdate" value="{{$a->start_date}}" class="span3" />
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
            <div id="Simple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="datepicker">
                        End Date
                    </label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input type="text" id="datepicker2" name="enddate" class="span3" value="{{$a->end_date}}" />
                    </div>
                </div>
            </div>
            <script>
                $(function () {
                    $("#datepicker2").datepicker();
                    $("#datepickerInline").datepicker();
                    $("#datepickerMulti").datepicker({
                        numberOfMonths: 3,
                        showButtonPanel: true
                    });
                    $('#timeEntry').timeEntry().change();
                });
            </script> 
            <!--Text Area - No Resize end-->
            <div id="Simple_Select_Box_with_Filter_Search" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Select Location</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="country" id="selectBoxFilter6">
                       <option value="{{$a->country}}" selected='selected'>{{ $a->countryname }}</option> 
                        @foreach($country as $countrye)
                        <option value="{{ $countrye->country_id }}">{{ $countrye->name }}</option>
                        @endforeach	                                        
                    </select>
                </div>
            </div>
            <script>
                                $(document).ready(function(){
                        $("#selectBoxFilter6").select2();
                                $("#selectBoxFilter6").change(function(){
                        $(this).find("option:selected").each(function(){
                        if ($(this).attr("value") == "1"){
                        $("#tabState").show();
                       
                        } else{
                        $("#selectBoxFilter7").select2();
                        $("#tabState").hide();
                        
                        }

                        });
                        }).change();
                        });</script>
        </div>

        <div id="tabState" class="control-group row-fluid">
            <div id="Simple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter"></label>
                </div>
                <div class="span9" >
                    <div class="controls">
                        <select name="state" id="selectBoxFilter7">
                             <option value="{{$a->state}}" selected='selected'>{{ $a->name }}</option> 
                             @foreach($states as $state)
                            <option value="{{ $state->state_id}}">{{ $state->name }}</option>		
                            @endforeach
                        </select>
                    </div>
                </div>
                <script>
                                    $().ready(function(){
                            $("#selectBoxFilter7").select2();
                            });</script>
            </div>

        </div>    
            

            
        </div>
        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button type="submit" class="btn btn-success">Publish</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
            </div>
        </div>
        <!--	end container-->

    </form>
   @endforeach
</div>
 
<script>
    function validateEventData(){
           var valid = 1;
                $('.author_error').remove();
                $('#new input').removeClass('error');
                $('#new textarea').removeClass('error');
               
            if ($('select[name=channel]').val().trim() == 0){
                valid = 0;
                $('select[name=channel]').addClass('error');
                $('select[name=channel]').after(errorMessage('Please enter channel'));
                }
            if ($('input[name=title]').val().trim() == 0){
                valid = 0;
                $('input[name=title]').addClass('error');
                $('input[name=title]').after(errorMessage('Please enter name'));
                }
            if ($('textarea[name=descripation]').val().trim() == 0){
                valid = 0;
                $('textarea[name=descripation]').addClass('error');
                $('textarea[name=descripation]').after(errorMessage('Please enter bio'));
                }
            if ($('input[name=startdate]').val().trim() == 0){
                valid = 0;
                $('input[name=startdate]').addClass('error');
                $('input[name=startdate]').after(errorMessage('Please enter email'));
                } 
            if ($('input[name=enddate]').val().trim() == 0){
                valid = 0;
                $('input[name=enddate]').addClass('error');
                $('input[name=enddate]').after(errorMessage('Please enter mobile'));
                }
            if ($('input[name=hours]').val().trim() == 0){
                valid = 0;
                $('input[name=hours]').addClass('error');
                $('input[name=hours]').after(errorMessage('Please enter mobile'));
                } 
            if ($('input[name=minutes]').val().trim() == 0){
                valid = 0;
                $('input[name=minutes]').addClass('error');
                $('input[name=minutes]').after(errorMessage('Please enter mobile'));
                }
            if ($('input[name=endhours]').val().trim() == 0){
                valid = 0;
                $('input[name=endhours]').addClass('error');
                $('input[name=endhours]').after(errorMessage('Please enter mobile'));
                }
            if ($('input[name=endminutes]').val().trim() == 0){
                valid = 0;
                $('input[name=endminutes]').addClass('error');
                $('input[name=endminutes]').after(errorMessage('Please enter mobile'));
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
