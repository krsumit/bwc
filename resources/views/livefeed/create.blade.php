@extends('layouts/master')
@section('title', 'Create Live Feed - BWCMS')
@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Create Feed</small></h1>
        </div>
        <script>
            $(document).ready(function () {
                $('#pageSubmit').click(doClick);
                function doClick() {
                     var checkvalid = 1;
                    $('#maxi').parent('div').removeClass('error');
                    var as = $('#maxi').val();
                    $('.error.elrte-error').remove();
                    $('.error.noborder').remove();
                    $('#maxi').parent('div').removeClass('error');
                    if (as.length == 0) {
                        $('#maxi').parent('div').addClass('error');
                        $('.elrte-wrapper').after('<span class="error elrte-error" style="display:block;margin-top:10px;" >Feed description is required. </span>');
                        checkvalid = 0;
                    }

                    $("#fileupload").validate({
                        errorElement: "span",
                        errorClass: "error",
                        onclick: true,
                        invalidHandler: function (event, validator) {
                            for (var i in validator.errorMap) { ///alert(i);

                                if ($('#' + i).hasClass('formattedelement')) {
                                    $('#' + i).siblings('.formattedelement').addClass('error');
                                }

                            }
                        },
                        rules: {
                            "req": {
                                required: true
                            }, "title": {
                                rangelength: [10, 250]
                            },
                            "description": {
                                required: true
                            },
                            "article_title": {
                                required: true,
                                rangelength: [10, 250]
                            },
                            "article_summary": {
                                required: true,
                                rangelength: [50, 800]
                            }
                        }
                    });
                    
                    if (!$("#fileupload").valid())
                            checkvalid = 0;
                    if (checkvalid == 0) {
                        $('#submitsection').prepend('<div class="error noborder">An error has occured. Please check the above form.</div>');
                        return false;
                    } else {
                        $('#submitsection').hide();
                    }
                }

            });
        </script>

        <script type="text/javascript">
            $(function () {
                $("#jstree").jstree({
                    "json_data": {
                        "data": [
                            {
                                "data": {
                                    "title": "Create Feed",
                                    "attr": {"href": "#feed-detail"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Feeds",
                                    "attr": {"href": "#feed-list"}
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
            });</script>
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
                <a href="/dashboard">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li>
                <a href="#">Live Feed</a>

            </li>
            <li class="current">
                <a href="javascript:;">Add Feed</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Add Live Feed</small></h2>
    </header>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
    <a href="/article/{{$article->article_id}}" >
        <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Edit Linked Article</button>
    </a>
    </div>
    {!! Form::open(array('url'=>'livefeed/','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
    <input type="hidden" name="article_id" id="artile_id" value="{{$article->article_id}}"/>
   
    <div class="container-fluid" id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')) && (!$errors->any()))style="display: none" @endif >

         <div class="form-legend" id="Notifications">Notifications</div>
         @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                </div>
        @endif

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
        <div class="form-legend" id="feed-detail">Article Details</div>
        
         <!--Text Area - No Resize begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Title (200 Characters)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea  id="article_title" name="article_title" rows="2" class="no-resize">{{$article->title}}</textarea>
                </div>
            </div>
        </div>
        <!--Text Area - No Resize end-->

        <!--Text Area Resizable begin-->
        <div id="Text_Area_Resizable" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Summary (800 Characters)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea  id="article_summary" name="article_summary" rows="4" class="">{{$article->summary}}</textarea>
                </div>
            </div>
        </div>
        <!--Text Area Resizable end-->
        
    </div>

    <div class="container-fluid">
        <div class="form-legend" id="feed-detail">Live-Feed Details

        </div>
        <!--Text Area - No Resize begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Title (200 Characters)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea  id="title" name="title" rows="2" class="no-resize  title_range valid"></textarea>
                    <span for="title" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                </div>
            </div>
        </div>
        <!--Text Area - No Resize end-->

        <!--WYSIWYG Editor - Full Options-->
        <div id="WYSIWYG_Editor_-_Full_Options" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="description">Description</label>
            </div>
            <div class="span9">
                <div class="controls elrte-wrapper">
                    <textarea name="description" id="maxi" rows="2" class="auto-resize required formattedtextareat"></textarea>
                    <span for="description" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                    <script>

                        $(function () {

                            $('#maxi').froalaEditor({
                                height: 250,
                                htmlRemoveTags: [],
                                pastePlain: true,
                                imageUploadURL: '/photo/editor/store',
                                imageUploadParams: {
                                    _token: $('input[name="_token"]').val()
                                },
                                imageMaxSize: 1024 * 1024 * 1 / 2
                            });
                            $('#maxi').on('froalaEditor.image.error', function (e, editor, error, response) {
                                alert(error.message);
                            });
                        });

                        $(document).ready(function () {

                            $("#canonical").addClass("none");
                            $(':radio[id=ifno]').change(function () {
                                $("#canonical").addClass("none");
                            });
                            $(':radio[id=ifyes]').change(function () {
                                $("#canonical").removeClass("none");
                            });
                        });
                    </script>
                </div>
            </div>
        </div>

        <!--WYSIWYG Editor - Full Options end-->

    </div><!-- end container1 -->


    <div class="container-fluid">


        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <button type="submit" name="status" value="N" id="pageSubmit" name="N" class="btn btn-warning">Submit</button>
            </div>
        </div>
    </div>
    <!--	end container-->
    {!! Form::close() !!}
    
    {!! Form::open(array('url'=>'livefeed/'.$article->article_id,'class'=> 'form-horizontal','id'=>'feed_list_from','enctype'=>'multipart/form-data')) !!}
        {!! csrf_field() !!}
        {!! method_field('DELETE') !!}
         <input type="hidden" name="article_id" id="artile_id" value="{{$article->article_id}}"/>
         <div class="container-fluid" id="feed-list">
                       <!--Sortable Responsive Media Table begin-->
                       <div class="row-fluid">
                           <div class="span12">
                               <table class="table table-striped table-responsive" id="tableSortableResMed">
                                   <thead class="cf sorthead">
                                       <tr>
                                           <th>Title</th>
                                           <th>Description</th>
                                           <th class="center">
                                               <input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall">
                                           		
                                           </th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                       @foreach($liveFeeds as $feed)
                                       <tr class="gradeX" id="rowCur{{$feed->id}}">
                                           <td><a href="{{url('livefeed/'.$feed->id).'/edit'}}">{{$feed->title}}</a></td>
                                           <td><a href="{{url('livefeed/'.$feed->id).'/edit'}}">{!!mb_strimwidth(strip_tags($feed->description),0,50,'....')!!}</a></td>
                                           <td  class="left">
                                                <input type="checkbox" name="checkItem[]" class="uniformRadio" value="{{$feed->id}}">
                                           </td>
                                       </tr>
                                       @endforeach                                       
                                   </tbody>
                               </table>

                           </div>
                           
                       </div>
                       <!--Sortable Responsive Media Table end-->
                       
                        <div class="control-group row-fluid">
                                            <div class="span12 span-inset">
                                                <button class="btn btn-danger pull-right" onclick="deleteFeed();" type="button" name="delete" style="display:block;">Delete</button> 
                                                <!--<a href="cms-right-management.html">
                                                	<button class="btn btn-warning pull-right" type="submit" name="edit" style="display:block; margin-right:10px">Modify</button>
                                                </a>-->
                                            </div>
                                        </div>
                           
                       
           </div><!-- end container -->
        {!! Form::close() !!}
		   <script>
                       $(document).ready(function() {
                           $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable( {
                               "sPaginationType": "bootstrap",
                               "aaSorting": [] ,
                              "aoColumnDefs": [ { "bSortable": false, "aTargets": [2] } ],
                               "fnInitComplete": function(){
                                   $(".dataTables_wrapper select").select2({
                                       dropdownCssClass: 'noSearch'
                                   });
                               }
                           });
                         
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
                    function deleteFeed() {
                    var ids = '';
                    var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                        return this.value;
                    }).get();
                    if (checkedVals.length > 0) {
                        var ids = checkedVals.join(",");
                        $('#feed_list_from').submit();
                      
                    } else {
                        alert('Please select at least one record.');
                        }
                    }
                   </script>
    <script type="text/javascript" src="{{ asset('js/florawysiwyg/froala_editor.pkgd.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/florawysiwyg/froala_editor.pkgd.min.css') }}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/florawysiwyg/froala_style.min.css') }}" media="all" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/florawysiwyg/font-awesome.min.css') }}" media="all" />   
</div>
@stop
