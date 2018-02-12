<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript">
    function closep(){
        var token = $('input[name=_token]');
        var photo_id=$('#photo_id').val();
        var formdata=$('#imageedit_form').serialize();
                    $.ajax({
                        type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                                url: '/article/image/update', // the url where we want to POST
                                data: {detail: formdata},
                                dataType: 'text', // what type of data do we expect back from the server
                                encode: true,
                                beforeSend  :function(data){
                                        $('#savebtn').hide();
                                        $('#savebtn').siblings('img').show();
                                },
                                
                                success: function (data) {
                                         $('#row_'+photo_id).html(data);
                                         //alert(1);
                                },
                                headers: {
                                'X-CSRF-TOKEN': token.val()
                                }
                        })
                        .done(function (data) {
                           // $('#savebtn').siblings('img').hide();
                            //$('#savebtn').show();
                        });
          
     $( "#closbutton").trigger("click");
    }
//Google plus ends here 
</script>
    </head>

    <body class="body-dashboard light-version">
        <div class="form-horizontal">
        <div class="container-fluid">

<!--            <div id="tags" class="form-legend">Image Detail</div>-->
            <!--Select Box with Filter Search begin-->

              {!! Form::open(array('url'=>'article/image/update','class'=> 'form-horizontal','id'=>'imageedit_form')) !!}
              <input type="hidden" name="photo_id" id="photo_id" value="{{$photo->photo_id}}"/>
            
             <div id="Multiple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="control-group row-fluid">    
                    <div class="span3">
                        <label class="control-label" for="multiFilter">&nbsp;&nbsp;Image Title</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="imagetitlep" style="outline: medium none;" id="imagetitlep" value="{{$photo->title}}"/>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($photo->owned_by=='quickbyte' || $photo->owned_by=='album')
            <div id="Multiple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="control-group row-fluid">    
                    <div class="span3">
                        <label class="control-label" for="multiFilter">&nbsp;&nbsp;Description</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <textarea type="text" name="descriptionp" style="outline: medium none;" id="descriptionp" >{{$photo->description}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if($photo->owned_by=='album')
            <div id="Multiple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="control-group row-fluid">    
                    <div class="span3">
                        <label class="control-label" for="multiFilter">&nbsp;&nbsp;Photo Source</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="photosourcep" style="outline: medium none;" id="photosourcep" value="{{$photo->source}}"/>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div id="Multiple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="control-group row-fluid">    
                    <div class="span3">
                        <label class="control-label" for="multiFilter">&nbsp;&nbsp;Source Url</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="text" name="sourceurlp" style="outline: medium none;" id="sourceurlp" value="{{$photo->source_url}}"/>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <div id="Multiple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="control-group row-fluid">    
                    <div class="span3">
                        <label class="control-label" for="multiFilter">&nbsp;&nbsp;Photo By</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            
                            <input type="text"  style="outline: medium none;" id="imagebyp" name="imagebyp" value="{{$photo->photo_by}}">
                        </div>
                    </div>
                </div>
            </div>  
            
           @if($photo->owned_by=='article')
            <div id="Multiple_Select_Box_with_Filter_Search" class="control-group row-fluid">
                <div class="control-group row-fluid">    
                    <div class="span3">
                        <label class="control-label" for="multiFilter">&nbsp;&nbsp;Use this image on social</label>
                    </div>
                    <div class="span9">
                        <div class="controls">
                            <input type="checkbox" name="social_image_popup" id="social_image_popup" value="{{$photo->photopath}}" @if($article->social_image==$photo->photopath) checked="checked" @endif />
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            
            <div class="control-group row-fluid" id="submitsection">
                <div class="span12 span-inset">
                        <button type="button" id="closbutton" class="btn btn-default" data-dismiss="modal" style="display:none;">Close</button>
                        <input type="button" id="savebtn" name="close" value="Save" onclick="closep()"/>
                        <img src="{{ asset('images/photon/preloader/76.gif')}}" alt="loader" style="width:5%; display:none;"/>

                </div>
                    
            </div>
          {!! Form::close() !!}

        </div>
        </div>    
    </body>
</html>
