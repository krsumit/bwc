<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BW Businessworld (Image croping)</title>
  <link rel="shortcut icon" src="{{ asset('images/favicon.ico') }}" />
  <link rel="apple-touch-icon" src="{{ asset('images/iosicon.png') }}" />
    
  <!-- Scripts -->
  <script src="{{ asset('js/cropper/jquery.min.js')}}"></script>
  <script src="{{ asset('js/cropper/jquery.cropit.js')}}"></script>
 
    </head>
    
    <body class="body-dashboard light-version">
    
   <style>
       .image-editor{width:80%; margin:0 auto; text-align: center;}
       
       
       
      .cropit-preview {
        background-color: #f8f8f8;
        background-size: cover;
        border: 1px solid #ccc;
        width: {{$sizeArray[0]}}px;
        height: {{$sizeArray[1]}}px;
        margin:10px auto;
      }
      
      /*.image-size-label{width:auto;  margin:10px; border:1px solid #000; border-radius: 100px; background:#ccc;}*/

    
    </style>
  </head>
  <body>
    
      <div class="image-editor" >
        <input type="file" class="cropit-image-input">
        
        <div class="cropit-preview"></div>
        
        <div class="image-size-label">
            <span style="">Resize image :</span> 
             <input type="range" class="cropit-image-zoom-input" >
        </div>
        
        <div style="margin:10px;">
            <button class="icon icon-rotate-left rotate-ccw-btn rotate-ccw">Rotate counterclockwise</button>
            <button class="rotate-cw">Rotate clockwise</button>
        </div>    

        <button class="export">Export</button>
      </div>

    <script>
        //http://scottcheng.github.io/cropit/
      $(function() {
        $('.image-editor').cropit({
          imageState: {
            src: '{{ asset('css/cropper/bw.png') }}',
          },
          smallImage: 'allow',
          maxZoom: 2,
        });

        $('.rotate-cw').click(function() {
          $('.image-editor').cropit('rotateCW');
        });
        $('.rotate-ccw').click(function() {
          $('.image-editor').cropit('rotateCCW');
        });

        $('.export').click(function() {
          var imageData = $('.image-editor').cropit('export');
          window.open(imageData);
        });
      });
    </script>
  </body>

 
    </body>
</html>
