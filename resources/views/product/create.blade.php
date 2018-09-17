@extends('layouts/master')
@section('title', 'Add Variant (Model:'.$model->name.') - BWCMS')
@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Add Variant</small></h1>
        </div>
        <script type="text/javascript">
            $(function () {
            $("#jstree").jstree({
            "json_data" : {
            "data" : [
            {
            "data" : {
            "title" : "Variant Detail",
                    "attr" : { "href" : "#variant_detail" }
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
            <li>
                <a href="/products/{{$model->id}}">Model Variant</a>

            </li>
            <li class="current">
                <a href="javascript:;">Add Variant</a>
            </li>
        </ul>
    </div>            
    <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Add Variant of (Model :{{$model->name}})</small></h2>
        Brand : {{$brand->name}}(Product Type : {{$productType->name}})
    </header>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/products/{{$model->id}}" >
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Variant List</button>
        </a>
    </div>
    {!! Form::open(array('url'=>'products','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
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

    <div class="container-fluid" id="variant_detail">
        <div class="form-legend" id="feed-detail">Variant Details</div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="title">Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" id="product_name" name="product_name" required="required"  value="{{ old('product_name')}}"/>

                </div>
            </div>
        </div>


    </div>  
    @foreach($attributeGroups as $group)
    <div class="container-fluid" id="group_section_{{$group->id}}">
        <div class="form-legend" id="feed-detail">{{$group->name}}</div>
        {{--*/ $attributes=explode(',',$group->group_attributes) /*--}}
        @foreach($attributes as $attribute)

        {!!App\Helpers\Helper::getAttributeHtml($attribute)!!}

        @endforeach

    </div>  
    @endforeach

    <div class="container-fluid">


        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <button type="submit" name="add" value="N" id="add_button"class="btn btn-warning" onclick="this.disabled = true" >Save</button>
            </div>
        </div>
    </div>

</div>


<script type="text/javascript" src="{{ asset('colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('colorpicker/css/bootstrap-colorpicker.min.css') }}" media="all" />

<script>
                            $(document).ready(function () {
                                $('.attribute_colorpicker').colorpicker()

                            });
                            $('#add_colorpicker').click(function(){
                                var insert_element=$(this).parent('div').parent('div').find('.colorpicker_div').eq(0).clone();
                                //alert(insert_element);
                                $(this).parent('.span-inset').before(insert_element);
                                $(document).find('.attribute_colorpicker').colorpicker()
                            });
                            $(document).find('.del_colorpicker').live('click', function(){
                                if($(document).find('.del_colorpicker').length>1){
                                    if ($(this).siblings('input.attribute_colorpicker').val().trim()){
                                    if (confirm('Do you want to remove "' + $(this).siblings('input').val() + '" ?')){
                                        $(this).parent('div').remove();
                                    }
                                    } else{
                                        $(this).parent('div').remove();
                                    }
                                }
                            });

</script>    

@stop
