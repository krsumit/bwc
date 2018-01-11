@extends('layouts/master')

@section('title', 'Edit category - BWCMS')
@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit Category</small></h1>
        </div>		
       

        <br><br>
        <div class="panel-header">
    <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
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
            <li class="current">
                <a href="javascript:;">Edit Category</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Edit Category</small></h2>

    </header>
    <form class="form-horizontal"method="POST" action="/category/update" onsubmit="return validateCategoryData();">
        {!! csrf_field() !!}
      
        <input type="hidden" value="maincategory" id="maincategory" name="maincategory">
        

        <div class="container-fluid">

            <div class="form-legend" id="tags">Edit Category</div>
            <!--Topics begin-->

            <!--Topics end-->

            <!--Select Box with Filter Search begin-->

            <div class="control-group row-fluid" id="Multiple_Select_Box_with_Filter_Search">


                <script>
                    $().ready(function () {
                        $("#selectBoxFilter").select2();
                    });
                </script>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label for="add tags" class="control-label">Category Name</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="name" id="add_mastercategory" value="{{$category->name}}" class="required number valid"><span for="add tags" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                            <input type="hidden1" name="category_id" value="{{$category->$field}}">
                            <input type="hidden1" name="level" value="{{$level}}"> 
                            <input type="hidden1" name="category_referer" value="{{$referer}}"> 

                            
                        </div>
                    </div>
                    <div class="span12 span-inset">

                        <button style="display:block;" class="btn btn-info" type="submit">Save</button>
                        <img style="width:5%; display:none; " alt="loader" src="images/photon/preloader/76.gif"></div>

                </div>
            </div>                       
        </div>

  
    </form>
</div> 
<script>
    function validateCategoryData(){
           var valid = 1;
                $('.author_error').remove();
                $('#new input').removeClass('error');
                $('#new textarea').removeClass('error');
            if ($('input[name=add_mastercategory]').val().trim() == 0){
                valid = 0;
                $('input[name=add_mastercategory]').addClass('error');
                $('input[name=add_mastercategory]').after(errorMessage('Please enter name'));
                }
            if (valid == 0)
                return false;
                else
                return true;
        }
    function errorMessage($msg){
return '<span class="error author_error">' + $msg + '</span>';
        }
 </script>
@stop