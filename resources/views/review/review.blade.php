@extends('layouts/master')
@section('title', 'Add Review (Model:'.$model->name.') - BWCMS')
@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Add Review</small></h1>
        </div>
        <script type="text/javascript">
            $(function () {
            $("#jstree").jstree({
            "json_data" : {
            "data" : [
            {
            "data" : {
            "title" : "General Information",
                    "attr" : { "href" : "#group_section_general" }
            }
            },  
            @foreach($attributeGroups as $group)
            {
            "data" : {
            "title" : "{{$group->name}}",
                    "attr" : { "href" : "#group_section_{{$group->id}}" }
            }
            },
                    @endforeach
            {
            "data" : {
            "title" : "Conclusion",
                    "attr" : { "href" : "#group_section_conclusion" }
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
            <li class="current">
                <a href="javascript:;">Add Review</a>
            </li>
        </ul>
    </div>            
    <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Add Review (Model :{{$model->name}})</small></h2>
        Brand : {{$brand->name}}(Product Type : {{$productType->name}})
    </header>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/brand-models" >
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Model List</button>
        </a>
    </div>
    {!! Form::open(array('url'=>'reviews/'.$model->id,'class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
    {!! method_field('PUT') !!}
    <input type="hidden" name="model_id" value="{{$model->id}}"/>
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
    
    <div class="container-fluid" id="group_section_general">
        <div class="form-legend" id="feed-detail">General Information</div>
        <div class="control-group row-fluid">
        <div class="span3">
            <label class="control-label" for="title">Title</label>
        </div>
        <div class="span9">
            <div class="controls">
                <input maxlength="255" class="attribute_text" id="review_title" name="review_title" value="{{$model->review_title}}" type="text"></span></div>
         </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Description</label>
            </div>
            <div class="span9">
                <div class="controls"><textarea class="attribute_textarea editor" id="review_description" name="review_description">{{$model->review_description}}</textarea></div>
             </div>
        </div>
        
        <div class="control-group row-fluid">
        <div class="span3">
            <label class="control-label" for="title">Social Title</label>
        </div>
        <div class="span9">
            <div class="controls">
                <input maxlength="255" class="attribute_text" id="review_social_title" name="review_social_title" value="{{$model->review_social_title}}" type="text"></span></div>
         </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Social Description</label>
            </div>
            <div class="span9">
                <div class="controls"><textarea maxlength="255"  class="attribute_textarea editor" id="review_social_description" name="review_social_description">{{$model->review_social_description}}</textarea></div>
             </div>
        </div>
        <div id="File_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label">Upload logo (Size:{{config('constants.dimension_brand_logo')}}, File Size<={{config('constants.maxfilesize').' '.config('constants.filesizein')}})
                </label>
            </div>
            <div class="span9">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="input-append">
                        <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="review_image"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                         
                      
                        </div>

                    </div>
                @if(trim($model->review_image))
                <div>
                  <img style="width: 70px;height: 70px;margin-left: 5px;" src="{{ config('constants.awsbaseurl').config('constants.aws_review_image').$model->review_image}}" alt="user" style="width:12%;" />
                </div>
                 @endif
                </div>

            </div>
        <div id="File_Upload" class="control-group row-fluid">
             <div class="span3">
                <label class="control-label">Select Grid</label>
            </div>
            <div class="span9">
                 <select name="grid_id" id="grid_id">
                     <option value="0">Please Select</option>
                        @foreach($girds as $gird)
                        <option value="{{ $gird->id }}" @if($model->grid_id==$gird->id) selected="selected" @endif>{{ $gird->name }}</option>
                        @endforeach
                </select>
                    <script>
                        $().ready(function(){
                            $("#grid_id").select2();
                        });
                    </script>
            </div>
        </div>
        
    </div>  
    
    

   
    @foreach($attributeGroups as $group)
    <div class="container-fluid" id="group_section_{{$group->id}}">
        <div class="form-legend" id="feed-detail">{{$group->name}}</div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Rating(0-10)</label>
            </div>
            <div class="span9">
                <div class="controls"><input maxlength="255" class="attribute_text span5" id="rating_{{$group->id}}" name="rating[{{$group->id}}]" value="{{$reviews[$group->id]['rating']}}" type="number" min="0" max="10" step=".5"></span></div>
             </div>
            </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Review</label>
            </div>
            <div class="span9">
                <div class="controls"><textarea maxlength="255"  class="attribute_textarea editor" id="review_{$group->id}}" name="reivew[{{$group->id}}]">{{$reviews[$group->id]['review']}}</textarea></div>
             </div>
            </div>
        
    </div>  
    @endforeach
    
    
    
    <div class="container-fluid" id="group_section_conclusion">
        <div class="form-legend" id="feed-detail">Conclusion</div>
       
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Review</label>
            </div>
            <div class="span9">
                <div class="controls"><textarea maxlength="255"  class="attribute_textarea editor" id="review_conclusion" name="review_conclusion">{{$model->review_conclusion}}</textarea></div>
             </div>
            </div>
        
    </div>  
    

    <div class="container-fluid">


        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <button type="submit" name="add" value="N" id="add_button"class="btn btn-warning" >Save</button>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript" src="{{ asset('js/florawysiwyg/froala_editor.pkgd.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/florawysiwyg/froala_editor.pkgd.min.css') }}" media="all" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/florawysiwyg/froala_style.min.css') }}" media="all" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/florawysiwyg/font-awesome.min.css') }}" media="all" />


<script type="text/javascript" src="{{ asset('colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('colorpicker/css/bootstrap-colorpicker.min.css') }}" media="all" />

<script>
                            $(document).ready(function () {
                                
                                $('.editor').froalaEditor({
                                        height: 200,
                                        htmlRemoveTags: [],
                                        pastePlain: true,
                                        imageUploadURL: '/photo/editor/store',
                                        imageUploadParams: {
                                        _token: $('input[name="_token"]').val()
                                        },
                                        imageMaxSize: 1024 * 1024 * 1 / 2
                                });
                                
                                $('.attribute_colorpicker_div').colorpicker({format:'hex'});

                            });
                            $('#add_colorpicker').click(function(){
                                var insert_element=$(this).parent('div').parent('div').find('.colorpicker_div').eq(0).clone();
                                //alert(insert_element);
                                $(this).parent('.span-inset').before(insert_element);
                                $(document).find('.attribute_colorpicker_div').colorpicker({format:'hex'})
                            });
                            $(document).find('.del_colorpicker').live('click', function(){
                                if($(document).find('.del_colorpicker').length>1){
                                    if ($(this).parent('div').find('input.attribute_colorpicker').val().trim()){
                                    if (confirm('Do you want to remove "' + $(this).parent('div').find('input.attribute_colorpicker').val() + '" ?')){
                                        $(this).parent('div').remove();
                                    }
                                    } else{
                                        $(this).parent('div').remove();
                                    }
                                }
                            });

</script>    

@stop
