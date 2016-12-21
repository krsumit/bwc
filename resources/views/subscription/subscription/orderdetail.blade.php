@extends('layouts/master')

@section('title', 'Subscription Order Detail - BWCMS') 


@section('content')
<style> .none { display:none; } </style>
<div class="panel">
    <div class="panel-content filler">
        <div class="panel-logo"></div>
        <div class="panel-header">
            <h1><small> Subscription Order Detail </small></h1>
        </div>

        <div class="panel-header">
        <!--<h1><small>Page Navigation Shortcuts</small></h1>-->
        </div>
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
                <a href="/dashboard">
                    <i class="icon-photon home"></i>
                </a>
            </li>
            <li>
                <a href="#">Order Detail </a>
                <ul class="breadcrumb-sub-nav">
                    <li>
                        <a href="#">Order Detail</a>
                    </li>
                    
                </ul>
            </li>
           
        </ul>
    </div>            <header>
        <i class="icon-big-notepad"></i>
        <h2><small>Subscription Order Detail</small></h2>
    </header>
    {!! Form::open(array('url'=>'subscriptions/order/update','class'=> 'form-horizontal','id'=>'fileupload','enctype'=>'multipart/form-data')) !!}
    {!! csrf_field() !!}
    <input type="hidden" name="subscription_id" id="subscription_id" value="{{$order->id}}"/>
    <input type="hidden" name="referrer_url" value="@if(isset($_SERVER['HTTP_REFERER'])){{ $_SERVER['HTTP_REFERER'] }} @else {{ url("subscriptions/active") }} @endif">
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


    <div class="container-fluid">
        <div class="form-legend" id="Article-Details">Order Detail

        </div>
        <!--Text Area - No Resize begin-->
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="" >Subscriber Name </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <label class="" >{{$order->first_name.' '.$order->last_name}} </label>
                </div>
            </div>
        </div>
        
         <div  class="control-group row-fluid">
            <div class="span3">
                <label class="" >Email </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <label class="" >{{$order->email}} </label>
                </div>
            </div>
        </div>
        
         <div  class="control-group row-fluid">
            <div class="span3">
                <label class="" >Phone </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <label class="" >{{$order->mobile}} </label>
                </div>
            </div>
        </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="" >Package Name </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <label class="" >{{$order->name}}</label>
                </div>
            </div>
        </div>
       
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="" >Package Duration </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <label class="" >{{$order->duration}} @if($order->duration_type=='1') Month(s) @else Year(s) @endif &nbsp; ({{$order->start_date}} - {{$order->end_date}})</label>
                </div>
            </div>
        </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="" >Magazines included </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <label class="" >{{$order->magazines}}</label>
                </div>
            </div>
        </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="" >Freebies </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <label class="" >{{$order->total_amount}} INR</label>
                </div>
            </div>
        </div>
        
         <div  class="control-group row-fluid">
            <div class="span3">
                <label class="" >Discount </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <label class="" >@if($order->discount_type=='1'){{$order->discount}} %  ({{$order->discount_amount}} INR) @else {{$order->discount}} INR @endif</label>
                </div>
            </div>
        </div>
         <div  class="control-group row-fluid">
            <div class="span3">
                <label class="" >Net Amount </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <label class="" >{{$order->net_amount}} INR</label>
                </div>
            </div>
        </div>
        
         <div  class="control-group row-fluid">
            <div class="span3">
                <label class="" >Order Date </label>
            </div>
            <div class="span9">
                <div class="controls">
                    <label class="" >{{$order->created_at}} </label>
                </div>
            </div>
        </div>
        
        <!--Text Area - No Resize end-->
       
        
      
        

    </div><!-- end container1 -->
   

	

    
    <div class="container-fluid">
        <div class="form-legend" id="Article-Details">Order Payment Detail</div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Payment Status </label>
            </div>
            <div class="span9">
                <div class="controls">
                    Paid<input type="radio" name="payment_status" value="1" style="margin:15px" @if($order->payment_status=='1') checked @endif />
                    Pending <input type="radio" name="payment_status" style="margin:15px"  value="0" @if($order->payment_status!='1') checked @endif />
                </div>
                <span class="error">{{$errors->first('payment_status')}}</span>
            </div>
           

        </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Status </label>
            </div>
            <div class="span9">
                <div class="controls">
                    Active<input type="radio" name="status" value="1" style="margin:15px" @if($order->status=='1') checked @endif />
                    Inactive<input type="radio" name="status" style="margin:15px"  value="0" @if($order->status=='0') checked  @endif/>
                </div>
                <span class="error">{{$errors->first('status')}}</span>
            </div>
            
        </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" for="selectBoxFilter">Payment Option</label>
            </div>
            <div class="span9">
                <div class="controls">
                    {{--*/ $paymentId = old('payment_option')?:$order->payment_option_id /*--}}
                    <select name="payment_option" id="payment_option" class="formattedelement">
                        @foreach($paymentOptions as $option)
                            <option value="{{$option->id}}" @if($paymentId==$option->id) selected="selected" @endif  >{{$option->name}}</option>
                        @endforeach
                    </select>
                </div>
                <span class="error">{{$errors->first('payment_option')}}</span>
            </div>
            <script>
                 $(document).ready(function(){
                        $("#payment_option").select2();
                              
                 });
            </script>
        </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Transaction Id</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="transaction_id" value="{{$order->transaction_id}}"/>
                </div>
                <span class="error">{{$errors->first('transaction_id')}}</span>
            </div>
        </div>
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Transaction Detail</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <textarea name="transaction_detail">{{$order->payment_detail}}</textarea>
                </div>
                 <span class="error">{{$errors->first('transaction_detail')}}</span>
            </div>
        </div>
         <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Transaction Proof (If any)</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="file" name="proof">
                    @if(trim($order->payment_doc)) <a target="_blank" href="{{config('constants.awsbaseurl')}}{{config('constants.awpayslipdir')}}{{$order->payment_doc}}"> Click here to see the proof</a> @endif
                </div>
                <span class="error">{{$errors->first('proof')}}</span>

            </div>
        </div>
        
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Payment Date</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="payment_date" id="payment_date" class="span3" value="{{date('Y-m-d',strtotime($order->payment_date))}}"/>
                    </div>
               <span class="error">{{$errors->first('payment_date')}}</span>

            </div>
            
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#payment_date").datepicker({
                        dateFormat: "yy-mm-dd",
                        changeYear: true,
                        yearRange: "-100:+0",
                    });
                   
                });
            </script>
        </div>
        
        <div  class="control-group row-fluid">
            <div class="span3">
                <label class="control-label" id="title" for="title">Subscription Period</label>
            </div>
            <div class="span9">
                <div class="controls">
                    <input type="text" name="start_date" id="start_date" class="span3" value="{{date('Y-m-d',strtotime($order->start_date))}}" /> &nbsp;To
                    &nbsp;<input type="text" name="end_date" id="end_date" class="span3"  value="{{date('Y-m-d',strtotime($order->end_date))}}" />
                    </div>
                 <span class="error">{{$errors->first('start_date')}}, {{$errors->first('end__date')}}</span>
            </div>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#start_date").datepicker({
                        dateFormat: "yy-mm-dd",
                        changeYear: true,
                        yearRange: "-100:+0",
                    });
                    $("#end_date").datepicker({
                        dateFormat: "yy-mm-dd",
                        changeYear: true,
                        yearRange: "-100:+0",
                    });
                });
            </script>
        </div>
     
     
      
     </div>
     
     
      
     
     
 
    <div class="container-fluid">
        <div class="control-group row-fluid" id="submitsection">
            <div class="span12 span-inset">
                <button type="submit" name="submitstatus" value="P" id="publishSubmit" class="btn btn-success">Save</button><img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>
            </div>
        </div>
    </div>
    <!--	end container-->
    {!! Form::close() !!}

</div>
<script>
     $(document).ready(function () {
        $("#fileupload").validate({
            errorElement: "span",
            errorClass: "error",
            rules: {
                'payment_status': {
                    required: true,
                },
                 'status': {
                    required: true,
                },
                 'payment_option': {
                    required: true,
                },
                 'transaction_id': {
                    required: true,
                },
                 'transaction_detail': {
                    required: true,
                },
                 'payment_date': {
                    required: true,
                },
                 'start_date': {
                    required: true,
                },
                 'end_date': {
                    required: true,
                }
                 
            }
        });
    });
</script> 

@stop
