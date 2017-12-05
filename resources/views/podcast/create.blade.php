@extends('layouts/master')

@section('title', 'Create Podcast - BWCMS')

@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Add Podcast Album</small></h1>
        </div>
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
                                "attr" : { "href" : "#Channel" } 
                            }
                        },
						
												{
                            "data" : { 
                                "title" : "Add Album", 
                                "attr" : { "href" : "#add-album" } 
                            }
                        },
                                                {
                            "data" : { 
                                "title" : "Tags", 
                                "attr" : { "href" : "#tags" } 
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
        </div>    
    </div>
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
                <a href="#">Album &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                <ul class="breadcrumb-sub-nav">
                    <li>
                        <a href="create-new-quickbyte.html">Add Album</a>
                    </li>
                </ul>
             </li>       
        </ul>
    </div>            
    <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Album ID: 45345</small></h2>
    </header>
    <form class="form-horizontal" id="fileupload" action="/padcast/store/" method="POST" enctype="multipart/form-data">
    {!! csrf_field() !!} 
        <div class="container-fluid">
            <div class="form-legend" id="Notifications">Notifications</div>
            <!--Notifications begin-->
            <div class="control-group row-fluid">
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
            <div class="form-legend" id="Channel">Channel</div>
            <!--Select Box with Filter Search begin-->
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Channel</label>
                </div>
		<div class="span9">
                    <div class="controls">
                       <select name="channel"  id="channel" class="formattedelement">
                        @foreach($channels as $channel)
                        <option value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <script>
                    $().ready(function(){
                        $("#channel").select2();
                    });
                </script>
            </div>
            <!--Select Box with Filter Search end-->					
        </div>  
        <div class="container-fluid">  
            <div class="form-legend" id="add-album">Add Album </div>
		<div id="Photo-feature"  class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Album Name </label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                           <input type="text" name="album_name" id="album_name">
                        </div>
                    </div>
                </div>
		<div id="Text_Area_Resizable" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Album Description</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <textarea  rows="4" class="" name ="album_description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Upload Photo</label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> 
                                    <span class="fileupload-preview">Upload Photo </span>
                                </div>
                                <span class="btn btn-file" style="margin-bottom:0px;">
                                        <span class="fileupload-new">Browse</span>
                                        <span class="fileupload-exists">Change</span>
                                        <input type="file"  name ="file" value=""/>
                                </span>
                                <a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>      
            </div>
                  
            <div class="container-fluid">

                <div class="form-legend" id="tags">Tags</div>
                <!--Select Box with Filter Search begin-->
                <div class="control-group row-fluid" id="Multiple_Select_Box_with_Filter_Search">
                    <div class="control-group row-fluid">    
                        <div class="span3">
                        <label for="multiFilter" class="control-label">Tags</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" class="valid" name="Taglist" id="Taglist"/>
                        </div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" for="add tags">Add New Tags<br>(Separated by Coma. No spaces)</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="addtags" class="valid"><span for="add tags" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                        </div>
                    </div>
                    <div class="span12 span-inset">
                        <div style="float:right; width:11%; margin-bottom:5px;"><button type="button" class="btn btn-primary" id="attachTag" style="display:block;">Attach</button>
                            <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:50%; display:block; margin-left:15px;display:none;">
                        </div>
                    </div>
                </div>
                <!-- Add Tag to Tags Table - Ajax request -->
                <script>
                    $().ready(function () {
                        var token = $('input[name=_token]');
                        // process the form
                        $("#attachTag").click(function () {
                            if ($('input[name=addtags]').val().trim().length == 0) {
                                alert('Please enter tage');
                                return false;
                            }

                            $.ajax({
                                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                                url: '/article/addTag', // the url where we want to POST
                                data: {tag: $('input[name=addtags]').val()},
                                dataType: 'json', // what type of data do we expect back from the server
                                encode: true,
                                beforeSend: function (data) {
                                    $('#attachTag').hide();
                                    $('#attachTag').siblings('img').show();
                                },
                                complete: function (data) {
                                    $('#attachTag').show();
                                    $('#attachTag').siblings('img').hide();
                                },
                                success: function (data) {

                                    $.each(data, function (key, val) {

                                        $("#Taglist").tokenInput("add", val);
                                    });
                                    $('input[name=addtags]').val('');
                                    // alert('Tag Saved');

                                },
                                headers: {
                                    'X-CSRF-TOKEN': token.val()
                                }
                            })
                        });
                        $("#Taglist").tokenInput("/tags/getJson", {
                            theme: "facebook",
                            searchDelay: 300,
                            minChars: 4,
                            preventDuplicates: true,
                        });
                    });
                </script>
            </div>                       
            <!--Select Box with Filter Search end-->
        </div>
        <div class="container-fluid">
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="submit" name ="submit" class="btn btn-success">Submit</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>       			
                </div>
            </div>
        </div>
    			
    </form>    
        <!--end container-->				
	<div class="container-fluid">
            <div class="form-legend">Album List</div>
             <!--Sortable Responsive Media Table begin-->
            <div class="row-fluid">
		<div class="span12">
                    <table class="table table-striped" id="tableSortable">
			<thead class="cf sorthead">
                            <tr>
                               <th>Image</th>
                               <th>Name</th>
                               <th>Date</th>
                               <th>Feature</th>
                               <th>Edit</th>
                               <th><input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall"></th>
                            </tr>
			</thead>
                        <tbody>
                            @foreach($postsArr as $posts)
                                <tr  class="gradeX"  id="rowCur{{$posts->id}}">
                                   <td style="width:160px;"><img src="http://www.PROJECTONE.com/upload/cjoriginalphoto/1337154571426.JPG" alt="user" style="width:70%;" /></td> 
                                  
                                   <td><a href="/podcast/uloadlist?id={{ $posts->id }}">{{ $posts->album_name }}</a></td>
                                    <td >{{ $posts->updated_at }}</td>
                                    <td>
                                       <input name="isf" class="uniformRadio" value="{{ $posts->id }}" type="radio">
                                   </td>
                                   <td> <a href="/podcast/edit?editid={{ $posts->id }}"><button type="button" class="btn btn-success">Edit</button> </a></td>
                                   <td><input type="checkbox" class="uniformCheckbox" name="checkItem[]" value="{{ $posts->id }}"></td>
                                  
                                  
                               </tr>
                              @endforeach  
                              <script>
                        $(document).ready(function () {

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

                        function deletePodcast() {
                                var ids = '';
                                var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                                    //var row = 'rowCur' + this.value;
                                    //$("#" + row).hide();
                                    return this.value;
                                }).get();
                                if (checkedVals.length > 0) {
                                    var ids = checkedVals.join(",");
                                    //alert(ids);return false;
                                    $.get("{{ url('/podcast/delete/?channel=').$currentChannelId}}",
                                            {option: ids},
                                    function (data) {
                                        if (data.trim() == 'success') {
                                            $.each(checkedVals, function (i, e) {
                                                var row = 'rowCur' + e;
                                                $("#" + row).hide();
                                            });
                                            $('#notificationdiv').show();
                                            $('#notificationdiv .control-group .span12.span-inset').html('<div class="alert alert-success alert-block">\n\
                                        <i class="icon-alert icon-alert-info"></i><button type="button" class="close" data-dismiss="alert">\n\
                                        &times;</button><strong>This is Success Notification</strong>\n\
                                        <span></span>Selected records dumped.</div>');
                                        } else {
                                            alert(data);
                                        }
                                        //alert(1);
                                    });
                                } else {
                                    alert('Please select at least one recordt.');
                                }
                            }

                        function isFeature() {
                            var ids = '';
                            var checkedVals = $("input[name='isf']:checked").val();
                            if (checkedVals.length > 0) {
                                var ids = checkedVals;
                                //alert(ids);return false;
                                $.get("{{ url('/podcast/isfeature/?channel=').$currentChannelId}}",
                                        {option: ids},
                                function (data) {
                                    if (data.trim() == 'success') {

                                        $('#notificationdiv').show();
                                        $('#notificationdiv .control-group .span12.span-inset').html('<div class="alert alert-success alert-block">\n\
                                    <i class="icon-alert icon-alert-info"></i><button type="button" class="close" data-dismiss="alert">\n\
                                    &times;</button><strong>This is Success Notification</strong>\n\
                                    <span></span>Selected records Feature.</div>');
                                    } else {
                                        alert(data);
                                    }
                                    //alert(1);
                                });
                            } else {
                                alert('Please select at least one records.');
                            }
                        }
                    </script>
                       </tbody>
                    </table>
                </div>
            </div>
            <!--Sortable Responsive Media Table end-->
					
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="button" class="btn btn-danger" onclick="deletePodcast()">Dump</button>
                    <img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                    <button type="button" class="btn btn-success" onclick="isFeature()" >Save</button>
                    <img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                    
                </div>
               
            </div>
        </div>
        
</div>
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>





@stop
