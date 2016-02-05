@extends('layouts/master')

@section('title', 'Add-edit-guestauthor - BWCMS')


@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Add BW Reporters</small></h1>
        </div>
        <div class="panel-search container-fluid">
            <div id="searchnameby">
            <form class="form-horizontal" method="get" action="">
                    <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                    <button class="btn btn-search" type="submit"></button>
                     @if(isset($_GET['searchin'])) 
                    <a href="{{url("article/add-edit-author")}}"><button class="btn btn-default" type="button">Reset</button></a>
                    @endif

                </form>
            </div>
            <div id="searchemailby">
            <form class="form-horizontal" method="get" action="">
                    <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keywordemail'] or ''}}" type="text" name="keywordemail">
                    <button class="btn btn-search" type="submit"></button>
                     @if(isset($_GET['keywordemail'])) 
                    <a href="{{url("article/add-edit-author")}}"><button class="btn btn-default" type="button">Reset</button></a>
                    @endif

                </form>
            </div>
             <label class="radio">
                <input type="radio" id="seacrchname" checked @if(isset($_GET['keyword'])) @if($_GET['keyword']!='') checked @endif @endif required name="searchin"  class="uniformRadio">
                Search by Columnist Name
            </label>
            <label class="radio">
                <input type="radio" id="seacrchemail" @if(isset($_GET['keywordemail'])) @if($_GET['keywordemail']!='') checked @endif @endif required name="searchin" class="uniformRadio" value="article_id">
               Search by Email ID
            </label>
        </div>

       

        <script>
             $().ready(function () {
                $("#searchemailby" ).hide();
                $("#seacrchname" ).click(function() {
                    $("#searchnameby" ).show();
                    $("#searchemailby" ).hide();
                    });
               $("#seacrchemail" ).click(function() {
                    $("#searchemailby" ).show();
                    $("#searchnameby" ).hide();
                    });    
            });
            $().ready(function () {
                $(".uniformRadio").uniform({
                    radioClass: 'uniformRadio'
                });

            });
        </script>
        <br><br>
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
                                    "title": "Add A New Columnist",
                                    "attr": {"href": "#new"}
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
                <a href="dashboard.html">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li class="current">
                <a href="javascript:;">Add BW Reporters</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Add BW Reporters</small></h2>

    </header>
    <form class="form-horizontal" onsubmit="return validateAuthorData()" method="POST" enctype= "multipart/form-data" action="/article/addAuthor">
       {!! csrf_field() !!}
       <input id="author_type" class="uniformRadio" type="hidden" value="2" name="author_type" style="opacity: 0;">
       <input id="is_columnist" class="uniformRadio" type="hidden" value="0" name="is_columnist" style="opacity: 0;">
       <input id="photo" class="uniformRadio" type="hidden" value="" name="photoset" style="opacity: 0;">
        <input id="isertedbyauthordata" class="uniformRadio" type="hidden" value="isertedbybwreportersdata" name="isertedbybwreportersdata" style="opacity: 0;">
        <div class="container-fluid">

            <div class="form-legend" id="Notifications">Notifications</div>

            <!--Notifications begin-->
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    @if (Session::has('message'))
                    <div class="alert alert-success alert-block">
                        <i class="icon-alert icon-alert-info"></i>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>This is Success Notification</strong>
                        <span>{{ Session::get('message') }}</span>
                    </div>
                      @endif  
                       @if (Session::has('allready'))
                    <div class="alert alert-block">
                        <i class="icon-alert icon-alert-info"></i>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>This is Alert Notification</strong>
                        <span>{{ Session::get('allready') }}</span>
                    </div>
                        @endif  
                    
                </div>
            </div>
            <!--Notifications end-->

        </div>

        <div class="container-fluid">
            <div class="form-legend" id="new">Add A New BW Reporters

            </div>

            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="inputField">Name</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input  name="name" type="text" id="name">
                    </div>
                </div>
            </div>
            <div id="Text_Area_-_No_Resize" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Bio</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <textarea rows="4" class="no-resize" name="bio" id="bio"></textarea>
                    </div>
                </div>
            </div>
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="inputField">Email</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input  name="email" type="email" id="email">
                    </div>
                </div>
            </div>
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="inputField">Mobile</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input  name="mobile" type="text" id="mobile">
                    </div>
                </div>
            </div>
            <div id="File_Upload" class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Photo(File Size <= {{config('constants.maxfilesize').' '.config('constants.filesizein')}})</label>
                </div>
                <div class="span9">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="input-append">
                            <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input  name="photo"type="file"></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label" for="inputField">Twitter</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input id="twitter" name="twitter" type="text">
                    </div>
                </div>
            </div>

            
            <input type="hidden" id="qid" name="qid" value="">
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button class="btn btn-warning pull-right" type="submit" style="display:block;">Add</button>
                </div>
            </div>


        </div>


        <div class="container-fluid">


            <!--Sortable Responsive Media Table begin-->
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped table-responsive" id="tableSortableResMed">
                        <thead class="cf sorthead">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email-ID</th>
                                <th>Mobile</th>
                                <th><input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall"></th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($posts as $a)
                            <tr class="gradeX" id="rowCur{{$a->author_id}}">
                                <td style="width:160px;"><img src="{{ config('constants.awsbaseurl').config('constants.awauthordir').$a->photo}}" alt="User Image" style="width:70%;" /></td>
                                <td ><a href="#"onclick="getEditcolumnist({{$a->author_id}})">{{$a->name}}</a></td>
                                <td >{{$a->email}}</td>
                                <td  class="center">{{$a->mobile}}</td>
                                <td  class="center"><input type="checkbox" class="uniformCheckbox" value="{{$a->author_id}}" name="checkItem[]"></td>
                            </tr>
                            @endforeach
                            

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="dataTables_paginate paging_bootstrap pagination">
                    
                 {!! $posts->appends(Input::get())->render() !!}
                </div>
            <!--Sortable Responsive Media Table end-->
                    
        </div><!-- end container -->
       
        <script>
            $(document).ready(function () {
                $('#tableSortable').dataTable({
                    "sPaginationType": "bootstrap",
                    "fnInitComplete": function () {
                        $(".dataTables_wrapper select").select2({
                            dropdownCssClass: 'noSearch'
                        });
                    }
                });
                //                            $("#simpleSelectBox").select2({
                //                                dropdownCssClass: 'noSearch'
                //                            }); 
                
                
                 $('#selectall').click(function(){
                            if($(this).is(':checked')) {
                                $('input[name="checkItem[]"]').each(function(){
                                    $(this).attr('checked','checked');
                                });
                            }else{
                                 $('input[name="checkItem[]"]').each(function(){
                                    $(this).removeAttr('checked');
                                });
                            }
                         });
            });
            
             function deleteAuthor() {
                        var ids = '';
                        var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                            var row = 'rowCur' + this.value;
                           
                            return this.value;
                        }).get();
                        
                       // alert(2);
                        var ids = checkedVals.join(",");
                        //alert(ids);return false;
                        $.get("{{ url('/author/delete/')}}",
                                {option: ids},
                        function (data) {
                            $.each(checkedVals, function (i, e) {
                                var row = 'rowCur' + e;
                                $("#" + row).remove();
                            });
                            $('#notificationdiv').show();
                            $('#notificationdiv .control-group .span12.span-inset').html('<div class="alert alert-success alert-block">\n\
                                <i class="icon-alert icon-alert-info"></i><button type="button" class="close" data-dismiss="alert">\n\
                                &times;</button><strong>This is Success Notification</strong>\n\
                                <span></span>Selected records dumped.</div>');
                           
                            //alert(1);
                        });
                    }
                    
        </script>


         <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button type="button" onclick="deleteAuthor()" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>							

            </div></div>
    </form>
</div>
<script>
    function validateAuthorData(){
           var valid = 1;
                $('.author_error').remove();
                $('#new input').removeClass('error');
                $('#new textarea').removeClass('error');
            if ($('input[name=name]').val().trim() == 0){
                valid = 0;
                $('input[name=name]').addClass('error');
                $('input[name=name]').after(errorMessage('Please enter name'));
                }
            if ($('textarea[name=bio]').val().trim() == 0){
                valid = 0;
                $('textarea[name=bio]').addClass('error');
                $('textarea[name=bio]').after(errorMessage('Please enter bio'));
                }
            if ($('input[name=email]').val().trim() == 0){
                valid = 0;
                $('input[name=email]').addClass('error');
                $('input[name=email]').after(errorMessage('Please enter email'));
                } else{

                if (!(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test($('input[name=email]').val()))){
                    valid = 0;
                    $('input[name=email]').addClass('error');
                    $('input[name=email]').after(errorMessage('Please enter vaild email'));
                    }
                }
            if ($('input[name=mobile]').val().trim() == 0){
                valid = 0;
                $('input[name=mobile]').addClass('error');
                $('input[name=mobile]').after(errorMessage('Please enter mobile'));
                }else {
                    var regex=/^(\d{1,3}[- ]?)?\d{10}$/;
                    if(!regex.test($('input[name=mobile]').val())){
                        valid = 0;
                        $('input[name=mobile]').addClass('error');
                        $('input[name=mobile]').after(errorMessage('Please enter valid mobile'));
                        }
                    }
            
                                    //alert(valid);
            if (valid == 0)
                return false;
                else
                return true;
        }
    function errorMessage($msg){
return '<span class="error author_error">' + $msg + '</span>';
        }
    function getEditcolumnist(id) {
                    //alert(id);
                    $.get("{{ url('/columnist/edit/')}}",
                            {option: id},
                            function (data) {
                                //add to relevant fields
                                //alert(data);
                                
                                var result = jQuery.parseJSON(data);
                                
                                var one;
                                var two;
                                $.each(result, function(index, element) {
                                   // alert(index);
                                    //alert(element);
                                    if(index == 0) {
                                        one = element;
                                    }else{
                                        two = element;
                                    }
                                });
                                $.each(one, function(ind, ele) {
                                    $.each(ele, function(index, element) {
                                      
                                        //alert(index);
                                         // alert(element);
                                        //alert(element);
                                        if (index == 'author_id') {
                                           
                                            $('#qid').val(element);
                                        }
                                        if (index == 'name') {
                                            
                                            $('#name').val(element);
                                        }
                                        if (index == 'bio') {
                                            
                                            $('#bio').val(element);
                                        }
                                        if (index == 'author_type_id') {
                                            
                                             $('#author_type_id').val(element);
                                        }
                                         if (index == 'email') {
                                            
                                             $('#email').val(element);
                                        }
                                       if (index == 'mobile') {  
                                             //var p="";
                                              $('#mobile').val(element);
                                     
                                        }
                                        if (index == 'photo') {  
                                             //var p="";
                                              $('#photo').val(element);
                                     
                                        }
                                        if (index == 'twitter') {  
                                             //var p="";
                                              $('#twitter').val(element);
                                     
                                        }
                                    });
                                });
                                //Loop on all tags, select the one selected
                                
                            });
                }       
</script>
@stop
