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
                                url: '/brand_models/image/update', // the url where we want to POST
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

              {!! Form::open(array('url'=>'brand-models/image/update','class'=> 'form-horizontal','id'=>'imageedit_form')) !!}
              <input type="hidden" name="photo_id" id="photo_id" value="{{$photo->id}}"/>
            
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
