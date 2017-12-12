@extends('layouts/master')

@section('title', 'edit Podcast - BWCMS')

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
                        <a href="#">Add Album</a>
                    </li>
                </ul>
             </li>       
        </ul>
    </div>            
    <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Album ID: 45345</small></h2>
    </header>
    <form class="form-horizontal" id="fileupload" action="/padcast/update/" method="POST" enctype="multipart/form-data">
    {!! csrf_field() !!} 
    <input type="hidden" name="id" value="{{$postsArr->id}}">
    <input type="hidden" name="album_photo" value="{{$postsArr->album_photo}}">
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
                    <label class="control-label" for="selectBoxFilter">Album Title</label>
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
                           <input type="text" name="album_name" id="album_name" value="{{$postsArr->album_name}}">
                        </div>
                    </div>
                </div>
		<div id="Text_Area_Resizable" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Album Description</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <textarea  rows="4" class="" name ="album_description">{{$postsArr->album_description}}</textarea>
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
                                 @if(isset($postsArr->album_photo))<img src="{{config('constants.awsbaseurl').config('constants.awpodcastimagethumbtdir').$postsArr->album_photo}}" width="100" height="100" style="padding-left: 5px;" />@endif
                        <input type="hidden" name="album_photo" value="@if(isset($postsArr->album_photo)){{$postsArr->album_photo}}@endif"/>
                        <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_debate_expert')}}')">&nbsp;Need to crop images? Click here</a>
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
                        prePopulate: <?php echo $tags ?>
                    });
                });</script>
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
       
        
</div>

@stop
