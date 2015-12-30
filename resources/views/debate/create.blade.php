@extends('layouts/master')

@section('title', 'Create New Debate - BWCMS')


@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Create New Debate</small></h1>

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
                                    "attr": {"href": "#Channel"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Debate Feature",
                                    "attr": {"href": "#qb-feature"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Tags",
                                    "attr": {"href": "#tags"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Categories",
                                    "attr": {"href": "#categories"}
                                }
                            }
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
                <a href="/dashboard">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li>
                <a href="#">Debate &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                <ul class="breadcrumb-sub-nav">
                    <li>
                        <a href="debate/create">Create New Debate</a>
                    </li>
                    <li>
                        <a href="debate/published">Published Debates</a>
                    </li>
                    <!--                    <li>
                                            <a href="deleted-quickbyte.html">Deleted QuickByte</a>
                                        </li>
                                        <li>
                                            <a href="#">QuickByte for Footer</a>
                                        </li>
                                        <li>
                                            <a href="#">Reports</a>
                                        </li>
                                        <li>
                                            <a href="#">Help</a>
                                        </li>-->
                </ul>
            </li>
            <li class="current">
                <a href="javascript:;">Create New Debate</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>New Debate</small></h2>
    </header>
    <!--            <form class="form-horizontal" id="fileupload" action="" method="POST" enctype="multipart/form-data">-->
    {!! Form::open(array('url'=>'debate/','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}    

    <div class="container-fluid" @if(count($errors->all())==0) style="display:none" @endif >

         <div class="form-legend" id="Notifications" >Notifications</div>

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

        <div class="form-legend" id="Channel">Channel</div>

        <!--Select Box with Filter Search begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Channel</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="channel"  id="channel" class="formattedelement">
                        <option selected="" value="">Please Select-</option>
                        @foreach($channels as $channel)
                        <option value="{{ $channel->channel_id }}" @if(old('channel')==$channel->channel_id) selected="selected" @endif>{{ $channel->channel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                $().ready(function () {
                    $("#channel").select2();
                });
            </script>
        </div>

        <!--Select Box with Filter Search end-->					
    </div>




    <div class="container-fluid">  
        <div class="form-legend" id="qb-feature">Debate Details
        </div>
        <div id="Photo-feature"  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Title (200 Characters)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea  rows="4" name="title" id="title" class="no-resize">{{old('title')}}</textarea>

                </div>
            </div>
        </div>
        <div id="Text_Area_Resizable" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Description</label>
            </div>
            <div class="span9">
                <div class="controls">


                    <textarea id="debatedesc" name="debatedesc" rows="2" class="auto-resize">{{old('debatedesc')}}</textarea>
                    <script>
                        elRTE.prototype.options.panels.web2pyPanel = [
                            'pastetext','bold', 'italic','underline','justifyleft', 'justifyright',
                           'justifycenter', 'justifyfull','forecolor','hilitecolor','fontsize','link',
                           'image', 'insertorderedlist', 'insertunorderedlist'];
                       elRTE.prototype.options.toolbars.web2pyToolbar = ['web2pyPanel','tables'];
                       
                        $('#debatedesc').elrte({
                            lang: "en",
                            styleWithCSS: false,
                            height: 200,
                            toolbar: 'web2pyToolbar'
                        });
                    </script>
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
                        <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:50%; display:block; margin-left:15px;display:none;"></div>
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
//                                        alert('Tag Saved');
//                                        $("#Taglist").tokenInput("add", [{"id":"2","name":"Coal Scam"},{"id":"4","name":"Cuisine"},{"id":"7","name":"Education"},{"id":"15","name":"Election"},{"id":"208","name":"testtag1"},{"id":"1","name":"Modi"},{"id":"207","name":"tagtest"},{"id":"210","name":"ankita"}]);
//                                         //$("#Taglist").tokenInput("add", {id: 9992, name: "test22"});
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
                });</script>
        </div>                       
        <!--Select Box with Filter Search end-->
    </div>

    <div class="container-fluid">

        <div class="form-legend" id="categories">Categories</div>

        <!--Select Box with Filter Search begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Categories</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="category1" id="category1" class="formattedelement">
                        <option  value="">Please Select</option>
                        @foreach($category as $key )
                        <option value="{{ $key->category_id }}">{{ $key->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $("#category1").select2();
                    $('#category1').change(function () {
                        $.get("{{ url('article/dropdown1')}}",
                                {option: $(this).attr("value") + '&level=_two'},
                        function (data) {
                            var selectBoxFilter3 = $('#selectBoxFilter3');
                            selectBoxFilter3.empty();
                            selectBoxFilter3.append("<option value=''>Please Select</option>");
                            $.each(data, function (index, element) {
                                selectBoxFilter3.append("<option value='" + element + "'>" + index + "</option>");
                            });
                            $("#selectBoxFilter3").select2();
                            $('#selectBoxFilter4').html("<option value=''>Please Select</option>");
                            $('#selectBoxFilter4').select2();
                            $('#selectBoxFilter5').html("<option value=''>Please Select</option>");
                            $('#selectBoxFilter5').select2();

                        });
                    });
                });</script>
        </div>
        <div id="categories" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter"></label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="category2" id="selectBoxFilter3">
                        <option  value="">Please Select</option>

                    </select>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $("#selectBoxFilter3").select2();
                    $('#selectBoxFilter3').change(function () {
                        $.get("{{ url('article/dropdown1')}}",
                                {option: $(this).attr("value") + '&level=_three'},
                        function (data) {
                            var selectBoxFilter4 = $('#selectBoxFilter4');
                            selectBoxFilter4.empty();
                            selectBoxFilter4.append("<option selected='' value=''>Please Select</option>");
                            $.each(data, function (index, element) {
                                selectBoxFilter4.append("<option value='" + element + "'>" + index + "</option>");
                            });
                            $('#selectBoxFilter4').select2();
                            $('#selectBoxFilter5').html("<option value=''>Please Select</option>");
                            $('#selectBoxFilter5').select2();
                        });
                    });
                });</script>
        </div>
        <div id="categories" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter"></label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="category3" id="selectBoxFilter4">
                        <option  value="">Please Select</option>
                    </select>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $("#selectBoxFilter4").select2();
                    $('#selectBoxFilter4').change(function () {
                        $.get("{{ url('article/dropdown1')}}",
                                {option: $(this).attr("value") + '&level=_four'},
                        function (data) {
                            var selectBoxFilter5 = $('#selectBoxFilter5');
                            selectBoxFilter5.empty();
                            selectBoxFilter5.append("<option value=''>Please Select</option>");
                            $.each(data, function (index, element) {
                                selectBoxFilter5.append("<option value='" + element + "'>" + index + "</option>");
                            });
                            $('#selectBoxFilter5').select2();
                        });
                    });
                });</script>
        </div>
        <div id="categories" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter"></label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="category4" id="selectBoxFilter5">
                        <option  value="">Please Select</option>
                    </select>
                </div>
            </div>
            <script>
                $().ready(function () {
                    $("#selectBoxFilter5").select2();
                });</script>
        </div>
        <!--Select Box with Filter Search end-->

    </div> 

    <div class="container-fluid">

        <div class="form-legend" id="Input_Field">Expert View 1</div>


        <!--Input Field begin-->
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label"  for="inputField">Expert Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="expertname1" name="expertname1" type="text">
                </div>
            </div>
        </div>
        <!--Input Field end-->

        <!--Input Field begin-->
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Designation</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="expertdesing1" name="expertdesing1" type="text">
                </div>
            </div>
        </div>
        <!--Input Field end-->

        <!--File Upload begin-->
        <div id="File_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Expert Photo</label>
            </div>
            <div class="span9">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="input-append">
                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" id="expertimage1" name="expertimage1"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                    </div>
                </div>
            </div>
        </div>
        <!--File Upload end-->

        <!--Input Field begin-->
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Twitter A/C</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="experttwitter1" name="experttwitter1" type="text">
                </div>
            </div>
        </div>
        <!--Input Field end-->

        <div id="Text_Area_-_No_Resize" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">View</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea rows="4" name="expertview1" id="expertview1" class="no-resize"></textarea>
                </div>
            </div>
        </div>

    </div>

    <div class="container-fluid">

        <div class="form-legend" id="Input_Field">Expert View 2</div>



        <!--Input Field begin-->
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Expert Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="expertname2" name="expertname2" type="text">
                </div>
            </div>
        </div>
        <!--Input Field end-->

        <!--Input Field begin-->
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Designation</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="inputField" id="expertdesing2" name="expertdesing2" name="inputField" type="text">
                </div>
            </div>
        </div>
        <!--Input Field end-->

        <!--File Upload begin-->
        <div id="File_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Expert Photo</label>
            </div>
            <div class="span9">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="input-append">
                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" id="expertimage2" name="expertimage2" ></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                    </div>
                </div>
            </div>
        </div>
        <!--File Upload end-->

        <!--Input Field begin-->
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Twitter A/C</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="experttwitter2" name="experttwitter2" type="text">
                </div>
            </div>
        </div>
        <!--Input Field end-->

        <div id="Text_Area_-_No_Resize" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">View</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea rows="4" name="expertview2" id="expertview2"  class="no-resize"></textarea>
                </div>
            </div>
        </div>

    </div>

    <div class="container-fluid">

        <div id="video" class="form-legend" id="Tabs">Add A Video</div>


        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Title</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input name="videotitle" id="videotitle" type="text">
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Code (500/320)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea rows="4" name="videocode" id="videocode" class="no-resize"></textarea>
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Source</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input name="videosource" id="videosource" type="text">
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">URL</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input name="videourl" id="videourl" type="text">
                </div>
            </div>
        </div>

    </div><!--end container-->

    <div class="container-fluid">
        <div class="control-group row-fluid">
            <div class="span12 span-inset">

                <label class="checkbox" >
                    <input type="checkbox" @if(old('is_featured')) checked="checked" @endif class="uniformCheckbox" value="1" name="is_featured">
                    <a href="#" target="_blank"> Make this a featured Debate</a>
                </label>
                <script>
                    $().ready(function () {
                        $(".uniformCheckbox").uniform();
                    });
                </script>

            </div>
        </div>
        <!--File Upload begin-->
        <div id="File_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Upload Featured Image</label>
            </div>
            <div class="span9">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="input-append">
                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="debateimage" id="debateimage"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                    </div>
                </div>
            </div>
        </div>
        <!--File Upload end-->

        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button value="P" name="status" class="btn btn-warning" type="submit">Publish</button>
                <img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>	
                <!--                                
                                               <button type="button" class="btn btn-success">Publish</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                <button type="button" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>-->

            </div>
        </div>

    </div>
    {!! Form::close() !!}
</div>
@stop

