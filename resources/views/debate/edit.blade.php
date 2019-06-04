@extends('layouts/master')

@section('title', 'Edit Debate - BWCMS')

<?php  //print_r($debateDetail); exit;  ?>
@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit Debate</small></h1>

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
                <a href="javascript:;">Edit Debate</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Edit Debate</small></h2>
    </header>
    <!--            <form class="form-horizontal" id="fileupload" action="" method="POST" enctype="multipart/form-data">-->
    {!! Form::open(array('url'=>'debate/update','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}    
     <input type="hidden" name="id" value="{{$debateDetail->id}}">
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
                        @foreach($channels as $channel)
                        <option @if($channel->channel_id==$debateDetail->channel_id) selected="selected" @endif value="{{ $channel->channel_id }}">{{ $channel->channel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <script>
                $().ready(function () {
                    $("#channel").select2();
                    
                    $('#channel').change(function(){
                        //alert(1);
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
        <div class="form-legend" id="qb-feature">Debate Details
        </div>
        <div id="Photo-feature"  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Title (200 Characters)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea  rows="3" name="title" id="title" class="no-resize">{{$debateDetail->title}}</textarea>

                </div>
            </div>
        </div>
        <div id="Text_Area_Resizable" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Description</label>
            </div>
            <div class="span9">
                <div class="controls">


                    <textarea id="debatedesc" name="debatedesc" rows="5" class="auto-resize">{{$debateDetail->description}}</textarea>
                    <script>
//                        elRTE.prototype.options.panels.web2pyPanel = [
//                            'pastetext','bold', 'italic','underline','justifyleft', 'justifyright',
//                           'justifycenter', 'justifyfull','forecolor','hilitecolor','fontsize','link',
//                           'image', 'insertorderedlist', 'insertunorderedlist'];
//                       elRTE.prototype.options.toolbars.web2pyToolbar = ['web2pyPanel','tables'];
//                       
//                        $('#debatedesc').elrte({
//                            lang: "en",
//                            styleWithCSS: false,
//                            height: 200,
//                            toolbar: 'web2pyToolbar'
//                        });
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
                            alert('Please enter tag');
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
                        prePopulate: <?php echo $debatetags ?>
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
                    
                 $('#pageSubmit').click(doCheck);   
                     function doCheck(){  
                     var checkvalid=1;
                     $('.error.noborder').remove();
                     //alert($('#debate_old_featured_image').val().trim());
                         if(($('#is_featured').is(":checked")) && ($('#debate_old_featured_image').val().trim()=='')){
                             if($('#debateimage').val()==''){
                                 checkvalid=0;
                                  $('.dbt_featured_image').append('<div class="error noborder">Please select featured image.</div>');
                             }
                         }
                         
                    
                     $("#fileupload").validate({
                        errorElement: "span",
                            errorClass: "error",
                            //$("#pageSubmit").onclick: true,
                            onclick: true,
                            invalidHandler: function(event, validator) {
                         
                                    for (var i in validator.errorMap) {
                                        
                                            if($('#'+i).hasClass('formattedelement')){
                                                $('#'+i).siblings('.formattedelement').addClass('error');
                                                
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
                                    "title": {
                                    required: true
                                    },
                                    "debatedesc": {
                                    required: true
                                    },
                                    "expertname1": {
                                    required: true
                                    },
                                    "expertdesing1": {
                                    required: true
                                    },
                                    "expertview1": {
                                    required: true
                                    },
                                   "expertimage1": {
                                    required: true,
                                    accept: "image/*",
                                    maxFileSize: {
                                        "unit": "MB",
                                        "size": 2
                                    }
                                    },
                                    "expertname2": {
                                    required: true
                                    },
                                    "expertdesing2": {
                                    required: true
                                    },
                                    "expertview2": {
                                    required: true
                                    },
                                   "expertimage2": {
                                    required: true,
                                    accept: "image/*",
                                    maxFileSize: {
                                        "unit": "MB",
                                        "size": 2
                                    }
                                    }
                                     
                            }
                    });
                     
                    if(!$("#fileupload").valid())
                                checkvalid=0;
                    if(checkvalid==0)
                        return false;
                    } 
                    
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

        <div class="form-legend" id="Input_Field">Expert View 1</div>


        <!--Input Field begin-->
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label"  for="inputField">Expert Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="expertname1" name="expertname1" type="text" value="@if(isset($exprtnts[0])){{$exprtnts[0]->name}}@endif">
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
                    <input id="expertdesing1" name="expertdesing1" type="text" value="@if(isset($exprtnts[0])){{$exprtnts[0]->designation}}@endif">
                </div>
            </div>
        </div>
        <!--Input Field end-->

        <!--File Upload begin-->
        <div id="File_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Expert Photo(Size:{{config('constants.dimension_debate_expert')}}, File Size<={{config('constants.maxfilesize').' '.config('constants.filesizein')}})</label>
            </div>
            <div class="span9">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="input-append">
                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" id="expertimage1" name="expertimage1"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                        @if(isset($exprtnts[0]))<img src="{{config('constants.awsbaseurl').config('constants.debateexpert').$exprtnts[0]->expert_photo}}" width="100" height="100" style="padding-left: 5px;" />@endif
                        <input type="hidden" name="expert_old_image1" value="@if(isset($exprtnts[0])){{$exprtnts[0]->expert_photo}}@endif"/>
                        <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_debate_expert')}}')">&nbsp;Need to crop images? Click here</a>
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
                    <input id="experttwitter1" name="experttwitter1" type="text" value="@if(isset($exprtnts[0])){{$exprtnts[0]->twitter_ac}}@endif">
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
                    <textarea rows="4" name="expertview1" id="expertview1" class="no-resize">@if(isset($exprtnts[0])){{$exprtnts[0]->view}}@endif</textarea>
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
                    <input id="expertname2" name="expertname2" type="text" value="@if(isset($exprtnts[1])){{$exprtnts[1]->name}}@endif">
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
                    <input id="inputField" id="expertdesing2" name="expertdesing2" name="inputField" type="text" value="@if(isset($exprtnts[1])){{$exprtnts[1]->designation}}@endif">
                </div>
            </div>
        </div>
        <!--Input Field end-->

        <!--File Upload begin-->
        <div id="File_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Expert Photo(Size:{{config('constants.dimension_debate_expert')}}, File Size<={{config('constants.maxfilesize').' '.config('constants.filesizein')}})</label>
            </div>
            <div class="span9">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="input-append">
                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" id="expertimage2" name="expertimage2" ></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                        @if(isset($exprtnts[1]))<img src="{{config('constants.awsbaseurl').config('constants.debateexpert').$exprtnts[1]->expert_photo}}" width="100" height="100" style="padding-left: 5px;" />@endif
                        <input type="hidden" name="expert_old_image2" value="@if(isset($exprtnts[1])){{$exprtnts[1]->expert_photo}}@endif"/>
                        <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_debate_expert')}}')">&nbsp;Need to crop images? Click here</a>
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
                    <input id="experttwitter2" name="experttwitter2" type="text" value="@if(isset($exprtnts[1])){{$exprtnts[1]->twitter_ac}}@endif">
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
                    <textarea rows="4" name="expertview2" id="expertview2"  class="no-resize">@if(isset($exprtnts[1])){{$exprtnts[1]->view}}@endif</textarea>
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
                    <input name="videotitle" id="videotitle" type="text" value="@if(isset($debateVideo)){{$debateVideo->title}}@endif">
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Code (500/320)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea rows="4" name="videocode" id="videocode" class="no-resize">@if($debateVideo){{$debateVideo->code}}@endif</textarea>
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Source</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input name="videosource" id="videosource" type="text" value="@if($debateVideo){{$debateVideo->source}}@endif">
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">URL</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input name="videourl" id="videourl" type="text" value="@if($debateVideo){{$debateVideo->url}}@endif">
                </div>
            </div>
        </div>

    </div><!--end container-->

    <div class="container-fluid">
        <div class="control-group row-fluid">
            <div class="span12 span-inset">

                <label class="checkbox" >
                    <input type="checkbox" id="is_featured" class="uniformCheckbox" @if($debateDetail->is_featured=='1' || old('is_featured')) checked="checked" @endif value="checkbox1" name="is_featured">
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
                <label class="control-label">Upload Featured Image(Size:{{config('constants.dimension_debate')}}, File Size<={{config('constants.maxfilesize').' '.config('constants.filesizein')}})</label>
            </div>
            <div class="span9 dbt_featured_image">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="input-append">
                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="debateimage" id="debateimage"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                         @if(isset($debatePhotos))<img src="{{config('constants.awsbaseurl').config('constants.debatefeatured').$debatePhotos->photopath}}" width="100" height="100" style="padding-left: 5px;" />
                         <input type="hidden" value="{{$debatePhotos->photopath}}" name="debate_old_featured_image" id="debate_old_featured_image"/>@endif
                         <a href="javascript:void(0);" style="font-size:12px;" onClick="cropImage('{{url('/photo/crop')}}?dimension={{config('constants.dimension_debate')}}')">&nbsp;Need to crop images? Click here</a>
                    </div>
                </div>
            </div>
        </div>
        <!--File Upload end-->

        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button value="P" name="status" id="pageSubmit" class="btn btn-warning" type="submit">Publish</button>
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

