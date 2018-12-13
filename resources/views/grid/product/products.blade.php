@extends('layouts/master')

@section('title', 'Grid Products - BWCMS')

@section('content') 
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Grid Products</small></h1>

        </div>
        <div class="panel-search container-fluid">
            
        </div>

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
                <a href="javascript:;">Grids</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Grid Products</small></h2>
    </header>
  
        <div class="container-fluid" id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')))style="display: none" @endif >
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
        <div class="form-horizontal">

        <div class="container-fluid">

            <div class="form-legend" id="Channel">Grid Details</div>

            <!--Select Box with Filter Search begin-->
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
            

            <!--Select Box with Filter Search end-->
            </div>
        </div>
        {!! Form::open(array('url'=>'/grid-products/'.$grid->id,'class'=> 'form-horizontal','id'=>'brands_list_from','enctype'=>'multipart/form-data')) !!}
        {!! csrf_field() !!}
        {!! method_field('PUT') !!} 
        @if(count($rows)>0)            
<!--            <div class="container-fluid">
                Sortable Non-responsive Table begin
                <div class="row-fluid">
                    <div class="span12">
                        <table class="table table-striped" id="tableSortable">
                            <thead>
                                <tr>
                                    <th>Row ID</th>
                                    <th>Name</th>
                                    <th><input type="checkbox" class="uniformCheckbox" value="checkbox1"  id="selectall"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $row)
                                <tr class="gradeX" id="rowCur{{$row->id}}">
                                    <td><a href="/grid-rows/{{$row->id}}/edit">{{$row->id}}</a> </td>
                                    <td><a href="/grid-rows/{{$row->id}}/edit">{{$row->name}}</a></td>
                                    <td class="center"> 
                                       <input type="checkbox" class="uniformCheckbox" value="{{$row->id}}" name="checkItem[]"> 
                                    </td>
                                  
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                Sortable Non-responsive Table end

                <script>
                    $(document).ready(function () {
                        $('#tableSortable').dataTable({
                            bInfo: false,
                            bPaginate: false,
                            "aaSorting": [],
                            "aoColumnDefs": [{"bSortable": false, "aTargets": [2]}],
                            "fnInitComplete": function () {
                                $(".dataTables_wrapper select").select2({
                                    dropdownCssClass: 'noSearch'
                                });
                            }
                        });
                        
                        $('#selectall').click(function () {
                            if ($(this).is(':checked')) {
                                $('input[name="checkItem[]"]').each(function () {
                                    $(this).attr('checked', 'checked');
                                });
                            } else {
                                $('input[name="checkItem[]"]').each(function () {
                                    $(this).removeAttr('checked');
                                });
                            }
                        });
                    });


                    function deleteProductType() {
                            var ids = '';
                            var checkedVals = $('input[name="checkItem[]"]:checkbox:checked').map(function () {
                                return this.value;
                            }).get();
                            if (checkedVals.length > 0) {
                                var ids = checkedVals.join(",");
                                $('#brands_list_from').submit();

                            } else {
                                alert('Please select at least one record.');
                                }
                        }


                </script>
            </div> end container 
            <div class="control-group row-fluid">
                <div class="span12 span-inset">
                    <button type="button" onclick="deleteProductType()" class="btn btn-danger">Dump</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>							
                </div>
            </div>-->
           
        @else
<!--        <div class="container-fluid">
            No row available
        </div>-->
        @endif  
        @if($grid->type=='review')
            <div class="container-fluid" style="overflow:auto;">
                <div class="control-group row-fluid">
                <table class="table table-striped" id="tableSortable">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            @foreach($gridColumns as $gridcolumn)
                            <th>{{$gridcolumn->name}}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tableData as $data) 
                        
                        <tr>
                            <td>{{$data['name']}}</td> 
                            @foreach($gridColumns as $gridcolumn)
                                @if(isset($data['data'][$gridcolumn->att_group_id]))
                                <td>{{$data['data'][$gridcolumn->att_group_id]->rating}}&nbsp;/&nbsp;10</td>
                                @else
                                    <td>NA</td>
                                @endif
                            @endforeach
                        </tr>
                       @endforeach
                    </tbody>
                </table>           
            </div>
            </div>     
               <div class="container-fluid"> 
                <div class="control-group row-fluid">    
                    <div class="span3">
                        <label for="multiFilter" class="control-label">Products</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" class="valid" name="product_list" id="product_list"/>
                        </div>
                    </div>
                </div>
                <div class="control-group row-fluid">    
                    <div class="span3"></div>
                    <div class="span9">
                        <div class="controls">
                            <div class="span12 span-inset">
                                <button type="submit" name="add" value="N" id="add" class="btn btn-warning">Save</button>
                             </div>   
                        </div>
                    </div>
                </div>
            </div>   
      
        <script>
            $().ready(function() { 
                 $("#product_list").tokenInput(function(){ 
                            return "/products/product-json?channel="+{{$channel->channel_id}};
                        }, 
                        {
                                theme: "facebook",
                                searchDelay: 300,
                                minChars: 4,
                                preventDuplicates: true,
                                prePopulate: <?php echo $productList ?>,
                        }); 
         
            });
        </script>
        @else
        <div class="container-fluid" style="overflow:auto;">
            <div class="control-group row-fluid">
                <table class="table table-striped" id="tableSortable">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            @foreach($gridColumns as $gridcolumn)
                            <th>{{$gridcolumn->name}}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $row)
                        <tr>
                            <td>{{$row->name}}</td>
                             @foreach($gridColumns as $gridcolumn)
                            <th>
                                <input type="hidden" name="hiadden_product_list[{{$row->id}}][{{$gridcolumn->id}}]" id="hiadden_product_list_{{$row->id}}_{{$gridcolumn->id}}" value='@if(isset($productList[$row->id][$gridcolumn->id])) [{{json_encode($productList[$row->id][$gridcolumn->id])}}] @endif'/>    
                                <input type="text" class="valid product_list_grid" name="product_list[{{$row->id}}][{{$gridcolumn->id}}]" id="product_list_{{$row->id}}_{{$gridcolumn->id}}"/>
                                
                            </th>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>           
            </div>
            <div class="control-group row-fluid">    
                    <div class="span3"></div>
                    <div class="span9">
                        <div class="controls">
                            <div class="span12 span-inset">
                                <button type="submit" name="add" value="N" id="add" class="btn btn-warning">Save</button>
                             </div>   
                        </div>
                    </div>
                </div>
        </div>
        <script>
            $().ready(function() { 
                 $('#tableSortable').dataTable({
                        bInfo: false,
                        bPaginate: false,
                        bSort: false,
                        bSearch:false
                    });
         
                        
                $('input[name^="product_list"]').each(function() {
                    var id=$(this).attr('id');
                    id=id.replace('product_list','hiadden_product_list');
                    var prepop=$('#'+id).val();
                    if(prepop.length==0)
                        prepop='""';
                      $(this).tokenInput(function(){ 
                            return "/products/product-json?channel="+{{$channel->channel_id}};
                        }, 
                        {
                                theme: "facebook",
                                searchDelay: 300,
                                minChars: 4,
                                preventDuplicates: true,
                                tokenLimit:1,
                                prePopulate:JSON.parse(prepop)
                        }); 
                    
                });
                
            });
        </script>
        @endif
         {!! Form::close() !!}
</div>
@stop