@extends('layouts/master')

@section('title', 'Add-edit-guestauthor - BWCMS')


@section('content')
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>{{$whoauthor}} Listing</small></h1>
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
                     @if(isset($_GET['searchin'])) 
                    <a href="{{url("article/add-edit-author")}}"><button class="btn btn-default" type="button">Reset</button></a>
                    @endif

                </form>
            </div>
             <label class="radio">
                <input type="radio" id="seacrchname" checked @if(isset($_GET['keyword'])) @if($_GET['keyword']!='') checked @endif @endif required name="searchin"  class="uniformRadio">
                Search by {{$whoauthor}} Name
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
                <a href="javascript:;"> {{$whoauthor}} Listing</a>
            </li>
        </ul>
    </div>           
    <form class="form-horizontal" onsubmit="return validateAuthorData()" method="POST" enctype= "multipart/form-data" action="/article/addAuthor">
       {!! csrf_field() !!}
       
        


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
                                <td ><a href="/article/add-author/{{$a->author_id}}">{{$a->name}}</a></td>
                                <td >{{$a->email}}</td>
                                <td  class="center">{{$a->mobile}}</td>
                                <td  class="center"><input type="checkbox" class="uniformCheckbox" value="{{$a->author_id}}" name="checkItem[]"></td>
                            </tr>
                            @endforeach
                            

                        </tbody>
                            

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
                               // $("#" + row).remove();
                            });
                            /*$('#notificationdiv').show();
                            $('#notificationdiv .control-group .span12.span-inset').html('<div class="alert alert-success alert-block">\n\
                                <i class="icon-alert icon-alert-info"></i><button type="button" class="close" data-dismiss="alert">\n\
                                &times;</button><strong>This is Success Notification</strong>\n\
                                <span></span>Selected records dumped.</div>');*/
                           
                            //alert(1);
                        }).done(function(data) {
                            if (data.trim() != 'success'){
                                //alert(data);
                                var obj = JSON.parse(data);
                                var ids=obj.author_id;
                                var authors=obj.author_detail;
                                $.each(checkedVals, function (i, e) {
                                     if (ids.indexOf(e) === -1) {
                                         var row = 'rowCur' + e;
                                         $("#" + row).remove();
                                     }
                                    
                                });
                                message='';
                                for (aut in authors) {
                                    message += authors[aut]+'\n';
                                }
                                message+='Above author(s) are assigned in articles,Can\'t be deleted';
                                alert(message);
                            }
                            else{
                                 $.each(checkedVals, function (i, e) {
                                         var row = 'rowCur' + e;
                                         $("#" + row).remove();
                                });
                                $('#notificationdiv').show();
                            $('#notificationdiv .control-group .span12.span-inset').html('<div class="alert alert-success alert-block">\n\
                                <i class="icon-alert icon-alert-info"></i><button type="button" class="close" data-dismiss="alert">\n\
                                &times;</button><strong>This is Success Notification</strong>\n\
                                <span></span>Selected records dumped.</div>');
                            }
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
                //valid = 0;
                //$('input[name=mobile]').addClass('error');
               // $('input[name=mobile]').after(errorMessage('Please enter mobile'));
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
