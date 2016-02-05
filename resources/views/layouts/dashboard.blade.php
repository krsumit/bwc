@extends('layouts.master')

@section('title', 'Dashboard - BWCMS')


@section('sidebar')
    @parent
	
@endsection

@section('content')
	
	    <div class="container-fluid dashboard dashboard-title">
            <div class="row-fluid">
                <div class="span12">
                    <h1>
                        Dashboard
                    </h1>
                    <input type="hidden" name="userid" value="{{old('id')}}">
                </div>
            </div>
        </div>
        <div class="container-fluid dashboard dashboard-widget-group">
             <div class="row-fluid">
                <div id="photon_widgets" class="span12 ui-sortable">
                    <!-- General Stats Widget begin -->
<script>
    $().ready(function() {
        $(".widget-general-stats select").select2();
    });
</script>
<div class="container-fluid " id="notificationdiv"  @if((!Session::has('message')) && (!Session::has('error')))style="display: none" @endif >

            <!--Notifications begin-->
            <div class="control-group row-fluid" >
                <div class="span12 span-inset">
                    @if(Session::has('message'))
                    <div class="alert alert-success alert-block">
                        <i class="icon-alert icon-alert-info"></i>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>This is Success Notification</strong>
                        <span>{{ Session::get('message') }}</span>
                    </div>
                    @endif
                    @if(Session::has('error'))
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
<div class="widget-holder">
    <div class="widget-flipper">
        <div class="widget-area widget-general-stats widget-front">
            <div class="widget-head">
                Content Stats
                
            </div>
            <ul>
                <li>
                    Articles Published
                    <div>
                        <span>{{$article_publish}}</span>
                       
                    </div>
                </li>
                <li>
                    Quick Bytes Published
                    <div>
                        <span>{{$quickbyte_publish}}</span>
                        
                    </div>
                </li>
                <li>
                    Videos Published
                    <div>
                        <span>{{$videos_publish}}</span>
                       
                    </div>
                </li>
                <li>
                   Photos Published
                    <div>
                        <span>{{$photos_publish}}</span>
                        
                    </div>
                </li>
                <li>
                   Columns Published
                    <div>
                        <span>{{$columns_publish}}</span>
                       
                    </div>
                </li>
            </ul>
        </div>

        <div class="widget-area widget-general-stats widget-back">
            <div class="widget-savehead">
                <a href="javascript:;" class="btn btn-mini btn-inverse" onClick="flipit(this)"><i class='icon-photon cog'></i>Done</a>
            </div>
            <form>
                <div class="container-fluid widget-settings">
                    <div class="control-group row-fluid">
                        <div class="span12">
                            <label class="control-label">Filter by Location:</label>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span12">
                            <div class="controls">
                                <select>
                                    <option selected="" value="Any">Any</option>
                                    <option value="Europe">Europe</option>
                                    <option value="Asia">Asia</option>
                                    <option value="North America">America</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span12">
                            <label class="control-label">Filter by Period</label>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span12">
                            <div class="controls">
                                <select>
                                    <option selected="" value="Last Year">Last Year</option>
                                    <option value="Last Quarter">Last Quarter</option>
                                    <option value="Last Month">Last Month</option>
                                    <option value="Last Week">Last Week</option>
                                    <option value="Yesterday">Yesterday</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>

                    <!--Dashboard Specific Jquery Pre-loading Functions-->
                    <script>
                        $().ready(function() {

                            var isDragActive = false;
                            // Quicklaunch Widget
                            $( "#sortable" ).sortable({
                                cancel: '#sortable li:last-child',
                                start: function(event, ui) {
                                    isDragActive = true;
                                    $('.dashboard-quick-launch li img').tooltip('hide');
                                },
                                stop: function(event, ui) {
                                    isDragActive = false;
                                },
                                containment: 'parent',
                                tolerance: 'pointer'
                            });

                            // Make widgets sortable
                            $( "#photon_widgets" ).sortable({
                                cancel: '.blank-widget, .flip-it',
                                placeholder: 'dashboard-widget-placeholder',
                                start: function(event, ui) {
                                    isDragActive = true;
                                    $('.widget-holder').addClass('noPerspective');
                                    $('.dashboard-quick-launch li img').tooltip('hide');
                                },
                                stop: function(event, ui) {
                                    isDragActive = false;
                                    $('.widget-holder').removeClass('noPerspective');
                                },
                                tolerance: 'pointer'
                            });


                            $('.dashboard-quick-launch li img').not('.dashboard-quick-launch li:last-child').tooltip({
                                placement: 'top',
                                html: true,
                                trigger: 'manual',
                                title: '<a href="javascript:;"><span class="left">Edit</span></a><a href="javascript:;"><span class="right">Delete</span></a>'
                            });


                            var hoverTimeout;
                            $('.dashboard-quick-launch li').hover(function () {
                                if (!$(this).find('.tooltip').length){
                                    $activeQL = $(this);
                                    clearTimeout(hoverTimeout);
                                    hoverTimeout = setTimeout(function() {
                                        if (isDragActive) return;
                                        $activeQL.find('img').tooltip('show');
                                    }, 1000);
                                }
                            }, function () {
                                clearTimeout(hoverTimeout);
                                $('.dashboard-quick-launch li').find('img').tooltip('hide');
                            });

                            var firstHover = true;
                            $('.dashboard-quick-launch li').hover(function(){
                                if (firstHover) {
                                    firstHover = false;
                                    setTimeout(function(){
                                        $.pnotify({
                                            title: 'Assignment Alert',
                                            type: 'info',
                                            text: 'You have 2 New Articles to write'
                                        });
                                    }, 400);
                                }
                            });
                        });
                    </script>
                    <!--End ---- Dashboard Specific Jquery Pre-loading Functions-->

<script>
    $().ready(function() {
        if (widgetsLoaded['widget-latest-users']) return;
        widgetsLoaded['widget-latest-users'] = true;
        
        $('.widget-latest-users li').each(function () {
            var thisUserName = $('span', this).text();
            var thisImgSrc = $('img', this).attr('src');
            var tooltipTemp = $('.widget-tip-template').clone(true, true);
            
            $('.user-name', tooltipTemp).text(thisUserName);
            $('.avatar-big', tooltipTemp).attr('src', thisImgSrc);

            $('img', this).tooltip({
                placement: 'top',
                html: true,
                trigger: 'manual',
                title: tooltipTemp.html()
            });
        });

        var hoverUsersTimeout;
        $('.widget-latest-users li').hover(function () {
            if (!$(this).find('.tooltip').length){
                $activeQL = $(this);
                clearTimeout(hoverUsersTimeout);
                hoverUsersTimeout = setTimeout(function() {
                    $activeQL.find('img').tooltip('show');
                }, 500);
            }
        }, function () {
            $('.widget-latest-users li').find('img').tooltip('hide');
            clearTimeout(hoverUsersTimeout);
        });

        $(".widget-latest-users select").select2();
    });
</script>
<div class="widget-holder">
    <div class="widget-flipper">
        <div class="widget-area widget-latest-users widget-front">
            <!-- USER TIP TEMPLATE -->
          <div class='widget-tip-template'>
                <div class='avatar-section'>
                    <img class='avatar-big' src='images/photon/user2.jpg' alt='profile' />
                </div>
               
                <div class='text-section'>
                    <span class='user-name'>Lakshman</span>
                    <span class='user-location'>Chennai, India</span>
                    <span class='user-info'>l.k@gmail.com<br/>+91 9876543210, 011 25652721<br/>Registred: 9/26/2012 (8:56PM)</span>
					
                </div>
                
            </div>

            <div class="widget-head">
                Top Writers
                
            </div>
            <ul>
                @foreach($posts as $writers)
                <li  >
                    <div class="avatar-image"  >
                       @if($writers->photo)
                        <img src="{{$writers->photo}}" alt="profile"/>
                        @else
                         <img src="images/photon/author_dummy.png" alt="profile"/>
                        @endif
                    </div>
                    <span>{{$writers->name}}</span> 
                    <span style="display: none">{{$writers->email}}</br>{{$writers->mobile}}<br/>Registred: {{$writers->created_at}}</span> 
                    <!--<div>5 mins</div>-->
                </li>
                @endforeach
                
            </ul>
        </div>
               
                
        <div class="widget-area widget-latest-users widget-back">
            <div class="widget-savehead">
                <a href="javascript:;" class="btn btn-mini btn-inverse" onClick="flipit(this)"><i class='icon-photon cog'></i>Done</a>
            </div>
            <form>
                <div class="container-fluid widget-settings">
                    <div class="control-group row-fluid">
                        <div class="span12">
                            <label class="control-label">Filter by Location:</label>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span12">
                            <div class="controls">
                                <select>
                                    <option selected="" value="Any">Any</option>
                                    <option value="Europe">Europe</option>
                                    <option value="Asia">Asia</option>
                                    <option value="North America">America</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group row-fluid">
                        <div class="span12">
                            <label class="control-label">Filter By</label>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span12">
                            <div class="controls">
                                <select>
                                    <option selected="" value="Yes">All</option>
                                    <option value="No">Currently Login</option>
                                    <option value="Only if Viking">Latest Registered</option>
                                </select>
                            </div>
                        </div>
                    </div>
					
					 <div class="control-group row-fluid">
                        <div class="span12">
                            <label class="control-label">Filter by Period</label>
                        </div>
                    </div>
                    <div class="control-group row-fluid">
                        <div class="span12">
                            <div class="controls">
                                <select>
                                    <option selected="" value="Last Year">Last Year</option>
                                    <option value="Last Quarter">Last Quarter</option>
                                    <option value="Last Month">Last Month</option>
                                    <option value="Last Week">Last Week</option>
                                    <option value="Yesterday">Yesterday</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>




                  </div>
            </div>
        </div>

	
@endsection