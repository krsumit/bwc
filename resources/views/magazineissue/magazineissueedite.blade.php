@extends('layouts/master')

@section('title', 'Magazine issued Management - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Magazine Articles</small></h1>
        </div>
        <div class="panel-search container-fluid">
            <form class="form-horizontal" action="javascript:;">
                <input id="panelSearch" placeholder="Search" type="text" name="panelSearch">
                <button class="btn btn-search"></button>
                <script>
                    $().ready(function(){
                        var searchTags = [
                            "Dashboard",
                            "Form Elements",
                            "Graphs and Statistics",
                            "Typography",
                            "Grid",
                            "Tables",
                            "Maps",
                            "Sidebar Widgets",
                            "Error Pages",
                            "Help",
                            "Input Fields",
                            "Masked Input Fields",
                            "Autotabs",
                            "Text Areas",
                            "Select Menus",
                            "Other Form Elements",
                            "Form Validation",
                            "UI Elements",
                            "Graphs",
                            "Statistical Elements",
                            "400 Bad Request",
                            "401 Unauthorized",
                            "403 Forbidden",
                            "404 Page Not Found",
                            "500 Internal Server Error",
                            "503 Service Unavailable"
                        ];
                        $( "#panelSearch" ).autocomplete({
                            source: searchTags
                        });
                    });            
                </script>
            </form>
        </div>	
	<label class="radio">
            <input type="radio" name="optionsRadios" class="uniformRadio" value="radio1">
            Search by Columnist Name
        </label>
        <label class="radio">
            <input type="radio" name="optionsRadios" class="uniformRadio" value="radio1">
                Search by Email ID
        </label>						
        <script>
            $().ready(function(){
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
                "json_data" : {
                    "data" : [
                                                {
                            "data" : { 
                                "title" : "Magazine Articles", 
                                "attr" : { "href" : "#ma" } 
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
            });
        </script>
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
                <a href="dashboard.html">
                    <i class="icon-photon home"></i>
                </a>
            </li>
                    <li class="current">
                <a href="javascript:;">Magazine Articles</a>
            </li>
        </ul>
    </div>          
    <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Magazine Articles</small></h2>        
    </header>
    <form class="form-horizontal" method="post" id="artcilmg">
        <input type="hidden" name="m_id" value="{{$id}}" >
        <input type="hidden" name="channel_id" value="{{$posts->channel_id}}" >
        
         {{ csrf_field() }}
         <div class="container-fluid" id="msg">
                <div class="form-legend" id="Notifications">Notifications</div>
                <!--Notifications begin-->
                <div class="control-group row-fluid">
                    <div class="span12 span-inset">
                        <div   class="alert alert-success alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>This is Success Notification</strong>
                            <span>Your data has been successfully modified.</span>
                        </div>
                        
                       
                        
                    </div>
                </div>
                <!--Notifications end-->
            </div>
                
        <div class="container-fluid">
                		
            <div class="form-legend" > Magazine Issue Name</div>                            
            <div  class="control-group row-fluid" id="ch-reporter">
                <div class="span3">
                    <label class="control-label" for="selectBoxFilter">Magazine Issue Name</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="channel" id="selectBoxFilter20">
                            <option value="">{{$posts->title}}</option>
                            
                        </select>
                    </div>
                </div>
                <script>
                    $().ready(function () {
                        $("#selectBoxFilter20").select2();
                    });
                </script>                           
            </div>                            
        </div>  
         <div class="container-fluid" style="margin-bottom:0 !important;">


            <div class="form-legend" id="tags3">Add in Newsletter</div> 

            <div class="row-fluid">
                
                <div class="span12">

                    

                    <table class="table table-striped" id="tableSortable2">
                        <thead>
                           <tr>
                               <th>Article ID</th>
                               <th>Title</th>
                               <th>Featured</th>
                               <th>Last Word</th>
                               <th>Editor-In-Chief's Note</th>
                               <th><input type="checkbox" class="uniformCheckbox" value="checkbox1" id="selectall"></th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($SelectedArticleArr as $selectarticle)
                                <tr  class="gradeX"  id="rowCur{{$selectarticle->article_id}}">
                                   <td>{{ $selectarticle->article_id }}</td>
                                   <td><a href="/article/{{ $selectarticle->article_id }}">{{ $selectarticle->title }}</a></td>
                                   <td class="center"><input type="checkbox" class="uniformCheckbox" value="{{$selectarticle->article_id}}" name="checkItem[]" @if($selectarticle->m_f==1) ? checked ="selected" @endif></td>
                                   <td class="center">
                                                <div class="uniformRadio" id="uniform-ifyes"><span><input id="ifyes" name="m_lw" class="uniformRadio" value="{{$selectarticle->article_id}}" @if($selectarticle->m_lw==1) ? checked ="selected" @endif style="opacity: 0;" type="radio"></span></div>
                                   </td>
                                   <td class="center">
                                                <div class="uniformRadio" id="uniform-ifno"><span ><input id="ifno"  name="m_eicn" class="uniformRadio" value="{{$selectarticle->article_id}}" style="opacity: 0;" type="radio" @if($selectarticle->m_eicn==1) ? checked ="selected" @endif></span></div>
                                   </td>
                                   </td>
                                <td  class="center"> <input type="checkbox" class="uniformCheckbox" name="checkItems[]" value="{{ $selectarticle->article_id }}"></td>
                               </tr>
                              @endforeach       
                            
                        </tbody>
                    </table>
                </div>
                
            </div>
            <!--Sortable Non-responsive Table end-->

             <div class="control-group row-fluid">
                    <div class="span12 span-inset">
                        
                        <button type="button" class="btn btn-danger" onclick="deleteMagazinesissuefeatur()">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                        
                    </div>
                </div>
           
        </div><!-- end container --> 
         
        <div class="container-fluid">
            <div class="form-legend" id="ma">Magazine Articles</div>
                <!--Sortable Responsive Media Table begin-->
               <div class="row-fluid">
                   <div class="span12">
                       <table class="table table-striped table-responsive" >
                           <thead class="cf sorthead">
                               <tr>
                                   <th>Article ID</th>
                                   <th>Title</th>
                                   <th>Featured</th>
                                   <th>Last Word</th>
                                   <th>Editor-In-Chief's Note</th>
                               </tr>
                           </thead>
                           <tbody>
                               
                                @foreach($ArticleArr as $article)
                                <tr  class="gradeX"  id="rowCur{{$article->article_id}}">
                                   <td>{{ $article->article_id }}</td>
                                   <td><a href="/article/{{ $article->article_id }}">{{ $article->title }}</a></td>
                                   <td class="center"><input type="checkbox" class="uniformCheckbox" value="{{$article->article_id}}" name="checkItem[]"></td>
                                   <td class="center">
                                                <div class="uniformRadio" id="uniform-ifyes"><span><input id="ifyes" name="m_lw" class="uniformRadio" value="{{$article->article_id}}" style="opacity: 0;" type="radio"></span></div>
                                   </td>
                                   <td class="center">
                                                <div class="uniformRadio" id="uniform-ifno"><span ><input id="ifno"  name="m_eicn" class="uniformRadio" value="{{$article->article_id}}" style="opacity: 0;" type="radio"></span></div>
                                   </td>
                               </tr>
                              @endforeach  
                               
                           </tbody>
                       </table>

                   </div>
               </div>
               <!--<div class="dataTables_paginate paging_bootstrap pagination">-->
               
    
                    
               
           </div><!-- end container -->
           
            <div class="container-fluid">		
                <div class="control-group row-fluid">
                    <div class="span12 span-inset">
                         <button type="button" class="btn btn-success" onclick="mginsertArticle()">Save</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
			<!--<button type="button" class="btn btn-success">Save</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>-->							
                    </div>
		</div>				
            </div>
           <script>
               $(document).ready(function() {
                   $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable( {
                       "sPaginationType": "bootstrap",
                       "fnInitComplete": function(){
                           $(".dataTables_wrapper select").select2({
                               dropdownCssClass: 'noSearch'
                           });
                       }
                   });
                   
                   $('#selectall').click(function () {
                        if ($(this).is(':checked')) {
                            $('input[name="checkItems[]"]').each(function () {
                                $(this).attr('checked', 'checked');
                            });
                        } else {
                            $('input[name="checkItems[]"]').each(function () {
                                $(this).removeAttr('checked');
                            });
                        }
                    });
               });
               
           </script>			   			   
       </form>
   </div>
<script>
       $(document).ready(function () {
        document.getElementById("msg").style.display = "none"; 
        });          
    function mginsertArticle() {
        var data = $('#artcilmg').serialize();  
            //alert(data);
            var data = $('#artcilmg').serialize();  
            //alert('sumit');return false;
           $.ajax({
                url : "/magazineissue/mginsertArticle", // the endpoint
                type : "POST", // http method
                data : $('#artcilmg').serialize(), // data sent with the post request
                //cache: false,
                // handle a successful response
                    success: function( response ) {
                        //alert(response);
                       document.getElementById("msg").style.display = "block";
                       $('html, body').scrollTop($("#msg").offset().top);
                    }

            });

    }
    
    function deleteMagazinesissuefeatur() {
                    var ids = '';
                    var checkedVals = $('input[name="checkItems[]"]:checkbox:checked').map(function () {
                        //var row = 'rowCur' + this.value;
                        //$("#" + row).hide();
                        return this.value;
                    }).get();
                    if (checkedVals.length > 0) {
                        var ids = checkedVals.join(",");
                        //alert(ids);return false;
                        $.get("{{ url('/magazineissuefeature/delete/')}}",
                                {option: ids},
                        function (data) {
                            if (data.trim() == 'success') {
                                $.each(checkedVals, function (i, e) {
                                    var row = 'rowCur' + e;
                                    $("#" + row).hide();
                                });
                                $('#notificationdiv').show();
                                $('#notificationdiv .control-group .span12.span-inset').html('<div class="alert alert-success alert-block">\n\
                            <i class="icon-alert icon-alert-info"></i><button type="button" class="close" data-dismiss="alert">\n\
                            &times;</button><strong>This is Success Notification</strong>\n\
                            <span></span>Selected records dumped.</div>');
                            } else {
                                alert(data);
                            }
                            //alert(1);
                        });
                    } else {
                        alert('Please select at least one record.');
                    }
                }

</script>
 

@stop