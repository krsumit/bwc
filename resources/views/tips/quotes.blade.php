@extends('layouts/master')

@section('title', 'Quotes - BWCMS')
@section('content')

    <div class="panel">
        <div class="panel-content filler">
            <div class="panel-logo"></div>
            <div class="panel-header">
                <h1><small>Quotes</small></h1>
            </div>
            <div class="panel-search container-fluid">
                @if(Request::input('channel'))
               <form class="form-horizontal" method="get" action="{{url('quotes')}}">
                     <input type="text" name="channel" id="channel" value="{{Request::input('channel')}}" style="display: none;"/>
                    <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                    <button class="btn btn-search" type="submit"></button>
                     @if(isset($_GET['keyword'])) 
                     <a href="{{url('quotes')}}?channel={{Request::input('channel')}}"><button class="btn btn-default" type="button">Reset</button></a>
                   @endif
             </form>
                @endif
            </div>

            <br><br>

            <script type="text/javascript">
                $(function () {
                    //alert(1);
                    $("#jstree").jstree({
                        "json_data" : {
                            "data" : [

                                {
                                    "data" : {
                                        "title" : "Channel",
                                        "attr" : { "href" : "#channel" }
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

                function getEditQuote(id) {
                    //alert(id);
                    $.get("{{ url('/quotes/edit/')}}",
                            {option: id},
                            function (data) {
                                //add to relevant fields
                                //alert(data);
                                $("#add_new").show();
                                $("#btn_addnew").hide();
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
                                       var baseUrl="{{config('constants.awsbaseurl')}}{{config('constants.quotesimage')}}";
                                        //alert(index);
                                         // alert(element);
                                        //alert(element);
                                        if (index == 'quote_id') {
                                           
                                            $('#qid').val(element);
                                        }
                                        
                                        if (index == 'description') {
                                            
                                            $('#description').val(element);
                                        }
                                       if (index == 'quotes_image') {
                                            //alert(1);
                                          var image =  $('#edit_quotes_image').val(element);
					$('#quets_image img').attr('src',baseUrl+element);
					
                                        }
                                       if (index == 'tag') {  
                                             //var p="";
                                             $.each(element, function(index, element2) {
                                                 //alert(element2.toSource());
                                                 $("#Taglist").tokenInput("clear");

                                                 $("#Taglist").tokenInput("add", element2);
                                               //if(p==''){p=element2.name;}else{
                                                // p = p+','+element2.name;
                                             //}
                                              
 
                                             });
                                            
                                             
                                            $('#token-input-Taglist').val(p);
                                     
                                        }
                                    });
                                });
                                //Loop on all tags, select the one selected
                                var lst = '';
                                $.each(two, function (inx, el) {
                                    //for each field
                                    //alert(el);
                                    lst += el + ',';
                                });
                                //$("#tiptag").val([res]);
                                $('#quotetag').val(lst.split(','));
                                $("#quotetag").select2();
                            });
                }
                function deleteQuote(){
                    //alert($('#selectEdit').val());
                    var ids = '';
                    var checkedVals = $('.uniformCheckbox:checkbox:checked').map(function() {
                        var row = 'rowCur' + this.value;
                        $("#"+ row).hide();
                        return this.value;
                    }).get();
                    var ids = checkedVals.join(",");
                    //alert(ids);
                    $.get("{{ url('/quotes/delete/')}}",
                            { option: ids },
                            function(data) {
                                $.each(checkedVals, function (i, e) {
                                    var row = 'rowCur' + e;
                                    $("#" + row).hide();
                                });
                            });
                }
                function addTags(){
                    var ids = $('#tagadded').val();
                    alert(ids);
                    //alert($('#selectEdit').val());
                    $.get("{{ url('/quotes/addTag/')}}",
                            { option: ids },
                            function(data) {

                                var resplen = (data).length;
                                var lst = '';
                                var existing = $( "#quotetag" ).val();
                                $.each(data, function(index, element) {
                                    if((resplen > 1)){
                                        $.each(element, function(ind, ele){
                                            $("#quotetag").append($('<option>', {
                                                value: ele.tag_id,
                                                text: ele.tag
                                            }));
                                            lst += ele.tag_id + ',';
                                        });
                                    }else{
                                        $("#quotetag").append($('<option>', {
                                            value: element.tag_id,
                                            text: element.tag
                                        }));
                                        lst = element.tag_id + ',';
                                    }
                                });
                                lst +=existing;
                                $('#quotetag').val(lst.split(','));
                                $("#quotetag").select2();
                            });
                }

            </script>
            <!-- Add Tag to Tags Table - Ajax request -->
            <script>
            function addqueatsfunction(){
            var valid=1
            $('.author_error').remove();
            $('#add_new input').removeClass('error');
            $('#add_new select').removeClass('error');
             $('#add_new textarea').removeClass('error');
            if(!($('#description').val())){
            valid=0;
            $('#description').addClass('error');
            $('#description').after(errorMessage('Please fill description'));
            }
              if(!($('#Taglist').val())){
                //alert(1);
                 valid=0;
                $('#Taglist').addClass('error');
                $('#Taglist').after(errorMessage('Please choose existing Tags'));
              
            }
            if(!($('select[name=channel_sel]').val())){
                //alert(1);
                 valid=0;
                $('select[name=channel_sel]').addClass('error');
                $('select[name=channel_sel]').after(errorMessage('Please select chennal'));
              
            }
            if(valid==0)
                return false;
            else
            return true;
            }
            function errorMessage($msg){
             return '<span class="error author_error" style="clear:both;display:inline-block">'+$msg+'</span>';
            }
                
                
                $().ready(function() {
                        var token = $('input[name=_token]');
                                // process the form
                        $("#attachTag").click(function(){
                        if ($('input[name=tagadded]').val().trim().length == 0){
                        alert('Please enter tage'); return false;
                        }

                        $.ajax({
                        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                                url         : '/quotes/addTag', // the url where we want to POST
                                data        :   { tag : $('input[name=tagadded]').val() },
                                dataType    : 'json', // what type of data do we expect back from the server
                                encode      : true,
                                beforeSend  :function(data){
                                $('#attachTag').hide();
                                        $('#attachTag').siblings('img').show();
                                },
                                complete    :function(data){
                                $('#attachTag').show();
                                        $('#attachTag').siblings('img').hide();
                                },
                                success     :  function(data){

                                $.each(data, function(key, val){

                                $("#Taglist").tokenInput("add", val);
                                });
                                        $('input[name=tagadded]').val('');
                                        //alert('Tag Saved');
//                                        $("#Taglist").tokenInput("add", [{"id":"2","name":"Coal Scam"},{"id":"4","name":"Cuisine"},{"id":"7","name":"Education"},{"id":"15","name":"Election"},{"id":"208","name":"testtag1"},{"id":"1","name":"Modi"},{"id":"207","name":"tagtest"},{"id":"210","name":"ankita"}]);
//                                         //$("#Taglist").tokenInput("add", {id: 9992, name: "test22"});
                                },
                                headers: {
                                'X-CSRF-TOKEN': token.val()
                                }
                        })
                        });
                                $("#Taglist").tokenInput("/quotes/tags/getJson", {
                                theme: "facebook",
                                searchDelay: 300,
                                minChars: 4,
                                tokenLimit:1,
                                preventDuplicates: true,
                                
                                
                        });
                         
                        });
                               
            </script>
            
            <div class="sidebarMenuHolder mCustomScrollbar _mCS_1" style="height: 268px;"><div class="Jstree_shadow_top"></div><div style="position:relative; height:100%; overflow:hidden; max-width:100%;" id="mCSB_1" class="mCustomScrollBox"><div style="position:relative; top:0;" class="mCSB_container mCS_no_scrollbar"><div class="mCustomScrollBox" id="mCSB_1" style="position:relative; height:100%; overflow:hidden; max-width:100%;"><div class="mCSB_container mCS_no_scrollbar" style="position:relative; top:0;"><div style="position:relative; height:100%; overflow:hidden; max-width:100%;" id="mCSB_1" class="mCustomScrollBox"><div style="position:relative; top:0;" class="mCSB_container mCS_no_scrollbar">
                                        <div class="JStree">

                                            <div id="jstree" class="jstree jstree-0 jstree-focused jstree-default"><ul><li class="jstree-leaf"><ins class="jstree-icon">&nbsp;</ins><a href="#currently-displayed" class=""><ins class="jstree-icon">&nbsp;</ins>Currently Displayed</a></li><li class="jstree-leaf"><ins class="jstree-icon">&nbsp;</ins><a href="#tableSortableResMed" class=""><ins class="jstree-icon">&nbsp;</ins>Logo Management</a></li><li class="jstree-last jstree-leaf"><ins class="jstree-icon">&nbsp;</ins><a href="#new-logo" class=""><ins class="jstree-icon">&nbsp;</ins>New Logo</a></li></ul></div>

                                        </div>
                                    </div><div style="position: absolute; display: none;" class="mCSB_scrollTools"><a style="display:block; position:relative;" class="mCSB_buttonUp"></a><div style="position:relative;" class="mCSB_draggerContainer"><div style="position: absolute; top: 0px;" class="mCSB_dragger"><div style="position:relative;" class="mCSB_dragger_bar"></div></div><div class="mCSB_draggerRail"></div></div><a style="display:block; position:relative;" class="mCSB_buttonDown"></a></div></div></div><div class="mCSB_scrollTools" style="position: absolute; display: none;"><a class="mCSB_buttonUp" style="display:block; position:relative;"></a><div class="mCSB_draggerContainer" style="position:relative;"><div class="mCSB_dragger" style="position: absolute; top: 0px;"><div class="mCSB_dragger_bar" style="position:relative;"></div></div><div class="mCSB_draggerRail"></div></div><a class="mCSB_buttonDown" style="display:block; position:relative;"></a></div></div></div><div style="position: absolute; display: none;" class="mCSB_scrollTools"><a style="display:block; position:relative;" class="mCSB_buttonUp"></a><div style="position:relative;" class="mCSB_draggerContainer"><div style="position: absolute; top: 0px;" class="mCSB_dragger"><div style="position:relative;" class="mCSB_dragger_bar"></div></div><div class="mCSB_draggerRail"></div></div><a style="display:block; position:relative;" class="mCSB_buttonDown"></a></div></div><div class="Jstree_shadow_bottom"></div></div>    </div>
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
                    <a href="javascript:;">Quotes</a>
                </li>
            </ul>
        </div>           <header>
            <i class="icon-big-notepad"></i>
            <h2><small>Quotes</small></h2>

        </header>
        {!! Form::open(array('url'=>'quotes/','class'=> 'form-horizontal','id'=>'validation_form','enctype'=>'multipart/form-data','onsubmit'=>'return addqueatsfunction()')) !!}
        {!! csrf_field() !!}
            <div class="container-fluid"  @if((!Session::has('message')) && (!Session::has('error')))style="display: none" @endif >
                 <input type="hidden" name="edit_quotes_image" id="edit_quotes_image" value="">
                <div id="Notifications" class="form-legend" >Notifications</div>

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


        <input type="hidden" name="id" value="{{$uid}}">
        <input type="hidden" id="qid" name="qid" value="">
            <div class="container-fluid">
                <div class="form-legend" id="channel">Channel</div>

                <!--Select Box with Filter Search begin-->
                <div id="assign-article-to-a-Issue" class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" for="channel_sel">Channel</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <select name="channel_sel" id="channel_sel">
                                <option selected="" value="">---- Please Select ----</option>
                                @foreach($channels as $c)
                                    <option @if(Request::input('channel')==$c->channel_id) selected="selected" @endif; value="{{$c->channel_id}}">{{$c->channel}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <script>
                        $().ready(function(){
                            $("#channel_sel").select2();
                        });
                    </script>
                </div>
                <!--Select Box with Filter Search end-->

            </div>

            <div class="container-fluid" id="new">
                <div class="form-legend" >Add A New Quote</div>
                <div class="control-group row-fluid">
                    <div class="span12 span-inset">
                        <button type="button" class="btn btn-success" id="btn_addnew">Add New Quote</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                    </div>
                </div>
                <div id="add_new">
                    

                    <!--Text Area Resizable begin-->
                    <div id="Text_Area_Resizable" class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Quote</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <textarea  rows="4" name="description" id="description" class=""></textarea>
                            </div>
                        </div>
                    </div>
                    <!--Text Area Resizable end-->
                      <!--Text field Resizable begin-->
                    <div class="control-group row-fluid" id="photourl">
                    <div class="span3">
                        <label class="control-label">Quote Image</label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file" style="margin-bottom:0px;"><span class="fileupload-new">Browse</span><span class="fileupload-exists">Change</span>
                                    <input type="file" name="quotes_image" id="quotes_image" value="" />
                                </span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
				<div id="quets_image"><img src="" style="width:129px;"></div>
				
                            </div>
                        </div>
                    </div>
                </div>
                    <!--Text field Resizable end-->
                    <!--Select Box with Filter Search begin-->
                  
                    <!--Select Box with Filter Search end-->


                    <div class="control-group row-fluid" id="Multiple_Select_Box_with_Filter_Search">
                        <div class="span3">
                            <label for="quotetag" class="control-label">Choose From Existing Tags</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                 <input type="text" class="valid" name="Taglist" id="Taglist"/>
                            </div>
                        </div>
                        <script>
                            $().ready(function(){
                                $("#quotetag").select2();
                            });
                        </script>
                        </div>

                        <div class="control-group row-fluid">
                            <div class="span3">
                                <label class="control-label" for="add tags">Add New Tags<br>(Separated by Coma)</label>
                            </div>
                            <div class="span9">
                                <div class="controls">
                                    <input type="text" name="tagadded" id="tagadded" class=""><span for="add tags" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                                </div>
                            </div>

			<div class="span12 span-inset">
                            <div style="float:right; width:11%; margin-bottom:5px;"><button type="button" id="attachTag"  class="btn btn-primary" style="display:block;">Attach</button>
                            <img src="images/photon/preloader/76.gif" alt="loader" style="width:50%; display:block; margin-left:15px; display:none;""></div>
                       
			</div>
                       </div>
                    <!--</div>-->
                    <!--Select Box with Filter Search end-->


                    <div class="control-group row-fluid">
                        <div class="span12 span-inset">
                            <button type="submit" name="addQuote" class="btn btn-success">Add</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                        </div>
                    </div>
                </div>

            </div>
            @if(trim(Request::input('channel')))
            <div class="container-fluid" id="quote_list">
                <div class="row-fluid">
                    <div class="span12">
                        <table class="table table-striped" id="tableSortable">
                            <thead>
                            <tr>
                                <th>Quote No.</th>
                                
                                <th>Quotes</th>
                                <th>Tag</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="{{trim(Request::input('channel'))}}">
                            @foreach($quoteArr as  $arr)
                            <?php 
                            //print_r($arr);exit;
                            ?>
                                    <tr class="gradeX" id="rowCur{{$arr->quote_id}}">
                                        <td>{{$arr->quote_id}}</td>
                                       
                                        <td><a href="#" onclick="getEditQuote({{$arr->quote_id}});">{{$arr->description}}</a></td>
                                        <td> {{$arr->tag}} </td>
                                        <td class="center"><input type="checkbox" name="delTip" class="uniformCheckbox" value="{{$arr->quote_id}}"></td>
                                    </tr>
                            @endforeach
                             </tbody>
                        </table>
                    </div>
                     <div class="dataTables_paginate paging_bootstrap pagination">
                    
                {!! $quoteArr->appends(Input::get())->render() !!}
                </div>
                    
                    <div class="control-group row-fluid">
                        <div class="span12 span-inset">
                            <button type="button" onclick="deleteQuote();" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                        </div>
                    </div>
                </div>
                <!--Sortable Non-responsive Table end-->


                <script>
                    $(document).ready(function() {
                        $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable( {
                             bInfo: false,
                              bPaginate:false,
                              "aaSorting": [] ,
                              "aoColumnDefs": [ { "bSortable": false, "aTargets": [3] } ],
                            "fnInitComplete": function(){
                                $(".dataTables_wrapper select").select2({
                                    dropdownCssClass: 'noSearch'
                                });
                            }
                        });
                        //                            $("#simpleSelectBox").select2({
                        //                                dropdownCssClass: 'noSearch'
                        //                            });
                    });
                </script>
            </div>
            @endif
            <!-- end container -->

            <script>
                $(document).ready(function(e) {
                    //$("#quote_list").hide();
                    $("#add_new").hide();
                    $("#btn_addnew").click(function(e) {
                        if($('#channel_sel').attr("value").trim().length==0){
                            alert('Please select channel');
                            return false;
                        }
                        $("#add_new").show();
                        $(this).hide();
                    });
                });
            </script>

            <script type="text/javascript">

                $(document).ready(function(){

                    $("#channel_sel").change(function(){
                        //alert(1);

                        $(this).find("option:selected").each(function(){
                           //alert($(this).attr("value").trim().length);
                            if($(this).attr("value").trim().length != 0){
                                // alert($(this).attr("value"));
                               // $('#channel_sel option').each(function(index,element){
                                
                                //      alert($("#" + element.value));
                                
                                   // $("#" + element.value).hide();
                                    window.location='{{url("quotes")}}'+'?channel='+$(this).attr("value").trim();
                              //  });
                                //var v = $(this).attr("value");
                                //$("#quote_list").show();
                               // $("#" + v).show();
                                //$("#quote_list").show();

                            }

                            else if($(this).attr("value")=="none"){

                                $("#quote_list").hide();

                            }

                        });

                    });

                });

            </script>

        {!! Form::close() !!}
    </div>
    @stop
