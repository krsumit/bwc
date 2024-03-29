@extends('layouts/master')

@section('title', 'Tips - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Tips</small></h1>
        </div>
        <div class="panel-search container-fluid">
            <form action="javascript:;" class="form-horizontal">
                <input type="text" name="panelSearch" placeholder="Search by Tags Name" id="panelSearch" class="ui-autocomplete-input" autocomplete="off"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><span class="ui-helper-hidden-accessible" aria-live="polite" role="status"></span><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                <button class="btn btn-search"></button>
                <script>
                    $().ready(function () {
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
                        $("#panelSearch").autocomplete({
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
                    "json_data": {
                        "data": [
                            {
                                "data": {
                                    "title": "Channel",
                                    "attr": {"href": "#channel"}
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

            function getTipsList(cid) {
                //alert(cid);
                //alert($('#selectEdit').val());
                $.get("{{ url('/tips/list/')}}",
                        {option: cid},
                function (data) {
                    //add to relevant fields
                    //alert(data);
                    //var response = jQuery.parseJSON(data);
                    //alert(data.length);
                    var response = jQuery.parseJSON(data);
                    //alert(response);
                    var r;
                    //var response=jQuery.parseJSON(data);
                    $.each(data, function (ind, elem) {
                        //Each Row

                        r = "<tr class=gradeX id='+ind+'>";
                        //alert(elem.length);
                        //alert(ind);
                        //alert(elem);
                        $.each(elem, function (index, element) {
                            //Column

                            //alert(element);
                            if (index == 'tip') {
                                r += "<td>" + element + "</td>";
                            }
                            if (index == 'tip_id') {
                                r += "<td>" + element + "</td>";
                            }
                            if (index == 't_category_id') {
                                r += "<td>" + element + "</td>";
                            }
                            if (index == 't_tags') {
                                r += "<td>" + element + "</td>";
                            }
                            //alert(elem);
                            //$('#channel_sel').append("<option value='"+ element +"'>"+ index +"</option>");
                        });
                        r += "</tr>";
                    });
                    $('#tiprows').append(r);
                });
            }

            function getEditTip(id) {
                //alert(id);
                //alert($('#selectEdit').val());
                $.get("{{ url('/tips/edit/')}}",
                        {option: id},
                function (data) {
                    //add to relevant fields
                    //alert(data);
                    //var result = $.parseJSON(data);
                    $("#add_new").show();
                    $("#btn_addnew").hide();
                    var result = jQuery.parseJSON(data);
                    var one;
                    var two;
                    //alert(result.length);
                    $.each(result, function (index, element) {
                        //alert(index);
                        //alert(element);
                        if (index == 0) {
                            one = element;
                        } else {
                            two = element;
                        }
                    });
                    //alert(one);
                    //alert(two);
                    //one = jQuery.parseJSON(one);
                    //two = jQuery.parseJSON(two);
                    $.each(one, function (ind, ele) {
                        $.each(ele, function (index, element) {
                            //alert(element);
                            //alert(element);
                            if (index == 'tip_id') {
                                $('#tid').val(element);
                            }
                            if (index == 'tip') {
                                $('#tip').val(element);
                            }
                            if (index == 'description') {
                                $('#description').val(element);
                            }
                            if (index == 't_category_id') {
                                $('#t_category').val(element);
                                $("#t_category").select2();
                            }
                        });
                    });
                    //Loop on all tags, select the one selected
                    var lst = '';
                    $.each(two, function (inx, el) {
                        //for each field
                        $.each(el, function (i, e) {
                            //alert(e);
                            if (i == 'ttag_id') {
                                lst += e + ',';
                            }
                        });
                    });
                    //var res = lst.substr(-1);
                    //$("#tiptag").val([res]);
                    //var res = lst.substring(0,lst.length-1);
                    $('#tiptag').val(lst.split(','));
                    $("#tiptag").select2();
                });
            }
            function deleteTip() {
                //alert($('#selectEdit').val());
                var ids = '';
                var checkedVals = $('.uniformCheckbox:checkbox:checked').map(function () {
                    var row = 'rowCur' + this.value;
                    $("#" + row).hide();
                    return this.value;
                }).get();
                var ids = checkedVals.join(",");
                //alert(ids);
                //alert($('input[type="checkbox"]:checked').not(":disabled"));
                //var id = $('#selectEdit').val();
                $.get("{{ url('/tips/delete/')}}",
                        {option: ids},
                function (data) {
                    $.each(checkedVals, function (i, e) {
                        var row = 'rowCur' + e;
                        $("#" + row).hide();
                    });
                });
            }
// from validations  whith js  by sumit start below -------------                
            $(document).ready(function(){
            
            function errorMessage($msg){
            return '<div class="error author_error">' + $msg + '</div>';
            }
            )};
                        
        
        
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
                <a href="/dashboard">
                    <i class="icon-photon home"></i>
                </a>
            </li>

            <li class="current">
                <a href="javascript:;">Tips</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Tips</small></h2>

    </header>
    {!! Form::open(array('url'=>'tips/','class'=> 'form-horizontal','id'=>'validation_form222','onsubmit'=>'return addtipsfunction()')) !!}
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



    <div class="container-fluid">
        <input type="hidden" name="id" value="{{$uid}}">
        <input type="hidden" id="tid" name="tid" value="">
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
                        $('#channel_sel').on('click', function() {
                //alert($(this).val());
                //getTipsList( this.value ); // or $(this).val()
                });
                });</script>
        </div>
        <!--Select Box with Filter Search end-->

    </div>

    <div class="container-fluid" >
        <div class="form-legend" >Add A New Tip</div>
        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                <button type="button" class="btn btn-success" id="btn_addnew">Add New Tip</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
            </div>
        </div>
        <div id="add_new">
            <div class="control-group row-fluid">
                <div class="span3">
                    <label class="control-label">Tip </label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <input type="text" name="tip" id="tip" >
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
                    <label class="control-label" for="t_category">Category</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select name="t_category" id="t_category" >
                            <option selected="" value="">---- Please Select ----</option>
                            @foreach($tipcategory as $tc)
                            <option value="{{$tc->tcate_id}}">{{$tc->tcategory}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <script>
                            $().ready(function(){
                    $("#t_category").select2();
                    });</script>
            </div>
            <!--Select Box with Filter Search end-->


            <div class="control-group row-fluid" id="Multiple_Select_Box_with_Filter_Search">
                <div class="span3">
                    <label for="tiptag" class="control-label">Choose From Existing Tags</label>
                </div>
                <div class="span9">
                    <div class="controls">
                        <select multiple name="tiptag[]" id="tiptag">
                           
                            @foreach($tiptags as $ttag)
                            <option value="{{$ttag->ttag_id}}">{{$ttag->tag}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <script>
                            $().ready(function(){
                    $("#tiptag").select2();
                    });</script>
            </div>
            <!--Select Box with Filter Search end-->


            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="submit" name="addTip" id="addTip" class="btn btn-success" >Add</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                </div>
            </div>
        </div>

    </div>




    <div class="container-fluid" id="tip_list">
        <div class="row-fluid">
            <div class="span12">
                <table class="table table-striped" id="tableSortable">
                    <thead>
                        <tr>
                            <th>Tip No.</th>
                            <th>Category</th>
                            <th>Tip</th>
                            <th>Tag</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    @foreach($tipArr as $t => $arr)
                    <tbody id="{{$t}}">
                        @foreach($arr as $a)
                        <tr class="gradeX" id="rowCur{{$a->tip_id}}">
                            <td>{{$a->tip_id}}</td>
                            <td>{{$a->tcategory}}</td>
                            <td><a href="#" onclick="getEditTip({{$a->tip_id}});">{{$a->tip}}</a></td>
                            <td>@if($tagArr[$a->tip_id]) {{$tagArr[$a->tip_id]}} @endif</td>
                            <td class="center"><input type="checkbox" name="delTip" class="uniformCheckbox" value="{{$a->tip_id}}"></td>
                        </tr>
                        @endforeach
                    </tbody>
                    @endforeach

                </table>
            </div>
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="button" onclick="deleteTip();" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                </div>
            </div>
        </div>
        <!--Sortable Non-responsive Table end-->
       

        <script>
                    $(document).ready(function() {
            $('#tableSortable, #tableSortableRes, #tableSortableResMed').dataTable({
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
            });</script>
        
        
    </div>
    <!-- end container -->

    <script>
                $(document).ready(function(e) {
        $("#tip_list").hide();
                $("#add_new").hide();
                $("#btn_addnew").click(function(e) {
        $("#add_new").show();
                $(this).hide();
        });
        });</script>

    <script type="text/javascript">

                $(document).ready(function(){

        $("#channel_sel").change(function(){

        $(this).find("option:selected").each(function(){
        //alert($(this).attr("value"));
        if ($(this).attr("value") != "none"){
        //alert($('#' + v));
        $('#channel_sel option').each(function(index, element){
        $("#" + element.value).hide();
        });
                var v = $(this).attr("value");
                $("#tip_list").show();
                $("#" + v).show();
        }

        else if ($(this).attr("value") == "none"){
        //                           // alert('NOne - hide');
        $("#tip_list").hide();
        }
        });
        }).change();
        });
        
        function addtipsfunction(){
            var valid=1;
            $('.author_error').remove();
            $('#new input').removeClass('error');
            $('#new textarea').removeClass('error');
            if($('input[name=tip]').val().trim()==0){
            valid=0;
            $('input[name=tip]').addClass('error');
            $('input[name=tip]').after(errorMessage('Please fill tip'));
            }
            if($('select[name=t_category]').val().trim()==0){
            valid=0;
            $('select[name=t_category]').addClass('error');
            $('select[name=t_category]').after(errorMessage('Please select category'));
            }
            if(!($('#tiptag').val())){
                //alert(1);
                 valid=0;
                $('#tiptag').addClass('error');
                $('#tiptag').after(errorMessage('Please select tag'));
              
            }
          
            if(valid==0)
                return false;
            else
            return true;
            }
            function errorMessage($msg){
             return '<div class="error author_error">'+$msg+'</div>';
            }
            
        
    </script>
    {!! Form::close() !!}
</div>

@stop
