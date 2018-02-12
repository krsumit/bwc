@extends('layouts/master')

@section('title', 'CMS Management - BWCMS')
@section('content')
<?php //echo '<pre>';print_r($delArrcheck); exit; ?>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Edit role</small></h1>
        </div>
        <div class="panel-search container-fluid">

        </div>

    </div> 

    <div class="sidebarMenuHolder">
        <div class="JStree">
            <div class="Jstree_shadow_top"></div>
            <div id="jstree"></div>
            <div class="Jstree_shadow_bottom"></div>
        </div>
    </div>    </div>

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
                <a href="#">Edit Role</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small> Edit Role </small></h2>

    </header>
    {!! Form::open(array('url'=>'roles/update/','class'=> 'form-horizontal','id'=>'form1')) !!}
    {!! csrf_field() !!}
    <div class="container-fluid" @if(count($errors->all())==0) style="display:none;" @endif >

         <div class="form-legend" id="Notifications">Notifications</div>

        <!--Notifications begin-->
        <div class="control-group row-fluid">
            <div class="span12 span-inset">
                @if (count($errors) > 0)
                <div class="alert alert-error alert-block">
                    <i class="icon-alert icon-alert-info"></i>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>This is Error Notification</strong>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                    <!--<span>Please select a valid search criteria.</span>-->
                </div>
                @endif				
            </div>
        </div>
        <!--Notifications end-->

    </div>
    <div class="container-fluid">
        <input type="hidden" name="id" value="{{$roleDetail->id}}">
        <div class="form-legend" >Edit Role</div>

        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="inputField">Name</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input id="inputField" name="name" type="text" value="{{$roleDetail->name}}">
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid rol-sav-btn" style="display:none">
        <div class="form-legend" id="addroles">
            Roles permission
        </div>
        <script>
            $().ready(function () {
                $(".uniformCheckbox").uniform();
            });
        </script>

        <div class="control-group row-fluid " >
            <div class="per_container"></div>
            <div class="span12 span-inset">
                <button type="submit" name="saveRights" class="btn btn-info">Save</button><img src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:5%; display:none;"/>
<!--                <button type="submit" name="" class="btn btn-success">Assign</button><img src="images/photon/preloader/76.gif" alt="loader" style="width:5%; display:none;"/>-->
            </div>
        </div>
    </div><!-- end container -->


    {!! Form::close() !!}
</div>
<script>
    // alert(1);
    $(document).ready(function () {
       // $("input[name='rightArr[]']").click(function () {
         //   if (this.checked) {
                $.ajax({
                    type: 'GET', // define the type of HTTP verb we want to use (POST for our form)
                    url: '/roles/get/channel/permission', // the url where we want to POST
                    data: {id: '<?php echo $roleDetail->id ?>',right_for:'2'},
                    async:false,
                    dataType: 'text', // what type of data do we expect back from the server
                    
                    beforeSend: function (data) {
//                        $('#genTopic').hide();
//                        $('#genTopic').siblings('img').show();
                    },
                    complete: function (data) {
//                        $('#genTopic').show();
//                        $('#genTopic').siblings('img').hide();
                    },
                    success: function (data) {
                       $('.per_container').append(data);
                       $(".uniformCheckbox").uniform();
                       $('.rol-sav-btn').show();                         
                    }
                })
//            } else {
//                $('#permissionch_'+$(this).val()).remove();
//                ///alert(2)
//            }
     //   });
    });


</script>
@stop 