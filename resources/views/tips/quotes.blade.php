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
                <form action="javascript:;" class="form-horizontal">
                    <input type="text" name="panelSearch" placeholder="Search by Tags Name" id="panelSearch" class="ui-autocomplete-input" autocomplete="off"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><span class="ui-helper-hidden-accessible" aria-live="polite" role="status"></span><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
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
                                    //alert(index);
                                    //alert(element);
                                    if(index == 0) {
                                        one = element;
                                    }else{
                                        two = element;
                                    }
                                });
                                $.each(one, function(ind, ele) {
                                    $.each(ele, function(index, element) {
                                        //alert(element);
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
                                        if (index == 'q_category_id') {
                                            $('#category').val(element);
                                            $("#category").select2();
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
        {!! Form::open(array('url'=>'quotes/','class'=> 'form-horizontal','id'=>'validation_form')) !!}
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

            <div class="container-fluid" >
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
                            <div class="controls">
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
                                <select name="category" id="category">
                                    <option selected="" value="none">---- Please Select ----</option>
                                    @foreach($qtcategory as $tc)
                                        <option value="{{$tc->cate_id}}">{{$tc->category}}</option>
                                    @endforeach
                                </select>
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
                                <select multiple name="quotetag[]" id="quotetag">
                                    @foreach($quotetags as $tag)
                                        <option value="{{$tag->tag_id}}">{{$tag->tag}}</option>
                                    @endforeach
                                </select>
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
                                    <input type="text" name="add tags" id="tagadded" class=""><span for="add tags" generated="true" class="error" style="display: none;">Please enter a valid text.</span>
                                </div>
                            </div>

							<div class="span12 span-inset">
							  <div style="float:right; width:11%; margin-bottom:5px;"><button type="button" onclick="addTags();" class="btn btn-primary" style="display:block;">Attach</button>
							  <img src="images/photon/preloader/76.gif" alt="loader" style="width:50%; display:block; margin-left:15px;"></div>
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