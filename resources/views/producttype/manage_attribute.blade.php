@extends('layouts/master')

@section('title', 'Manage Product Type attributes - BWCMS')


@section('content') 


<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Assign Attribute to Product Type</small></h1>

        </div>

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
                                    "title": "Product Type",
                                    "attr": {"href": "#p-type"}
                                }
                            },
                            {
                                "data": {
                                    "title": "Attribute",
                                    "attr": {"href": "#attrb"}
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
            <li>
                <a href="#">Product Type</a>

            </li>
            <li class="current">
                <a href="javascript:;">Assign Attribute to Product Type</a>
            </li>
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Product Type: {{$productType->name}}({{$productType->id}})</small></h2>
        
    </header>
    <div style="margin-bottom:20px;margin-right:20px;text-align:right;">
        <a href="/product-types" onclick="return confirm('All unsaved data will be lost. Do you want to leave this page ?')">
            <button class="btn btn-default" id="draftSubmit" value="S" name="status" type="submit">Product Types List</button>
        </a>
    </div>
    <form class="form-horizontal" action="/product-types/attribute/store" method="post">

        <input type="hidden" name="product_type_id" value="{{$productType->id}}"/>
         {!! csrf_field() !!}

        <div class="container-fluid">

            <div class="form-legend" id="attrb">Assign Attributes To Product Type</div>

            <div class="container-fluid" style="border: none; margin-top:10px;">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="container-fluid" style="margin: 0 !important;">

                            <div class="form-legend">Unassigned Attributes</div>

                            <div class="control-group row-fluid">
                                <div class="span12" style="height:400px; overflow-y: auto;">

                                    <div id="unassigned_att" style="list-style: none; cursor:grab;padding:10px 10px 30px 10px;;min-height:356px;" ondrop="drop(event)" ondragover="allowDrop(event)">
                                        @foreach($unassingedAttributes as $unassingedAttribute)
                                        <span id="drag_{{$unassingedAttribute->id}}" draggable="true" ondragstart="drag(event)" class="badge badge-info" style="width:80%">{{$unassingedAttribute->name}}</span>
                                        @endforeach
                                        
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                    
                    @foreach($attributeGroups as $attributeGroup)
                    <div class="span4" style="margin-bottom: 5px;">
                        <div id="div_group_{{$attributeGroup->id}}" class="container-fluid" style="margin: 0 !important;"s>

                            <div class="form-legend" >{{$attributeGroup->name}}</div>

                            <div class="control-group row-fluid">
                                <div style="padding:10px 10px 30px 10px; min-height:100px; cursor: grab" id="group_{{$attributeGroup->id}}" class="span12" ondrop="drop(event)" ondragover="allowDrop(event)">
                                    @foreach($groupAttributesArray[$attributeGroup->id] as $groupAttribute)
                                        @if($groupAttribute)
                                        <input id="attribute_{{$groupAttribute}}" name="attr_group[{{$attributeGroup->id}}][]" value="{{$groupAttribute}}" type="hidden" class="edit_mod">
                                        <span id="drag_{{$groupAttribute}}" draggable="true" ondragstart="drag(event)" class="badge badge-info" style="width:80%">{{$assignedAttributesDetail[$groupAttribute]}}</span>
                                        @endif
                                        @endforeach
                                    
                                </div>
                            </div>


                        </div>
                    </div>
                    @endforeach
                   
                                 

                </div>


            </div>


            <!--Select Box with Filter Search end-->
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="submit" class="btn btn-info pull-right" style="margin-left: 5px;">Save</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>
                </div>
            </div>

        </div>
        <!--end container-->






    </form>
</div>

<script>
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function drop(ev){
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        group_id=ev.target.id.split('_')[1];
        data_id=data.split('_')[1];
         var input_id='attribute_'+data_id;
        if(ev.target.id.match(/group_*/)){
            //console.log('Matching');
            hidden_input=jQuery('<input type="hidden" id="'+input_id+'" name="attr_group['+group_id+'][]" value="'+data_id+'"/>');
            if ($(document).find("#"+input_id).length > 0){
                $(document).find("#"+input_id).remove();
            }
           $('#'+ev.target.id).append(hidden_input);
           ev.target.appendChild(document.getElementById(data));
        }else{
            //console.log('Not Matching');
            
             if ($(document).find("#"+input_id).length > 0){
                    if($(document).find("#"+input_id).hasClass('edit_mod')){
                        if (confirm("It will remove value of this attribute from all previous entries, Are you sure ?")) {
                            $(document).find("#"+input_id).remove();
                            ev.target.appendChild(document.getElementById(data));
                        }
                        }else{
                             $(document).find("#"+input_id).remove();
                            ev.target.appendChild(document.getElementById(data));
                        }
             }
         }
      }    
        
   
</script>

@stop