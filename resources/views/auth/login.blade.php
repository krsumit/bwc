<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>BW CMS | Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
        <link rel="apple-touch-icon" href="iosicon.png" />
        <!--    DEVELOPMENT LESS -->
        <!--          <link rel="stylesheet/less" href="css/photon.less" media="all" />
                <link rel="stylesheet/less" href="css/photon-responsive.less" media="all" /> -->
        <!--    PRODUCTION CSS -->
        <!--<link rel="stylesheet" href="{{ asset('output/final.css') }}" rel="stylesheet" media="all" />-->
        <link rel="stylesheet" href="{{ elixir('output/final.css') }}" rel="stylesheet" media="all" />
        <!--        <link rel="stylesheet" href="{{ asset('css/css/photon-pt2.css') }}" rel="stylesheet" media="all" />
                <link rel="stylesheet" href="{{ asset('css/css/photon-responsive.css') }}" rel="stylesheet  " media="all" />
        
        -->

        <!--[if IE]>
                <link rel="stylesheet" type="text/css" src="{{ asset('css/css_compiled/ie-only-min.css') }}" />     
        
        <![endif]-->

        <!--[if lt IE 9]>
                <link rel="stylesheet" type="text/css" src="{{ asset('css/css_compiled/ie8-only-min.css') }}" />
                <script type="text/javascript" src="{{ asset('output/finalIE9.js') }}"></script>
                
        <![endif]-->


        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>

        <link type="text/javascript" href="{{ elixir('output/login-one.js') }}" />
        <script type="text/javascript" src="{{ asset('js/common.js') }}"></script>
        <!---->
    </head>

    <body class="body-login light-version">

        <div class="container-login">
            <div class="form-centering-wrapper">
                <div class="form-window-login">


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


                    <div class="form-window-login-logo">
                        <div class="login-logo">
                            <img src="{{ asset('images/photon/BW-logo.png') }}" alt="Photon UI" style="height:auto !important"/>
                        </div>
                        <h2 class="login-title">Welcome to BW CMS</h2>

                        <div class="panel-body">
                            @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="login-input-area">
                                <form method="POST" action="{{url('auth/login')}}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    {!! csrf_field() !!}
                                    <span class="help-block">Login With Your Administrater Account</span>
                                    <input type="text" name="email" class="col-md-4 control-label" placeholder="Email">
                                    <input type="password" name="password" class="col-md-4 control-label" placeholder="Password">
                                    <button type="submit" class="btn btn-large btn-success btn-login">Login</button>

                                </form>
                                <a href="#" class="forgot-pass">Forgot Your Password?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
