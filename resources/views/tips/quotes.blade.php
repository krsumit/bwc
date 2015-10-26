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
               <form class="form-horizontal" method="get" action="">
                    <input id="panelSearch" required  placeholder="Search" value="{{$_GET['keyword'] or ''}}" type="text" name="keyword">
                    <button class="btn btn-search" type="submit"></button>
                     @if(isset($_GET['keyword'])) 
                     <a href="quotes"><button class="btn btn-default" type="button">Reset</button></a>
                   @endif

             </form>
            </div>

            <br><br>

            <script type="text/javascript">
                $(function () {
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
                                      
                                        alert(index);
                                          alert(element);
                                        //alert(element);
                                        if (index == 'quote_id') {
                                           
                                            $('#qid').val(element);
                                        }
                                        if (index == 'quote') {
                                            
                                            $('#quote').val(element);
                                        }
                                        if (index == 'description') {
                                            
                                            $('#description').val(element);
                                        }
                                        if (index == 'category') {
                                          
                                             $("#authors").tokenInput("add", element);
                                           
                                        }
                                       if (index == 'tag') {  
                                             //var p="";
                                             $.each(element, function(index, element2) {
                                                 //alert(element2.toSource());
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
             if(!($('#authors').val())){
                //alert(1);
                 valid=0;
                $('#authors').addClass('error');
                $('#authors').after(errorMessage('Choose From Existing Tags'));
              
            }
            
            if(!($('#Taglist').val())){
                //alert(1);
                 valid=0;
                $('#Taglist').addClass('error');
                $('#Taglist').after(errorMessage('Choose From Existing Tags'));
              
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
                                preventDuplicates: true,
                                
                                
                        });
                         $("#authors").tokenInput("/author/getJson", {
                                theme: "facebook",
                                searchDelay: 300,
                                minChars: 2,
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
        {!! Form::open(array('url'=>'quotes/','class'=> 'form-horizontal','id'=>'validation_form','onsubmit'=>'return addqueatsfunction()')) !!}
        {!! csrf_field() !!}
            <div class="container-fluid">

                <div id="Notifications" class="form-legend">Notifications</div>

                <!--Notifications begin-->
                <div class="control-group row-fluid" style="display: none">
                    <div class="span12 span-inset">
                        <div class="alert alert-success alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>This is Success Notification</strong>
                            <span>Your data has been successfully modified.</span>
                        </div>
                        <div class="alert alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>This is Alert Notification</strong>
                            <span>No result found.</span>
                        </div>
                        <div class="alert alert-error alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>This is Error Notification</strong>
                            <span>Please select a valid search criteria.</span>
                        </div>
                        <div class="alert alert-error alert-block">
                            <i class="icon-alert icon-alert-info"></i>
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>This is Error Notification</strong>
                            <span>Please enter a valid email id.</span>
                        </div>
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
                                <option selected="" value="none">---- Please Select ----</option>
                                @foreach($channels as $c)
                                    <option value="{{$c->channel_id}}">{{$c->channel}}</option>
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
                    <div class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Quote </label>
                        </div>
                        <div class="span9">
                            <div class="controls" id="quotenew">
                                <input type="text" name="quote" id="quote">
                            </div>
                        </div>
                    </div>

                    <!--Text Area Resizable begin-->
                    <div id="Text_Area_Resizable" class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label">Description</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                <textarea  rows="4" name="description" id="description" class=""></textarea>
                            </div>
                        </div>
                    </div>
                    <!--Text Area Resizable end-->

                    <!--Select Box with Filter Search begin-->
                    <div  class="control-group row-fluid">
                        <div class="span3">
                            <label class="control-label" for="category">Category</label>
                        </div>
                        <div class="span9">
                            <div class="controls">
                                 <input type="text" class="valid" name="category" id="authors"/>
                            </div>
                        </div>
                       
                        <script>
                            $().ready(function(){
                                $("#category").select2();
                            });
                        </script>
                    </div>
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




            <div class="container-fluid" id="quote_list">
                <div class="row-fluid">
                    <div class="span12">
                        <table class="table table-striped" id="tableSortable">
                            <thead>
                            <tr>
                                <th>Quote No.</th>
                                <th>Category</th>
                                <th>Quotes</th>
                                <th>Tag</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            @foreach($quoteArr as $q => $arr)
                                <tbody id="{{$q}}">
                                @foreach($arr as $a)
                                    <tr class="gradeX" id="rowCur{{$a->quote_id}}">
                                        <td>{{$a->quote_id}}</td>
                                        <td>{{$a->category}}</td>
                                        <td><a href="#" onclick="getEditQuote({{$a->quote_id}});">{{$a->quote}}</a></td>
                                        <td>@if($tagArr[$a->quote_id]) {{$tagArr[$a->quote_id]}} @endif</td>
                                        <td class="center"><input type="checkbox" name="delTip" class="uniformCheckbox" value="{{$a->quote_id}}"></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @endforeach
                        </table>
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
                            "sPaginationType": "bootstrap",
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
            <!-- end container -->

            <script>
                $(document).ready(function(e) {
                    $("#quote_list").hide();
                    $("#add_new").hide();
                    $("#btn_addnew").click(function(e) {
                        $("#add_new").show();
                        $(this).hide();
                    });
                });
            </script>

            <script type="text/javascript">

                $(document).ready(function(){

                    $("#channel_sel").change(function(){

                        $(this).find("option:selected").each(function(){
                            //alert($(this).attr("value"));
                            if($(this).attr("value") != "none"){

                                $('#channel_sel option').each(function(index,element){
                              //      alert($("#" + element.value));
                                    $("#" + element.value).hide();
                                });
                                var v = $(this).attr("value");
                                $("#quote_list").show();
                                $("#" + v).show();
                                //$("#quote_list").show();

                            }

                            else if($(this).attr("value")=="none"){

                                $("#quote_list").hide();

                            }

                        });

                    }).change();

                });

            </script>

        {!! Form::close() !!}
    </div>
    @stop