@extends('layouts.sidebar')

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
		<link rel="stylesheet" type="text/css" href="{{ asset('css/token-input-facebook.css') }}" media="all" />
                <link rel="stylesheet" type="text/css" href="{{ asset('css/dev.css') }}" media="all" />
                <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.fileupload.css') }}" media="all" />
		
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

<script type="text/javascript" src="{{ asset('http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js') }}"></script>
	
<!-- <script type="text/javascript" src="{{ elixir('output/login-one.js') }}"></script> -->

<script type="text/javascript" src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap/bootstrap-dialog.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/modernizr.custom.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.pnotify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/less-1.3.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/xbreadcrumbs.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.maskedinput-1.3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.autotab-1.1b.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/charCount.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.textareaCounter.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/elrte.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/elrte.en.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/select2.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery-picklist.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.validate.file.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/additional-methods.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.metadata.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.mockjax.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.uniform.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.tagsinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.rating.pack.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/farbtastic.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.timeentry.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.jstree.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.mousewheel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.mCustomScrollbar.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.flot.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.flot.stack.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.flot.pie.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.flot.resize.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/raphael.2.1.0.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/justgage.1.0.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.qrcode.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.clock.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.countdown.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.jqtweet.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.cookie.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/bootstrap-fileupload.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/prettify/prettify.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/bootstrapSwitch.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/mfupload.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.tokeninput.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/custom.js') }}"></script>

        <!--<script>
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
        </script> -->

    </head>
    
    <body class="body-dashboard light-version">
    
    <div class="btn-toolbar btn-mobile-menus">
        <button class="btn btn-main-menu"></button>
        <button class="btn btn-user-menu"><i class="icon-logo"></i></button>
    </div>
        
        @section('sidebar')
            
        @show
    
          <div class="container">
				    @yield('content')
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
                    <li><img src="{{ asset('images/photon/icons/ql1@2x.png')}}" alt="Predefined"></li>
                    <li><img src="{{ asset('images/photon/icons/ql2@2x.png')}}" alt="Predefined"></li>
                    <li><img src="{{ asset('images/photon/icons/ql3@2x.png')}}" alt="Predefined"></li>
                    <li><img src="{{ asset('images/photon/icons/ql4@2x.png')}}" alt="Predefined"></li>
                    <li><img src="{{ asset('images/photon/icons/ql6@2x.png')}}" alt="Predefined"></li>
                    <li><img src="{{ asset('images/photon/icons/ql7@2x.png')}}" alt="Predefined"></li>
                    <li><img src="{{ asset('images/photon/icons/ql8@2x.png')}}" alt="Predefined"></li>
                    <li><img src="{{ asset('images/photon/icons/ql9@2x.png')}}" alt="Predefined"></li>
                    <li><img src="{{ asset('images/photon/icons/ql10@2x.png')}}" alt="Predefined"></li>
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
