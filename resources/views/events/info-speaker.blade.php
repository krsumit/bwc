@extends('layouts/master')

@section('title', 'Event Attendee - BWCMS')


@section('content')

<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small>Event Attendee Details</small></h1>
        </div>
        <script>
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
                                    "title": "Attendee Details",
                                    "attr": {"href": "#speaker-details"}
                                },
                            },
                            {
                                "data": {
                                    "title": "Professional Details",
                                    "attr": {"href": "#professional-detail"}
                                },
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
                <a href="javascript:;">Event Attendee Details</a>
            </li>
        </ul>
    </div>           <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Event Attendee Details</small></h2>

    </header>
    {!! Form::open(array('url'=>'speaker','class'=> 'form-horizontal','id'=>'speaker-form','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="container-fluid" id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')))style="display: none" @endif >

         <div class="form-legend" id="Notifications">Notifications</div>

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

    <div class="container-fluid" id="speaker-details">
        <div class="form-legend" id="new">Attendee Details

        </div>

        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label zero-padding" for="inputField">Name</label>
            </div>
            <div class="span9 ">
                <div class="controls control-label zero-padding">
                   {{$speaker->name}}
                </div>
            </div>
        </div>

        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label zero-padding" for="inputField">Email</label>
            </div>
            <div class="span9">
                <div class="span9 control-label zero-padding">
                   {{$speaker->email}}
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label zero-padding" for="inputField">Mobile</label>
            </div>
            <div class="span9">
                <div class="controls control-label zero-padding">
                     {{$speaker->mobile}}
                </div>
            </div>
        </div>

        <div id="File_Upload" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label  zero-padding">Photo</label>

            </div>
            <div class="span9">
                <div class="controls control-label zero-padding">
                     {{$speaker->photo}}
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label zero-padding" for="inputField">Twitter</label>
            </div>
            <div class="span9">
                 <div class="controls control-label zero-padding">
                     {{$speaker->twitter}}
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">
            <div class="span3">
                <label class="control-label zero-padding" for="inputField">Linkedin</label>
            </div>
            <div class="span9">
               <div class="controls control-label zero-padding">
                     {{$speaker->linkedin}}
                </div>
            </div>
        </div>
        <div id="Text_Area_-_No_Resize" class="control-group row-fluid">
            <div class="span3">
                <label class="control-label zero-padding">Description</label>
            </div>
            <div class="span9">
                 <div class="controls control-label zero-padding">
                     {{$speaker->description}}
                </div>
            </div>
        </div>
        <div class="control-group row-fluid">    
            <div class="span3">
                <label for="multiFilter" class="control-label zero-padding">Tags(Industry)</label>
            </div>
            <div class="span9">
                <div class="controls control-label zero-padding">
                     {{$speaker->tags}}
                </div>
            </div>
        </div>
       
        <!-- Add Tag to Tags Table - Ajax request -->
       
    </div>
   
    <div class="container-fluid" id="professional-detail">
        <div class="form-legend" id="tags">Professional Detail</div>
        <div class="control-group row-fluid">
            <div class="span12">
                <table class="table table-striped" id="tableSortable">
                        
                        <tbody>
                             @foreach($speakerDetails as $detail)
                             <tr>
                                <td @if($detail->is_current==0) style="font-weight:bold;" @endif >{{$detail->designation}}({{$detail->company}}) </td>                               
                                <td>
                                    <a href="#" class="show-detail">Show</a>
                                    <a href="#" class="hide-detail" style="display:none;">Hide</a>
                                </td>
                                
                            </tr>
                            <tr style="display:none;width:100%">
                                <td  colspan="2">
                                    <table>
                                        <tr>
                                            <td>Designation</td>
                                            <td>{{$detail->designation}}</td>
                                        </tr>
                                        <tr>
                                            <td>Company</td>
                                            <td>{{$detail->company}}</td>
                                        </tr>
                                        <tr>
                                            <td>Mobiles</td>
                                            <td>{{$detail->mobiles}}</td>
                                        </tr>
                                        <tr>
                                            <td>Email(s)</td>
                                            <td>{{$detail->emails}}</td>
                                        </tr>
                                        <tr>
                                            <td>City</td>
                                            <td>{{$detail->city}}</td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td>{{$detail->address}}</td>
                                        </tr>
                                        
                                    </table>
                                </td>
                            </tr>
                             @endforeach
                        </tbody>
            </div>
          
        </div>
       
    </div>
                            

    <script>
        $(document).ready(function () {
            $("#speaker-form").validate({
                errorElement: "span",
                errorClass: "error",
                //$("#pageSubmit").onclick: true,
                onclick: true,
                invalidHandler: function (event, validator) {

                    for (var i in validator.errorMap) { ///alert(i);

                        if ($('#' + i).hasClass('formattedelement')) {
                            $('#' + i).siblings('.formattedelement').addClass('error');
                        }

                    }
                },
                rules: {
                    "req": {
                        required: true
                    },
                    "speaker_name": {
                        required: true
                    },
                }
            });

            $('.show-detail').click(function(){
               $(this).toggle();
               $(this).siblings('.hide-detail').toggle(); 
               $(this).parents('tr').next('tr').toggle();
            });
            $('.hide-detail').click(function(){
               $(this).toggle();
               $(this).siblings('.show-detail').toggle();
               $(this).parents('tr').next('tr').toggle(); 
            });

        });
    </script>

    {!! Form::close() !!}

</div>

@stop