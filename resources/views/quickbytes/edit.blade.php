@extends('layouts/master')

@section('title', 'Edit Quickbyte - BWCMS')


@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit QuickByte</small></h1>

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
                                    "title": "Author",
                                    "attr": {"href": "#Author"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Quick Byte Feature",
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
                            "data" : {
                            "title" : "Assign This QB To A Campaign",
                                    "attr" : { "href" : "#assign-article-to-a-campaign" }
                            }
                            },
                            {
                                "data": {
                                    "title": "Topics",
                                    "attr": {"href": "#topics"}
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
                <a href="#">QuickByte &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                <ul class="breadcrumb-sub-nav">
                    <li>
                        <a href="create-new-quickbyte.html">Create New QuickByte</a>
                    </li>
                    <li>
                        <a href="published-quickbyte.html">Published QuickByte</a>
                    </li>
                    <li>
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
                    </li>
                </ul>
            </li>
            <li class="current">
                <a href="javascript:;">Edit QuickByte</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Edit QuickByte</small></h2>
    </header>
    <!--            <form class="form-horizontal" id="fileupload" action="" method="POST" enctype="multipart/form-data">-->
    {!! Form::open(array('url'=>'quickbyte/update','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}    
    <input type="hidden" name="id" value="{{$quickbyte->id}}">
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
                        <option @if($channel->channel_id==$quickbyte->channel_id) selected @endif; value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                $().ready(function () {
                    $("#channel").select2();
                        
                     $('#channel').change(function(){
                         
                                  $.get("{{ url('article/campaign')}}",
                        { option: $(this).attr("value") },
                                function(data) {
                                var Box = $('#campaign_id');
                                        Box.empty();
                                        Box.append("<option selected='' value=''>Please Select</option>");
                                        $.each(data, function(index, element) {
                                        Box.append("<option value='" + element + "'>" + index + "</option>");
                                        });
                                      $("#campaign_id").select2();
                                });
                                
                                $.get("{{ url('article/dropdown1')}}",
                                { option: $(this).attr("value") + '&level=' },
                                        function(data) {
                                        var Box = $('#selectBoxFilter2');
                                                Box.empty();
                                                Box.append("<option value=''>Please Select</option>");
                                                $.each(data, function(index, element) {
                                                Box.append("<option value='" + element + "'>" + index + "</option>");
                                                });
                                                $("#selectBoxFilter2").select2();
                                                $('#selectBoxFilter3').html("<option value=''>Please Select</option>");
                                                $("#selectBoxFilter3").select2();
                                                $('#selectBoxFilter4').html("<option value=''>Please Select</option>");
                                                $('#selectBoxFilter4').select2();
                                                $('#selectBoxFilter5').html("<option value=''>Please Select</option>");
                                                $('#selectBoxFilter5').select2();
                                        });
                        });
                    
                    
                });
            </script>
        </div>

        <!--Select Box with Filter Search end-->					
    </div>

    <div class="container-fluid">

        <div class="form-legend" id="Author">Author</div>

        <!--Select Box with Filter Search begin-->
        <div  class="control-group row-fluid" >
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Author</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="author_type" id="author_type" class="formattedelement">
                        <option value="">Please Select</option>
                        @foreach($p1 as $key=>$val)
                        <option @if($key==$quickbyte->author_type) selected @endif value="{{ $key}}">{{ $val }}</option>
                        @endforeach
                        <!--                                        <option value="online">BW Online Bureaw</option>
                                                                <option value="BW-Reporter">BW Reporters</option>
                                                                <option value="GA">Guest Author</option>
                                                                <option value="CL">Cloumnist</option>-->
                    </select>
                </div>
            </div>
            <script>
                $().ready(function () {
                    $("#author_type").select2();
                });
            </script>

            <script type="text/javascript">

                $(document).ready(function () {
                    var auid =<?php echo $quickbyte->author_id; ?>;
                    var selecteds = '';
                    $("#ch-reporter").hide();

                    $("#author_type").change(function () {

                        $(this).find("option:selected").each(function () {

                            if ($(this).attr("value") != "1" && $(this).attr("value") != "") {
                                $("#ch-reporter").show();
                                $.get("{{ url('/article/authordd/')}}",
                                        {option: $(this).attr("value")},
                                function (data) {
                                    var simpleSelectBox1 = $('#author_name');
                                    simpleSelectBox1.empty();
                                    simpleSelectBox1.append("<option value=''>Select</option>");
                                    $.each(data, function (index, element) {
                                        if (element == auid)
                                                selecteds = 'selected';
                                                else
                                                selecteds = '';
                                        simpleSelectBox1.append("<option " + selecteds + " value='" + element + "'>" + index + "</option>");
                                    });
                                    $("#author_name").select2();
                                });
                            }
                            else {
                                $("#ch-reporter").hide();
                            }

                        });

                    }).change();

                });

            </script>

        </div>

        <!--Select Box with Filter Search end-->					

        <div  class="control-group row-fluid" id="ch-reporter">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Choose Reporter</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="author_name" id="author_name">

                    </select>
                </div>
            </div>
            <script>
                $().ready(function () {
                    $("#author_name").select2();
                });
            </script>                            
        </div>

    </div>


    <div class="container-fluid">  
        <div class="form-legend" id="qb-feature">Quick Byte Feature 
        </div>
        <div id="Photo-feature"  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Quickbyte Title </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="title" value="{{$quickbyte->title}}" id="title">
                </div>
            </div>
        </div>
        <div id="Text_Area_Resizable" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Quickbyte Discription</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea  rows="4" class="" id="featuredesc" name="featuredesc">{{$quickbyte->description}}</textarea>
                </div>
            </div>
        </div>
        <div id="Drag_And_Drop_Upload" class="control-group row-fluid">
            <div class="span12">
                <table class="table table-striped table-responsive" id="tableSortableResMed">
                    <thead class="cf sorthead">
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Desc</th>
                            <th>Photo By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($photos as $photo)
                        <tr id="row_{{$photo->photo_id}}">
                            <td width="20%">
                                <img src="{{ config('constants.awsbaseurl').config('constants.awquickbytesimagethumbtdir').$photo->photopath}}" alt="quick byte" />
                            </td>
                            <td width="20%">{{$photo->title}}</td>
                            <td width="30%" class="tdimagedesc">{{$photo->description}}</td>
                            <td class="center" width="15%">{{$photo->photo_by}}</td>
<!--                                            <td>{{ $photo->title }}</td>-->
                    <input type="hidden" name="deleteImagel" id="{{ $photo->photo_id }}">
<!--                                    <td class="center">{{ $photo->source }}</td>
                    <td class="center">{{ $photo->source_url }}</td>-->
                    <td class="center" width="15%">
                        <button type="button" onclick="$(this).MessageBox({{ $photo->photo_id }})" name="{{ $photo->photo_id }}" id="deleteImage" class="btn btn-mini btn-danger">Dump</button>
                        <button type="button" onclick="editImageDetail({{ $photo->photo_id }},'quickbyte')" name="image{{ $photo->photo_id }}" id="deleteImage" class="btn btn-mini btn-edit">Edit</button>

                        <img  src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:block; margin-left:15px;display:none;"/></td>
                    
                    </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>
        <!--Drag And Drop Upload begin-->
        <div id="Drag_And_Drop_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">
                    Upload Photos<a href="javascript:;" class="bootstrap-tooltip" data-placement="top" data-original-title="Here You can add multiple photos by Drag and Drop or Simply By clicking and selecting  photos (Size: {{config('constants.dimension_qb')}}) (File Size <= {{config('constants.maxfilesize').' '.config('constants.filesizein')}}  )."><i class="icon-photon info-circle"></i></a>
                </label>
            </div>
            <div class="span9 row-fluid" >
                <div class=" fileupload-buttonbar">
                    <div class="col-lg-7">
                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="btn btn-success fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span>Add files...</span>
                            <input type="file" name="files[]" multiple>
                        </span>
                        <button type="submit" class="btn btn-primary start">
                            <i class="glyphicon glyphicon-upload"></i>
                            <span>Start upload</span>
                        </button>
                        <button type="reset" class="btn btn-warning cancel">
                            <i class="glyphicon glyphicon-ban-circle"></i>
                            <span>Cancel upload</span>
                        </button>
                        <button type="button" class="btn btn-danger delete">
                            <i class="glyphicon glyphicon-trash"></i>
                            <span>Delete</span>
                        </button>
                        <input type="checkbox" class="toggle">
                        <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_qb')}}')">Need to crop images? Click here</a>
                        <!-- The global file processing state -->
                        <span class="fileupload-process"></span>
                    </div>
                    <!-- The global progress state -->
                    <div class="col-lg-5 fileupload-progress fade">
                        <!-- The global progress bar -->
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                        </div>
                        <!-- The extended global progress state -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>
                <!-- The table listing the files available for upload/download -->
                <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
            </div>

        </div>
        <!--Drag And Drop Upload end-->

        <!--                        
                                <div class="control-group row-fluid">
                                        <div class="span3" >
                                            <a class="control-label text-info" href="edit-article-panel-page2.html" >Add Individual Photo Description</a>
                                        </div>
                                        
                                        <div class="span9 span-inset">
                                            <button class="btn btn-info" id="add_des" type="button">Add </button>
                                         </div>
                                 </div>-->

        <!-- descruiption divs can be multiple so place them here-->
        <div id="des_area">
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Description 1</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <textarea  rows="2" class=""></textarea>
                    </div>
                </div>
            </div>

            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Description2</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <textarea  rows="2" class=""></textarea>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function (e) {
                $("#des_area").hide();
                $("#add_des").click(function (e) {
                    $("#des_area").show();
                });
            });
        </script>

    </div>

    <!--                  <div class="container-fluid">
    
                            <div class="form-legend" id="tags">Tags</div>
                             Select Box with Filter Search begin
                          
                            <div class="control-group row-fluid" id="Multiple_Select_Box_with_Filter_Search">
                                <div class="span3">
                                    <label for="multiFilter" class="control-label">Tags</label>
                                </div>
                                <div class="span9">
                                    <div class="controls">
                                      <select multiple name="multiFilter" id="multiFilter">
                                            <option value="Beige">Beige</option>
                                            <option value="Black">Black</option>
                                            <option value="Blue">Blue</option>
                                            <option value="Bronze">Bronze</option>
                                            <option value="Brown">Brown</option>
                                            <option value="Gold">Gold</option>
                                            <option value="Gray">Gray</option>
                                            <option value="Green">Green</option>
                                            <option value="Orange">Orange</option>
                                            <option value="Pink">Pink</option>
                                            <option value="Purple">Purple</option>
                                            <option value="Red">Red</option>
                                            <option value="Silver">Silver</option>
                                            <option selected="" value="Turquoise">Turquoise</option>
                                            <option value="White">White</option>
                                            <option value="Yellow">Yellow</option>
                                        </select>
                                    </div>
                                </div>
                                <script>
                                    $().ready(function(){
                                        $("#multiFilter").select2();
                                    });
                                </script>
                                                            
                                             </div>                       
                            Select Box with Filter Search end
                    </div>-->

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
                        prePopulate: <?php echo $tags ?>
                    });
                });</script>
        </div>                       
        <!--Select Box with Filter Search end-->
    </div>


    <div class="container-fluid">

        <div class="form-legend" id="assign-article-to-a-campaign">Assign this QuickByte to a Campaign</div>

        <!--Select Box with Filter Search begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="campaign">Campaign</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="campaign"  id="campaign_id">
                         <option value="">Please Select</option>
                     
                        @foreach($campaign as $campaigns)
                        @if($quickbyte->campaign_id == $campaigns->campaign_id)
                        <option selected value="{{ $campaigns->campaign_id }}">{{ $campaigns->title }}</option>
                        @else
                        <option value="{{ $campaigns->campaign_id }}">{{ $campaigns->title }}</option>
                        @endif
                        @endforeach
                       
                    </select>
                    <span for="campaign" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                    <!--<div class="control-group row-fluid">
                                    <div class="span12 span-inset">
                                    <button class="btn btn-warning" type="button" style="display:block; float:left;">Delete</button>
                                     <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>
                                      <button type="button" class="btn btn-primary" style="display:block; float:left; margin-left:5px;">Attach</button>
                                      <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>
                                    </div>
                            </div>-->
                </div>
            </div>

            <script>
                        $().ready(function(){
                $("#campaign_id").select2();
                });</script>
        </div>
        <!--Select Box with Filter Search end-->

    </div>

    <div class="container-fluid">

        <div class="form-legend" id="topics-location">Topics And Location</div>
        <!--Topics begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="Ltopics" style="width:100%;">Topics</label>
                <button type="button" name="genTopic" id="genTopic" class="btn btn-mini btn-inverse" style="margin-left:15px; display:block;">Generate Topics</button>
                <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:none; margin-left:15px;"/>
            </div>
            <div class="span9">
                <div class="controls ltopicsparentdiv">
                    <select multiple name="Ltopics[]" id="Ltopics">
                        @foreach($arrTopics as $topic)
                        <option value="{{$topic->id}}" selected >{{$topic->topic}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                $().ready(function () {
                    $("#Ltopics").pickList();
                });
                $("#genTopic").click(function () {
                    var token = $('input[name=_token]');
                    //alert($('#maxi').elrte('val'));
                    // process the form
                    var tdata = $('#featuredesc').val();
                    $('body').find('.table.table-striped textarea').each(function () {
                        // alert($(this).val());
                        tdata += ' ' + $(this).val();
                    });

                    $('body').find('.tdimagedesc').each(function () {
                        tdata += ' ' + $(this).html();
                    });
                    $.ajax({
                        type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                        url: '/article/generateTopics', // the url where we want to POST
                        data: {detail: tdata},
                        dataType: 'json', // what type of data do we expect back from the server
                        encode: true,
                        beforeSend: function (data) {
                            $('#genTopic').hide();
                            $('#genTopic').siblings('img').show();
                        },
                        complete: function (data) {
                            $('#genTopic').show();
                            $('#genTopic').siblings('img').hide();
                        },
                        success: function (data) {

                            var resplen = (data).length;
                            //alert(resplen);
                            var selectedarray = new Array();
                            $('.ltopicsparentdiv').find("#Ltopics option:selected").each(function () {
                                selectedarray.push($(this).val());
                            });
                            var dataoption = '<select multiple name="Ltopics[]" id="Ltopics">';
                            var selectedop = '';
                            $.each(data, function (index, element) {
                                selectedop = '';
                                if (selectedarray.indexOf(element.id) >= 0)
                                    selectedop = 'selected';
                                dataoption += "<option " + selectedop + " value='" + element.id + "'>" + element.topic + "</option>";

                            });
                            dataoption += '</select>';
                            $(".ltopicsparentdiv").html(dataoption);
                            $("#Ltopics").pickList();
                        },
                        headers: {
                            'X-CSRF-TOKEN': token.val()
                        }
                    })
                            // using the done promise callback
                            .done(function (data) {
                                // log data to the console so we can see
                                //console.log(data);
                                //alert(data);
                                //alert('Topic Populated');
                                // here we will handle errors and validation messages
                            });
                    // stop the form from submitting the normal way and refreshing the page
                    //event.preventDefault();
                });</script>
            <div class="span12 span-inset">
                <img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;">
            </div>
        </div>
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
                    <select name="category1" id="selectBoxFilter2" class="formattedelement">
                        @if(count($acateg)>0)
                        <option selected="" value="{{ $acateg[0]['category_id'] }}">{{$acateg[0]['name']}}</option>
                        @else
                        <option selected="selected" value="">Please Select</option>
                        @endif
                        @foreach($category as $key )
                        <option value="{{ $key->category_id }}">{{ $key->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $("#selectBoxFilter2").select2();
                    $('#selectBoxFilter2').change(function () {
                        $.get("{{ url('article/dropdown1')}}",
                                {option: $(this).attr("value") + '&level=_two'},
                        function (data) {
                            var selectBoxFilter3 = $('#selectBoxFilter3');
                            selectBoxFilter3.empty();
                            selectBoxFilter3.append("<option selected='' value=''>Please Select</option>");
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
                        @if(count($acateg)>1)
                        <option selected="" value="{{ $acateg[1]['category_id'] }}">{{$acateg[1]['name']}}</option>
                        @endif
                        <option value="">Please Select</option>
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
                        @if(count($acateg)>2)
                        <option selected="" value="{{ $acateg[2]['category_id'] }}">{{ $acateg[2]['name'] }}</option>
                        @endif
                        <option value="">Please Select</option>
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
                            selectBoxFilter5.append("<option selected='' value=''>Please Select</option>");
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
                        @if(count($acateg)>3)
                        <option selected="" value="{{ $acateg[3]['category_id'] }}">{{$acateg[3]['name']}}</option>
                        @endif
                        <option value="">Please Select</option>
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
        <div class="control-group row-fluid">
            <div class="span12 span-inset">

                <label class="checkbox" >
                    <input type="checkbox" class="uniformCheckbox" value="checkbox1" @if($quickbyte->sponsored==1) checked @endif; name="is_sponsored">
                           <a href="#" target="_blank">This Is  Sponsored</a>
                </label>
                <script>
                    $().ready(function () {
                        $(".uniformCheckbox").uniform();
                    });
                </script>

            </div>
        </div>

        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button type="submit" class="btn btn-warning" name="status" value="P">Publish</button>	
                <button class="btn btn-danger" id="dumpSubmit" value="D" name="status" type="submit">Dump</button>
                <!--                                
                                               <button type="button" class="btn btn-success">Publish</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                <button type="button" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>-->

            </div>
        </div>
    </div>
    <!--	end container-->

    <!--            </form>-->
    <input type="hidden" id="uploadedImages" name="uploadedImages">

    {!! Form::close() !!}
</div>

<!-- The template to display files available for upload -->
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
    <td colspan="4">            
    <table width="100%">
    <tr>

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


    <tr>
    <td colspan="1">Title</td>
    <td colspan="3"><input type="text" name="imagetitle[{%=file.name%}]"/></td>    
    </tr>

    <tr>
    <td colspan="1">Description</td>
    <td colspan="3"><textarea name="imagedesc[{%=file.name%}]"></textarea></td>    
    </tr>
    <tr>
            <td colspan="1">Photograph By</td>
            <td colspan="3"><input type="text" name="photographby[{%=file.name%}]"/></textarea></td>    
   </tr>

    </table>   
    </td>           

    </tr>







    {% } %}
</script>
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<!--<script src="http:js/vendor/jquery.ui.widget.js"></script>-->
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<!-- <script type="text/javascript" src="{{ elixir('output/fileuploadJS.js') }}"></script> -->
<script type="text/javascript" src="{{ asset('js/jquery.iframe-transport.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-process.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-image.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-audio.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-video.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-validate.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.fileupload-ui.js') }}"></script>
<script>
                    $(document).ready(function () {
                        $('#fileupload').fileupload({
                            // Uncomment the following to send cross-domain cookies:
                            //xhrFields: {withCredentials: true},
                            url: '<?php echo url('quickbyte/image/upload') ?>',
                            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                            maxFileSize: 10000000
                        });
                    });



                    $('#fileupload').bind('fileuploaddone', function (e, data) {
                        //console.log(e);
                        var dataa = JSON.parse(data.jqXHR.responseText);
                        //console.log(dataa['files']['0']['name']);
                        $.each(dataa['files'], function (index, element) {
                            //console.log(element.name);
                            if ($('#uploadedImages').val().trim())        // validation ends here           
                                $('#uploadedImages').val($('#uploadedImages').val() + ',' + element.name);
                            else
                                $('#uploadedImages').val(element.name);
                        });

                    });
                    $('#fileupload').bind('fileuploaddestroyed', function (e, data) {
                        // console.log(data);
                        var file = getArg(data.url, 'file');
                        var images = $('#uploadedImages').val().split(',');
                        images.splice(images.indexOf(file), 1);
                        $('#uploadedImages').val(images.join());
                        //$('#imagesname').val($('#imagesname').val().replace(','+));

                    });


                    function getArg(url, name) {
                        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
                        if (results == null) {
                            return null;
                        }
                        else {
                            return results[1] || 0;
                        }
                    }



// Validation start hee

                    $("#fileupload").validate({
                        errorElement: "span",
                        errorClass: "error",
                        //$("#pageSubmit").onclick: true,
                        onclick: true,
                        invalidHandler: function (event, validator) {

                            for (var i in validator.errorMap) {

                                if ($('#' + i).hasClass('formattedelement')) {
                                    $('#' + i).siblings('.formattedelement').addClass('error');

                                }

                            }
                        },
                        rules: {
                            "req": {
                                required: true
                            },
                            "channel": {
                                required: true
                            },
                            "author_type": {
                                required: true,
                            },
                            "title": {
                                required: true
                            },
                            "featuredesc": {
                                required: true
                            }
                        }
                    });

                    $('select.formattedelement').change(function () {
                        if ($(this).val().trim() != '')
                            $(this).siblings('.formattedelement').removeClass('error');
                        $(this).siblings('span.error').remove();
                    });

                    // Validation ends here    

// Delete photo 

                    $.fn.MessageBox = function (msg)
                    {
                        var formData = new FormData();
                        formData.append('photoId', msg);
                        var token = $('input[name=_token]');
                        var rowID = 'row_' + msg;
                        var div = document.getElementById(rowID);
                        div.style.visibility = "hidden";
                        div.style.display = "none";
                        // process the form
                        $.ajax({
                            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                            url: '/article/delPhotos', // the url where we want to POST
                            data: formData,
                            dataType: 'json', // what type of data do we expect back from the server
                            contentType: false,
                            processData: false,
                            headers: {
                                'X-CSRF-TOKEN': token.val()
                            }
                        })
                                // using the done promise callback
                                .done(function (data) {

                                    // log data to the console so we can see
                                    console.log(data);
                                    //alert('Author Saved');
                                    // here we will handle errors and validation messages
                                });
                    };

</script>
<script>
//alert(1);
  var token = $('input[name=_token]');
  $("#tableSortableResMed tbody").sortable({
      appendTo: "parent",
      helper: "clone",
      update: function (event, ui) {
      
        var data = $(this).sortable('serialize');
        //alert(data);    
        // POST to server using $.post or $.ajax
                $.ajax({
                    data: data,
                    type: 'POST',
                    url: '{{ url("/quickbyte/sort/".$quickbyte->id)}}',
                    headers: {
                                'X-CSRF-TOKEN': token.val()
                             }
                });
        
    }
  }).disableSelection();

</script>
@stop


