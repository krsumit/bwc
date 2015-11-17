@extends('layouts/master')

@section('title', 'CMS Management - BWCMS')


@section('content')
<?php //echo '<pre>';print_r($delArrcheck); exit; ?>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Right Management</small></h1>
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
                                "title" : "CMS Rights", 
                                "attr" : { "href" : "#rights" } 
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
            <a href="#">Right Management</a>
        </li>
    </ul>
</div>           <header>
               <i class="icon-big-notepad"></i>
               <h2><small>Right Management</small></h2>
              
           </header>
           {!! Form::open(array('url'=>'rights/manage/','class'=> 'form-horizontal','id'=>'form1')) !!}
            {!! csrf_field() !!}
            <div class="container-fluid">

                        <div class="form-legend" id="Notifications">Notifications</div>

                        <!--Notifications begin-->
                        <div class="control-group row-fluid">
                            <div class="span12 span-inset">
                                @if (count($errors) > 0)
                                <div class="alert alert-error alert-block">
                                    <i class="icon-alert icon-alert-info"></i>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>This is Error Notification</strong>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    <!--<span>Please select a valid search criteria.</span>-->
                                </div>
                                @endif				
                            </div>
                        </div>
                        <!--Notifications end-->

                </div>
		<div class="container-fluid">
                       <input type="hidden" name="id" value="{{$userid}}">
			<div class="form-legend" >Edit Profile</div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                    <label class="control-label" for="inputField">Name</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <input id="inputField" name="name" type="text" value="{{ $name }}">
                                </div>
                            </div>
                        </div>
                                           
                        <div class="control-group row-fluid">
                                                <div class="span3">
                                                    <label class="control-label" for="inputField">Email </label>
                                                </div>
                                                <div class="span9">
                                                    <div class="controls">
                                                        <input  name="email" type="email" value="{{ $email}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group row-fluid">
                                                <div class="span3">
                                                    <label class="control-label" for="inputField">Mobile</label>
                                                </div>
                                                <div class="span9">
                                                    <div class="controls">
                                                        <input  name="mobile" type="tel" maxlength="10" value="{{ $mobile}}">
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
                                                        <select name="role" id="roleid">
                                                            <option selected="" value="">---Please Select---</option>
                                                            @foreach($roles as $role)
                                                            <option value="{{$role->user_types_id}}">{{$role->label}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <script>
                                                    $().ready(function(){
                                                        var r = {{ $roleO }};                                                        
                                                        $("#roleid").val(r);
                                                        $("#roleid").select2({
                                                            dropdownCssClass: 'noSearch'
                                                        });                                                        
                                                    });
                                                </script>
                                            </div>
                                            <!--Simple Select Box end-->
                                             
                                               <div class="control-group row-fluid">
                                                    <div class="span3">
                                                        <label class="control-label">Channel</label>
                                                    </div>
                                                   @foreach($allchannel as $channel)
                                                    <div class="span3">
                                                        <label class="radio">
                                                            <input type="checkbox"  @if(in_array($rightChannels[$channel->channel_id], $delArrcheck))  {{'checked="checked"'}} @endif   name="rightArr[]" class="uniformRadio" value="{{$rightChannels[$channel->channel_id]}}">
                                                           {{$channel->channel}}
                                                        </label>
                                                    </div>
                                                 @endforeach
                                                 
                                                </div>
                                            
                                            <div class="control-group row-fluid">
                                            <div class="span12 span-inset">
                                                <button class="btn btn-primary" type="submit" name="save" style="display:block;">Save</button>
                                            </div>
                                        </div>
                                  

                </div>
				  
				  
	<div class="container-fluid">

                        <div class="form-legend" id="rights">CMS Rights</div>
                        
                        
                        <div class="control-group row-fluid">
                            <div class="span12">
                                <label class="checkbox">
                                    <input checked="checked" type="checkbox" class="uniformCheckbox" value="checkbox1">
                                    Dashboard
                                </label>
							</div>
                        </div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="checkbox">
                                    <input checked="checked" type="checkbox" class="uniformCheckbox" value="checkbox1">
                                    Articles
                                </label>
							</div>
							<div class="span9">
                            	<ul style="list-style:none;">
                                	<li>							
                                    <label class="checkbox">
                                        <input @if(in_array('1', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="1">
                                        Create New Article
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('11', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="11">
                                        New Article
                                    </label>
                                    		<ul style="list-style:none; margin-left:80px;">
                                            	<li>
                                                	<label class="checkbox">
                                                        <input @if(in_array('checkbox2', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox2">
                                                        Choose Author
                                                    </label>
                                                </li>
                                                <li>
                                                	<label class="checkbox">
                                                        <input @if(in_array('3', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="3">
                                                        Add Author
                                                    </label>
                                                </li>
                                                <li>
                                                	<label class="checkbox">
                                                        <input @if(in_array('2', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="2">
                                                        Schedule Article
                                                    </label>
                                                </li>
                                            </ul>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('8', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="8">
                                        Edit Article
                                    </label>
                                        <ul style="list-style:none; margin-left:80px;">
                                            	<li>
                                                	<label class="checkbox">
                                                        <input @if(in_array('checkbox2', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox2">
                                                        Choose Author
                                                    </label>
                                                </li>
                                                <li>
                                                	<label class="checkbox">
                                                        <input @if(in_array('9', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="9">
                                                        Add Author
                                                    </label>
                                                </li>
                                                <li>
                                                	<label class="checkbox">
                                                        <input @if(in_array('10', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="10">
                                                        Schedule Article
                                                    </label>
                                                </li>
                                                <li>
                                                	<label class="checkbox">
                                                        <input @if(in_array('13', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="13">
                                                        Delete Article
                                                    </label>
                                                </li>
                                                <li>
                                                	<label class="checkbox">
                                                        <input @if(in_array('12', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="12">
                                                        Publish Direct
                                                    </label>
                                                </li>
                                            </ul>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('15', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="15">
                                        Scheduled Article
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('16', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="16">
                                        Published Article
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('17', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="17">
                                        Deleted Article
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('18', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="18">
                                        My Drafts
                                    </label>
                                    		<ul style="list-style:none; margin-left:80px;">
                                            	<li>
                                                	<label class="checkbox">
                                                        <input @if(in_array('', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="">
                                                        Publish Direct
                                                    </label>
                                                </li>
                                            </ul>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('19', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="19">
                                        Feature Box Management
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('30', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="30">
                                        Campaign Management
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('31', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="31">
                                        Add A Magazine Issue
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="">
                                        Tips
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('32', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="32">
                                        Report
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('33', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="33">
                                        Help
                                    </label>
                                    </li>
                                 </ul>
							</div>
                        </div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="checkbox">
                                    <input @if(in_array('checkbox1', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox1">
                                    Tips &amp; Quotes
                                </label>
							</div>
							<div class="span9">
                            	<ul style="list-style:none;">
                                	<li>							
                                    <label class="checkbox">
                                        <input @if(in_array('20', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="20">
                                        Tips
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input  @if(in_array('21', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="21">
                                        Tags
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input  @if(in_array('34', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="34">
                                        Quotes
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('35', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="35">
                                        Report
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('36', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="36">
                                        Help
                                    </label>
                                    </li>
                                 </ul>
							</div>
                        </div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="checkbox">
                                    <input @if(in_array('checkbox1', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox1">
                                    Debate
                                </label>
							</div>
							<div class="span9">
                            	<ul style="list-style:none;">
                                	<li>							
                                    <label class="checkbox">
                                        <input @if(in_array('38', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="38">
                                        Published Debate
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input  @if(in_array('37', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="37">
                                        Create New Debate
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('39', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="39">
                                        Debate Comments
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input   @if(in_array('40', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="40">
                                        Profanity Filter
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('41', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="41">
                                        Report
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input  @if(in_array('42', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="42">
                                        Help
                                    </label>
                                    </li>
                                 </ul>
							</div>
                        </div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="checkbox">
                                    <input @if(in_array('checkbox1', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox1">
                                    Columnist and Guest Author
                                </label>
							</div>
							<div class="span9">
                            	<ul style="list-style:none;">
                                	<li>							
                                    <label class="checkbox">
                                        <input @if(in_array('43', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="43">
                                       Add/Edit Columnist
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input  @if(in_array('44', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="44">
                                       Add New Guest Author
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('45', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="45">
                                        Report
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('46', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="46">
                                        Help
                                    </label>
                                    </li>
                                 </ul>
							</div>
                        </div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="checkbox">
                                    <input @if(in_array('checkbox1', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox1">
                                    Events
                                </label>
							</div>
							<div class="span9">
                            	<ul style="list-style:none;">
                                	<li>							
                                    <label class="checkbox">
                                        <input @if(in_array('47', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="47">
                                       Add New Event
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input  @if(in_array('48', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="48">
                                       Published Events
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('49', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="49">
                                       Deleted Events
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('50', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="50">
                                        Report
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('51', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="51">
                                        Help
                                    </label>
                                    </li>
                                 </ul>
							</div>
                        </div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="checkbox">
                                    <input @if(in_array('checkbox1', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox1">
                                    Quick Bytes
                                </label>
							</div>
							<div class="span9">
                            	<ul style="list-style:none;">
                                	<li>							
                                    <label class="checkbox">
                                        <input @if(in_array('22', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="22">
                                       Add New Quick Byte
                                    </label>
                                    		<ul style="list-style:none; margin-left:80px;">
                                            	<li>
                                                	<label class="checkbox">
                                                        <input @if(in_array('checkbox2', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox2">
                                                        Choose Author
                                                    </label>
                                                </li>
                                            </ul>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('24', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="24">
                                       Published Quick Bytes
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input type="checkbox"@if(in_array('25', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="25">
                                       Deleted Quick Bytes
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('52', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="52">
                                        Report
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('53', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="53">
                                        Help
                                    </label>
                                    </li>
                                 </ul>
							</div>
                        </div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="checkbox">
                                    <input @if(in_array('checkbox1', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox1">
                                    Sponsored Post
                                </label>
							</div>
							<div class="span9">
                            	<ul style="list-style:none;">
                                	<li>							
                                    <label class="checkbox">
                                        <input @if(in_array('26', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="26">
                                       Add New Sponsored Post
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input type="checkbox"@if(in_array('28', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="28">
                                       Published Sponsored Post
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input type="checkbox" @if(in_array('29', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="29">
                                       Deleted Sponsored Post
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('54', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="54">
                                        Report
                                    </label>
                                    												
                                    <label class="checkbox">
                                        <input @if(in_array('55', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="55">
                                        Help
                                    </label>
                                    </li>
                                 </ul>
							</div>
                        </div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="checkbox">
                                    <input @if(in_array('checkbox1', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox1">
                                    Photos
                                </label>
							</div>
							<div class="span9">
                            	<ul style="list-style:none;">
                                	<li>							
                                    <label class="checkbox">
                                        <input @if(in_array('56', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="56">
                                       Upload New Album
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('57', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="57">
                                       Published Album
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input  @if(in_array('58', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="58">
                                       Deleted Photos
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('59', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="59">
                                        Edit Album
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('60', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="60">
                                        Report
                                    </label>
                                    </li>
                                 </ul>
							</div>
                        </div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="checkbox">
                                    <input  @if(in_array('checkbox1', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox1">
                                    Videos
                                </label>
							</div>
							<div class="span9">
                            	<ul style="list-style:none;">
                                	<li>							
                                    <label class="checkbox">
                                        <input  type="checkbox" @if(in_array('61', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="61">
                                       Upload New Video
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input type="checkbox" @if(in_array('62', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="62">
                                       Published Video
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input type="checkbox"@if(in_array('63', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="63">
                                       Deleted Video
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input type="checkbox" @if(in_array('64', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="64">
                                       Featured Video
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('65', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="65">
                                        Report
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('66', $delArrcheck))
  {{'checked="checked"'}} @endif  type="checkbox" class="uniformCheckbox" name="rightArr[]" value="66">
                                        Help
                                    </label>
                                    </li>
                                 </ul>
							</div>
                        </div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="checkbox">
                                    <input @if(in_array('checkbox1', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox1">
                                    Site Management
                                </label>
							</div>
							<div class="span9">
                            	<ul style="list-style:none;">
                                	<li>							
                                    <label class="checkbox">
                                        <input  type="checkbox" @if(in_array('67', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="67">
                                       Category Master
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input type="checkbox" @if(in_array('68', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="68">
                                       Location Master
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input type="checkbox" @if(in_array('69', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="69">
                                       Topic Master
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input type="checkbox" @if(in_array('70', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="70">
                                       Tag Master
                                    </label>
                                    </li>
                                 </ul>
							</div>
                        </div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="checkbox">
                                    <input  type="checkbox" @if(in_array('checkbox1', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" value="checkbox1">
                                    Rights Management
                                </label>
							</div>
							<div class="span9">
                            	<ul style="list-style:none;">
                                	<li>							
                                    <label class="checkbox">
                                        <input  type="checkbox"@if(in_array('71', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="71">
                                       CMS Rights
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input type="checkbox" @if(in_array('72', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="72">
                                       Report
                                    </label>
                                    </li>
                                     <li>												
                                    <label class="checkbox">
                                        <input type="checkbox" @if(in_array('73', $delArrcheck))
  {{'checked="checked"'}} @endif class="uniformCheckbox" name="rightArr[]" value="73">
                                       Help
                                    </label>
                                    </li>
                                 </ul>
							</div>
                        </div>
                        
                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="checkbox">
                                    <input @if(in_array('checkbox1', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" value="checkbox1">
                                    Help
                                </label>
							</div>
							<div class="span9">
                            	<ul style="list-style:none;">
                                	<li>							
                                    <label class="checkbox">
                                        <input @if(in_array('74', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="74">
                                       FAQs
                                    </label>
                                    </li>
                                    <li>												
                                    <label class="checkbox">
                                        <input @if(in_array('75', $delArrcheck))
  {{'checked="checked"'}} @endif type="checkbox" class="uniformCheckbox" name="rightArr[]" value="75">
                                       Sitemap
                                    </label>
                                    </li>
                                 </ul>
							</div>
                        </div>
                        
                    <script>
                        $().ready(function(){
                            $(".uniformCheckbox").uniform();
                        });            
                    </script>
                    
                    	<div class="control-group row-fluid ">
                            <div class="span12 span-inset ">
                                 <button type="submit" name="saveRights" class="btn btn-info">Save</button><img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>
                                 <button type="submit" name="" class="btn btn-success">Assign</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                            </div>
                        </div>
                    
                    
                </div><!-- end container -->

				    
       {!! Form::close() !!}
   </div>
@stop 