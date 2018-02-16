var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
    
    mix.styles([
        
        'photon/normalize.css',
        'css/photon.css',
        'css/photon-pt2.css',
        'css/photon-responsive.css'
        
    ], 'public/output/final.css', 'public/css');

    
    mix.scripts([
        
        'bootstrap/bootstrap.min.js',
        'plugins/modernizr.custom.js',
        'plugins/jquery.pnotify.min.js',
        'plugins/less-1.3.1.min.js',
        'plugins/xbreadcrumbs.js',
        'plugins/jquery.maskedinput-1.3.min.js',
        'plugins/jquery.autotab-1.1b.js',
        'plugins/charCount.js',
        'plugins/jquery.textareaCounter.js',
        'plugins/elrte.min.js',
        'plugins/elrte.en.js',
        'plugins/select2.js',
        'plugins/jquery-picklist.min.js',
        'plugins/jquery.validate.min.js',
        'plugins/additional-methods.min.js',
        'plugins/jquery.form.js',
        'plugins/jquery.metadata.js',
        'plugins/jquery.mockjax.js',
        'plugins/jquery.uniform.min.js',
        'plugins/jquery.tagsinput.min.js',
        'plugins/jquery.rating.pack.js',
        'plugins/farbtastic.js',
        'plugins/jquery.timeentry.min.js',
        'plugins/jquery.dataTables.min.js',
        'plugins/jquery.jstree.js',
        'plugins/dataTables.bootstrap.js',
        'plugins/jquery.mousewheel.min.js',
        'plugins/jquery.mCustomScrollbar.js',
        'plugins/jquery.flot.js',
        'plugins/jquery.flot.stack.js',
        'plugins/jquery.flot.pie.js',
        'plugins/jquery.flot.resize.js',
        'plugins/raphael.2.1.0.min.js',
        'plugins/justgage.1.0.1.min.js',
        'plugins/jquery.qrcode.min.js',
        'plugins/jquery.clock.js',
        'plugins/jquery.countdown.js',
        'plugins/jquery.jqtweet.js',
        'plugins/jquery.cookie.js',
        'plugins/bootstrap-fileupload.min.js',
        'plugins/prettify/prettify.js',
        'plugins/bootstrapSwitch.js',
        'plugins/mfupload.js'
        
    ], 'public/output/login-one.js', 'public/js');
   
   mix.scripts([
        'jquery.iframe-transport.js',
        'jquery.fileupload.js',
        'jquery.fileupload-process.js',
        'jquery.fileupload-image.js',
        'jquery.fileupload-audio.js',
        'jquery.fileupload-video.js',
        'jquery.fileupload-validate.js',
        'jquery.fileupload-ui.js'
    ], 'public/output/fileuploadJS.js', 'public/js');
    
   mix.version([
         'public/output/final.css',
         'public/output/login-one.js',
         'public/output/fileuploadJS.js'
    ]);
    
   
   mix.scripts([
         'plugins/excanvas.js',
         'plugins/html5shiv.js',
         'plugins/respond.min.js',
         'plugins/fixFontIcons.js'
         
    ], 'public/output/finalIE9.js','public/js');
   
});
