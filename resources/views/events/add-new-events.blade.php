@extends('layouts/master')

@section('title', 'Add-edit-events - BWCMS')


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
        <h2><small>Add new event</small></h2>

    </header>
    <form class="form-horizontal" action="/event/add" method="POST"enctype= "multipart/form-data"  onsubmit="return validateEventData()">
         {!! csrf_field() !!}
        <div class="container-fluid" @if((!Session::has('message')) && (!Session::has('error')))style="display: none" @endif>
           
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
                       <option @if($channel->channel_id == $currentChannelId) @endif value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
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
            <div id="event-details" class="form-legend">Event Details</div>
            <!--Text Area - No Resize begin-->
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Title </label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="title" type="text" name="title" />
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
                        <textarea  rows="4" class="" name="descripation" id="descripation"></textarea>
                    </div>
                </div>
            </div>
            <!--WYSIWYG Editor - Full Options end-->

            <div id="File_Upload" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Upload Image(Size {{config('constants.dimension_event')}}, File Size <= {{config('constants.maxfilesize').' '.config('constants.filesizein')}})</label>
                </div>
                <div class="span9">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="input-append">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input name="photo" type="file"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_event')}}')">&nbsp;Need to crop images? Click here</a>
                        </div>
                    </div>
                </div>
            </div>
             <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Event Page Url </label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="url" type="text" name="url" />
                    </div>
                </div>
            </div>
            <div id="File_Upload" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Event Type</label>
                </div>
            <div class="span9">
                    <div class="controls">
                        <select name="category" id="selectBoxFilter20">
                            <option value="">--select category--</option>
                            <option value="Sponsored">Sponsored</option>
                            <option value="BW Businessworld"> BW Businessworld</option>
                            <option value="BW Smartcities"> BW Smartcities</option>
                            <option value="BW CIO"> BW CIO</option>
                            <option value="BW Hotelier"> BW Hotelier</option>
                            <option value="BW Wealth"> BW Wealth</option>
                            <option value="BW DealStreet"> BW DealStreet</option>
                            <option value="BW Disrupt"> BW Disrupt</option>
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
                        <input type="text" id="datepicker"  name="startdate" class="span3" />
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
                        <input type="text" id="datepicker2" name="enddate" class="span3" />
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
                        <option  value="">Please Select</option>
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
                            <option value="">Please Select</option>
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
            

<!--            <div id="Article-Details" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Daily Timing</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input type="text" id="hours"  name="hours" class="span3" placeholder="Hours" />&nbsp;&nbsp;&nbsp;<input type="text" id="minutes" class="span3" name="minutes" placeholder="Minutes" />&nbsp;To&nbsp;<input type="text" id="endhours" name="endhours" class="span3" placeholder="Hours" />&nbsp;&nbsp;&nbsp;<input type="text" id="endminutes" name="endminutes" class="span3" placeholder="Minutes" />
                    </div>
                </div>
            </div>-->
        </div>
        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button type="submit" class="btn btn-success">Publish</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
            </div>
        </div>
        <!--	end container-->

    </form>
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
                $('input[name=startdate]').after(errorMessage('Please enter start date'));
                } 
            if ($('input[name=enddate]').val().trim() == 0){
                valid = 0;
                $('input[name=enddate]').addClass('error');
                $('input[name=enddate]').after(errorMessage('Please enter end date'));
                }
                
            /*
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
               */ 
            
                                    //alert(valid);
            if (valid == 0)
                return false;
                else
                return true;
        }
    function errorMessage($msg){
return '<span class="error author_error">' + $msg + '</span>';
        }
    function getEditcolumnist(id) {
                    //alert(id);
                    $.get("{{ url('/columnist/edit/')}}",
                            {option: id},
                            function (data) {
                                //add to relevant fields
                                //alert(data);
                                
                                var result = jQuery.parseJSON(data);
                                
                                var one;
                                var two;
                                $.each(result, function(index, element) {
                                    //alert(index);
                                    //alert(element);
                                    if(index == 0) {
                                        one = element;
                                    }else{
                                        two = element;
                                    }
                                });
                                $.each(one, function(ind, ele) {
                                    $.each(ele, function(index, element) {
                                      
                                        //alert(index);
                                          //alert(element);
                                        //alert(element);
                                        if (index == 'author_id') {
                                           
                                            $('#qid').val(element);
                                        }
                                        if (index == 'name') {
                                            
                                            $('#name').val(element);
                                        }
                                        if (index == 'bio') {
                                            
                                            $('#bio').val(element);
                                        }
                                        if (index == 'author_type_id') {
                                            
                                             $('#author_type_id').val(element);
                                        }
                                         if (index == 'email') {
                                            
                                             $('#email').val(element);
                                        }
                                       if (index == 'mobile') {  
                                             //var p="";
                                              $('#mobile').val(element);
                                     
                                        }
                                        if (index == 'photo') {  
                                             //var p="";
                                              $('#photo').val(element);
                                     
                                        }
                                        if (index == 'twitter') {  
                                             //var p="";
                                              $('#twitter').val(element);
                                     
                                        }
                                    });
                                });
                                //Loop on all tags, select the one selected
                                
                            });
                }       
</script>
@stop
