<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Dashboard - ProjectOne</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <link rel="shortcut icon" href="favicon.ico" />
        <link rel="apple-touch-icon" href="iosicon.png" />
<!--    DEVELOPMENT LESS -->
<!--          <link rel="stylesheet/less" href="css/photon.less" media="all" />
        <link rel="stylesheet/less" href="css/photon-responsive.less" media="all" /> -->
<!--    PRODUCTION CSS -->
        <link rel="stylesheet" href="css/css/photon.css" media="all" />
		<link rel="stylesheet" href="css/css/photon-pt2.css" media="all" />

        <link rel="stylesheet" href="css/css/photon-responsive.css" media="all" />

<!--[if IE]>
        <link rel="stylesheet" type="text/css" href="css/css_compiled/ie-only-min.css" />

<![endif]-->

<!--[if lt IE 9]>
        <link rel="stylesheet" type="text/css" href="css/css_compiled/ie8-only-min.css" />
        <script type="text/javascript" src="js/plugins/excanvas.js"></script>
        <script type="text/javascript" src="js/plugins/html5shiv.js"></script>
        <script type="text/javascript" src="js/plugins/respond.min.js"></script>
        <script type="text/javascript" src="js/plugins/fixFontIcons.js"></script>
<![endif]-->
        
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>

<script type="text/javascript" src="js/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="js/plugins/modernizr.custom.js"></script>
<script type="text/javascript" src="js/plugins/jquery.pnotify.min.js"></script>
<script type="text/javascript" src="js/plugins/less-1.3.1.min.js"></script>        
<script type="text/javascript" src="js/plugins/xbreadcrumbs.js"></script>
<script type="text/javascript" src="js/plugins/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.autotab-1.1b.js"></script>
<script type="text/javascript" src="js/plugins/charCount.js"></script>
<script type="text/javascript" src="js/plugins/jquery.textareaCounter.js"></script>
<script type="text/javascript" src="js/plugins/elrte.min.js"></script>
<script type="text/javascript" src="js/plugins/elrte.en.js"></script>
<script type="text/javascript" src="js/plugins/select2.js"></script>
<script type="text/javascript" src="js/plugins/jquery-picklist.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/plugins/additional-methods.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.form.js"></script>
<script type="text/javascript" src="js/plugins/jquery.metadata.js"></script>
<script type="text/javascript" src="js/plugins/jquery.mockjax.js"></script>
<script type="text/javascript" src="js/plugins/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.rating.pack.js"></script>
<script type="text/javascript" src="js/plugins/farbtastic.js"></script>
<script type="text/javascript" src="js/plugins/jquery.timeentry.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.jstree.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="js/plugins/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.mCustomScrollbar.js"></script>
<script type="text/javascript" src="js/plugins/jquery.flot.js"></script>
<script type="text/javascript" src="js/plugins/jquery.flot.stack.js"></script>
<script type="text/javascript" src="js/plugins/jquery.flot.pie.js"></script>
<script type="text/javascript" src="js/plugins/jquery.flot.resize.js"></script>
<script type="text/javascript" src="js/plugins/raphael.2.1.0.min.js"></script>
<script type="text/javascript" src="js/plugins/justgage.1.0.1.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.qrcode.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery.clock.js"></script>
<script type="text/javascript" src="js/plugins/jquery.countdown.js"></script>
<script type="text/javascript" src="js/plugins/jquery.jqtweet.js"></script>
<script type="text/javascript" src="js/plugins/jquery.cookie.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="js/plugins/prettify/prettify.js"></script>
<script type="text/javascript" src="js/plugins/bootstrapSwitch.js"></script>
<script type="text/javascript" src="js/plugins/mfupload.js"></script>

<script type="text/javascript" src="js/common.js"></script>

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
		
		
    </head>
    
    <body class="body-dashboard light-version">

            
    <div class="btn-toolbar btn-mobile-menus">
        <button class="btn btn-main-menu"></button>
        <button class="btn btn-user-menu"><i class="icon-logo"></i></button>
    </div>

    <div class="nav-fixed-left" style="visibility: hidden">
        <ul class="nav nav-side-menu">
            <li class="shadow-layer"></li>
            <li>
                <a href="dashboard.html" >
                    <i class="icon-photon home"></i>
                    <span class="nav-selection">Dashboard</span>
                                    </a>
            </li>
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon document_alt_stroke"></i>
                    <span class="nav-selection">Articles</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="create-new-articles.html">Create New Articles</a>
                        </li>
						<li>
                            <a href="new-articles.html">New Articles</a>
                        </li>
						<li>
                            <a href="scheduled-articles.html">Scheduled Articles</a>
                        </li>
                        <li>
                            <a href="published-articles.html">Published Article</a>
                        </li>
						<li>
                            <a href="saved-articles.html">My Drafts</a>
                        </li>
						<li>
                            <a href="feature-box-management.html">Feature Box Management</a>
                        </li>
						<li>
                            <a href="campaign-management.html">Campaign Management</a>
                        </li>
                        <li>
                            <a href="add-a-magazine-issue.html">Add A Magazine Issue</a>
                        </li>
                        <li>
                            <a href="tips.html">Tips</a>
                        </li>
                        <li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon comment_alt2_stroke"></i>
                    <span class="nav-selection">Tips &amp; Quotes</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="tips.html">Tips</a>
                        </li>
                        <li>
                            <a href="tip-tag.html">Tags</a>
						<li>
                            <a href="quotes.html">Quotes</a>
                        </li>
                        <li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
			<li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon mic"></i>
                    <span class="nav-selection">Debate</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="published-debates.html">Published Debate</a>
                        </li>
                        <li>
                            <a href="create-new-debate.html">Create New Debates</a>
                        </li>
						<li>
                            <a href="new-comments-for-debate.html">Debate Comments</a>
                        </li>
                        <li>
                            <a href="profanity-filter.html">Profanity Filter</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
			   <li>
			     <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon pen"></i>
                    <span class="nav-selection">Columnist and Guest Author
					</span>
                         <i class="icon-menu-arrow"></i> </a>
									<div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="add-edit-columnist.html">Add/Edit Columnist</a>
                        </li>
						<li>
                            <a href="add-new-guest-author.html">Add New Guest Author</a>
                        </li>
                        <li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
			
			 
			<li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon movie"></i>
                    <span class="nav-selection">Events</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="add-new-events.html">Add New Events</a>
                        </li>
                        <li>
                            <a href="published-events.html">Published Events</a>
                        </li>
						<li>
                            <a href="deleted-events.html">Deleted Events</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon steering_wheel"></i>
                    <span class="nav-selection">Quick Bytes</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="create-new-quickbyte.html">Create New Quick Byte</a>
                        </li>
                        <li>
                            <a href="published-quickbyte.html">Published Quick Bytes</a>
                        </li>
						<li>
                            <a href="deleted-quickbyte.html">Deleted Quick Bytes</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
			
			<li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon document_stroke"></i>
                    <span class="nav-selection">Sponsored Post</span>
                   <i class="icon-menu-arrow"></i>     </a>
                 <div class="sub-nav">
                 	<ul class="nav">
                        <li>
                        	<a href="create-new-sponsored-post.html">Create New Sponsored Post</a>
                        </li>
                        <li>
                            <a href="published-sponsored-posts.html">Published Sponsored Posts</a>
                        </li>
						<li>
                            <a href="deleted-sponsored-posts.html">Deleted Sponsored Posts</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
			
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon new_window"></i>
                    <span class="nav-selection">Photos</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="upload-new-album.html">Upload New Album</a>
                        </li>
                        <li>
                            <a href="published-album.html">Published Album</a>
                        </li>
						<li>
                            <a href="deleted-photos.html">Deleted Photos</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon play"></i>
                    <span class="nav-selection">Videos</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="upload-new-Video.html">Upload New Video</a>
                        </li>
                        <li>
                            <a href="published-Videos.html">Published Videos</a>
                        </li>
						<li>
                            <a href="deleted-Videos.html">Deleted Videos</a>
                        </li>
						<li>
                            <a href="featured-Videos.html">Featured Videos</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
			<li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon cog"></i>
                    <span class="nav-selection">Site Management</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="category-master.html">Category Master</a>
                        </li>
						<li>
                            <a href="location-master.html">Location Master</a>
                        </li>
						<li>
                            <a href="topic-master.html">Topic Master</a>
                        </li>
                        <li>
                            <a href="tags.html">Tags Master</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon cog"></i>
                    <span class="nav-selection">Rights Management</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="cms-rights.html">CMS Rights</a>
                        </li>
						<li>
                            <a href="#">Reports</a>
                        </li>
						<li>
                            <a href="#">Help</a>
                        </li>
                    </ul>
                </div>
            </li>
			<li>
                <a href="javascript:;" class="sub-nav-container">
                    <i class="icon-photon info"></i>
                    <span class="nav-selection">Help</span>
                    <i class="icon-menu-arrow"></i>                </a>
                <div class="sub-nav">
                    <ul class="nav">
                        <li>
                            <a href="#">FAQ</a>
                        </li>
						<li>
                            <a href="#">Sitemap</a>
                        </li>
                    </ul>
                </div>
            </li>
           
           
         
         
            <li class="nav-logout">
                <a href="index.html">
                    <i class="icon-photon key_stroke"></i><span class="nav-selection">Logout</span>
                </a>
            </li>
        </ul>
    </div>        

        <div class="container-fluid dashboard dashboard-title">
            <div class="row-fluid">
                <div class="span12">
                    <h1>
                        Dashboard
                    </h1>
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
<div class="widget-holder">
    <div class="widget-flipper">
        <div class="widget-area widget-general-stats widget-front">
            <div class="widget-head">
                Content Stats
                <div>
                    <a href="javascript:;" onClick="flipit(this)"><i class='icon-photon cog'></i></a>
                    <img src="images/photon/w_arrows@2x.png" alt="Arrows"/>
                </div>
            </div>
            <ul>
                <li>
                    <span>3,000</span>&nbsp;Articles Published
                    <div>
                        <span>+0.6%</span>
                        <img src="images/photon/w_arrow_green@2x.png" alt="Arrow up"/>
                    </div>
                </li>
                <li>
                    <span>2,000 </span>&nbsp;Quick Bytes Published
                    <div>
                        <span>+1.4%</span>
                        <img src="images/photon/w_arrow_green@2x.png" alt="Arrow up"/>
                    </div>
                </li>
                <li>
                    <span>1,000</span>&nbsp;Videos Published
                    <div>
                        <span>-0.9%</span>
                        <img src="images/photon/w_arrow_red@2x.png" alt="Arrow up"/>
                    </div>
                </li>
                <li>
                    <span>200</span>&nbsp;Photos Published
                    <div>
                        <span>+2.8%</span>
                        <img src="images/photon/w_arrow_green@2x.png" alt="Arrow up"/>
                    </div>
                </li>
                <li>
                    <span>100</span>&nbsp;Columns Published
                    <div>
                        <span>-0.6%</span>
                        <img src="images/photon/w_arrow_red@2x.png" alt="Arrow up"/>
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
                <div>
                    <a href="javascript:;" onClick="flipit(this)"><i class='icon-photon cog'></i></a>
                    <img src="images/photon/w_latest@2x.png" alt="latest users"/>
                </div>
            </div>
            <ul>
                <li  >
                    <div class="avatar-image"  >
                        <img src="images/photon/user1.jpg" alt="profile"/>
                    </div>
                    <span>Vaibhav Kumar</span> 
                    <!--<div>5 mins</div>-->
                </li>
                <li  >
                    <div class="avatar-image" >
                        <img src="images/photon/user2.jpg" alt="profile"/>
                    </div>
                    <span>Rajesh Partap</span> 
                  <!--  <div>17 mins</div>-->
                </li>
                <li >
                    <div class="avatar-image" >
                        <img src="images/photon/user3.jpg" alt="profile"/>
                    </div>
                    <span>Ravinder Ganesh Prasad</span> 
                   <!-- <div>25 mins</div>-->
                </li>
                <li >
                    <div class="avatar-image" >
                        <img src="images/photon/user4.jpg" alt="profile"/>
                    </div>
                    <span>Ramesh Gupta</span> 
                   <!-- <div>2 hrs</div>-->
                </li>
                <li onMouseOver="show5()" onMouseOut="hide5()">
                    <div class="avatar-image" >
                        <img src="images/photon/user5.jpg" alt="profile"/>
                    </div>
                    <span>Arun Kumar</span> 
                  <!--  <div>4 hrs</div>-->
                </li>
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

        <div class="dashboard-watermark"></div>

        <!--Modal Dialogs' HTML begin-->
        
        <div id="modal-add-quick-launch-custom" class="modal hide fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3>Create Quick Launch Item</h3>
            </div>
            <div class="modal-body">
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" for="Shortcut_Label">Shortcut Label</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input id="Shortcut_Label" type="text" placeholder="Enter the name of your shortcut" name="inputFieldPlaceholder" />
                        </div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label" for="Shortcut_URL">Shortcut URL</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input id="Shortcut_URL" type="text" placeholder="Enter the address of your shortcut" name="inputFieldPlaceholder" />
                        </div>
                    </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span3">
                        <label class="control-label">Upload Icon</label>
                    </div>
                    <div class="span9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="input-append">
                                <div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview">Upload Image</span></div><span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" /></span><a href="javascript:;" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="alternative">Or select one of the predefined icons</p>
            </div>
            <div class="modal-body predefined-icons">
                <ul>
                    <li><img src="images/photon/icons/ql1@2x.png" alt="Predefined"></li>
                    <li><img src="images/photon/icons/ql2@2x.png" alt="Predefined"></li>
                    <li><img src="images/photon/icons/ql3@2x.png" alt="Predefined"></li>
                    <li><img src="images/photon/icons/ql4@2x.png" alt="Predefined"></li>
                    <li><img src="images/photon/icons/ql6@2x.png" alt="Predefined"></li>
                    <li><img src="images/photon/icons/ql7@2x.png" alt="Predefined"></li>
                    <li><img src="images/photon/icons/ql8@2x.png" alt="Predefined"></li>
                    <li><img src="images/photon/icons/ql9@2x.png" alt="Predefined"></li>
                    <li><img src="images/photon/icons/ql10@2x.png" alt="Predefined"></li>
                </ul>
            </div>

            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-primary" data-dismiss="modal">Create Item</a>
                <a href="javascript:;" class="btn" data-dismiss="modal">Cancel</a>
            </div>
        </div>
        <!--Modal Dialogs' HTML end-->
        </body>
</html>