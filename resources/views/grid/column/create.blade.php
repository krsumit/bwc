@extends('layouts/master')
@section('title', 'Add Grid Column - BWCMS')
@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">Grid
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
                <a href="/grid-columns/{{$grid->id}}">Grid Column</a>

            </li>
            <li class="current">
                <a href="javascript:;">Grid Column</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Add Grid Column</small></h2>
    </header>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/grid-columns/{{$grid->id}}" >
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Column List</button>
        </a>
    </div>
    {!! Form::open(array('url'=>'grid-columns','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
    <input type="hidden" name="grid_id" value="{{$grid->id}}"/>
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
        <div class="form-legend" id="feed-detail">Column Details</div>
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="channel_sel">Channel</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <label class="control-label" for="channel_sel">
                        {{$channel->channel}}
                        </label>
                    </div>
                </div>
             
            </div>
             <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="channel_sel">Grid Type</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <label class="control-label" for="channel_sel">
                        {{ ucfirst($grid->type)}}
                        </label>
                    </div>
                </div>
             
            </div>
            <div  class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="channel_sel">Grid</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <label class="control-label" for="channel_sel">
                        {{$grid->name}}
                        </label>
                    </div>
                </div>
             
            </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="channel_sel">Column Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                   <input type="text" id="column_name" name="column_name"  value="{{ old('column_name')}}"/>
                </div>
            </div>
            
        </div>
        @if($grid->type=='review')
            <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="channel_sel">Product Type</label>
            </div>
            <div class="span9">
                <div class="controls">
                   <select name="product_type" id="product_type" >
                        <option  value="">Select Product Type</option>
                        @foreach($productTypes as $productType )
                        <option value="{{ $productType->id }}">{{ $productType->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
              <script>
                            $().ready(function(){
                                $("#product_type").select2();
                                
                                 $('#product_type').change(function(){
                                     
                                     $.get("{{ url('grid-columns/')}}",
                                         { product_type: $(this).attr("value")},
                                        function(data) {
                                            //alert(data);
                                                var selectBoxFilter3 = $('#attribute_group');
                                                selectBoxFilter3.empty();
                                                selectBoxFilter3.append("<option value=''>Please Select</option>");
                                                $.each(data, function(index, element) {
                                                selectBoxFilter3.append("<option value='" + element + "'>" + index + "</option>");
                                                });
                                                $("#attribute_group").select2();
                                               
                                        }
                                    );
                                                                       
                                 });
                            });
                                                    
                    </script>
            </div>
            <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="attribute_group">Attribute Group</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <select name="attribute_group" id="attribute_group" >
                        <option  value="">Select Attribute Group</option>
                    </select>
                </div>
                 <script>
                            $().ready(function(){
                                $("#attribute_group").select2();
                            });
                                                    
                    </script>
            </div>
            
            </div>
        
        @endif
    </div>  

    <div class="container-fluid">


        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <button type="submit" name="add" value="N" id="add"class="btn btn-warning">Save</button>
            </div>
        </div>
    </div>

</div>
</div>
@stop
