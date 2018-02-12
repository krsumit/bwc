<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
		<title>@yield('title')</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <link rel="shortcut icon" src="{{ asset('images/favicon.ico') }}" />
        <link rel="apple-touch-icon" src="{{ asset('images/iosicon.png') }}" />
<!--    DEVELOPMENT LESS -->
<!--          <link rel="stylesheet/less" href="css/photon.less" media="all" />
        <link rel="stylesheet/less" href="css/photon-responsive.less" media="all" /> -->
<!--    PRODUCTION CSS -->
        <link rel="stylesheet" type="text/css" href="{{ elixir('output/final.css') }}" media="all" />
		
<!--[if IE]>
        <link rel="stylesheet" src="{{ asset('css/css_compiled/ie-only-min.css') }}" media="all" />

<![endif]-->

<!--[if lt IE 9]>
        <link rel="stylesheet" type="text/css" src="{{ asset('css/css_compiled/ie8-only-min.css') }}" />
		<script type="text/javascript" src="{{ asset('output/finalIE9.js') }}"></script>
<![endif]-->

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>

<script type="text/javascript" src="{{ elixir('output/login-one.js') }}"></script>

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
                            <a href="deleted-articles.html">Deleted Articles</a>
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
<!--
	 <div class="container-fluid dashboard dashboard-title">
            <div class="row-fluid">
                <div class="span12">
                    <h1>
                        Dashboard
                    </h1>
                </div>
            </div>
        </div>
-->
<!--
        @section('sidebar')
            
        @show
-->
<div class="container-fluid dashboard dashboard-title">
            <div class="row-fluid">
                <div class="span12">
                    <div class="container">
				    @yield('content')
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
                    <li><img href="{{ asset('images/photon/icons/ql1@2x.png') }}" alt="Predefined"></li>
                    <li><img href="{{ asset('images/photon/icons/ql2@2x.png') }}" alt="Predefined"></li>
                    <li><img href="{{ asset('images/photon/icons/ql3@2x.png') }}" alt="Predefined"></li>
                    <li><img href="{{ asset('images/photon/icons/ql4@2x.png') }}" alt="Predefined"></li>
                    <li><img href="{{ asset('images/photon/icons/ql6@2x.png') }}" alt="Predefined"></li>
                    <li><img href="{{ asset('images/photon/icons/ql7@2x.png') }}" alt="Predefined"></li>
                    <li><img href="{{ asset('images/photon/icons/ql8@2x.png') }}" alt="Predefined"></li>
                    <li><img href="{{ asset('images/photon/icons/ql9@2x.png') }}" alt="Predefined"></li>
                    <li><img href="{{ asset('images/photon/icons/ql10@2x.png') }}" alt="Predefined"></li>
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