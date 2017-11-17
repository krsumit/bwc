// function to search related image
function searchRelated() {
    var token = $('input[name=_token]');
    var selectedck='';
    $('#related_image_error').remove();
    if( $('#related_image_search').val().trim()==''){//alert(1);
        $('#related_image_button').after('<div class="error noborder" id="related_image_error" >Please enter some text to search.</div>');
    }else{
    $.ajax({
        type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url: '/article/relatedimage',
        data: {search_key: $('#related_image_search').val()},
        dataType: 'json', // what type of data do we expect back from the server
        beforeSend: function (data) {
            $('.relaed_image_box').html('');
            $('.relaed_image_box_outer').removeClass('hide');
            $('.loader-img-related-content').removeClass('hide');
            $('.related_action_button').addClass('hide');

            // Before sending request something can be done here
        },
        success: function (respText) {
            $('.related_image_no_data_found').remove();
            $('.related_image_element').remove();
            // If request processed successfully
            //alert(respText.length);
            if(respText.length>0){
            $.each(respText,function(key,imgdata){ 
                selectedck='';
                if($("tr[title='"+imgdata.image_name+"']").length>0){
                    selectedck='disabled="diabled" checked="checked"';
                }
                $('.relaed_image_box').append('<div class="related_image_element">\n\
<img  src="'+imgdata.image_url+'"/ style="width:90px; height:63px; float:left;" alt="'+imgdata.title+'">\n\
<input type="checkbox" '+selectedck+' class="inline_image" id="'+imgdata.image_name+'" title="'+imgdata.photo_by+'" name="relatedImages[]" value="'+imgdata.image_id+'"/ ><div class="related-img-tag-name">'+imgdata.tag_name+'</div></div>');
               
            });
            $('.related_action_button').removeClass('hide');
            }else{
                $('.relaed_image_box').append('<p class="related_image_no_data_found">No match found, search with different set of words.</p>')
            }
        },
        error: function (jqXHR, textStatus) {
            // If error come after request , Can be handled here
        },
        complete: function (data) {
            $('.loader-img-related-content').addClass('hide');
            // After completion something can be done here
        },
        headers: {
            'X-CSRF-TOKEN': token.val()
        }
    })
    }
}

function closeRelated(){
    $('.relaed_image_box_outer').addClass('hide');
    $('.relaed_image_box').html('');
    $('.related_action_button').addClass('hide');
   
}
function relatedImageSelected(){
  if($("input[name='relatedImages[]']:checked").length>0){  
    var list='';
   $("input[name='relatedImages[]']:checked:enabled").each(function(){
       //alert($(this).attr('title'));
     //if($('#row'+$(this).val()).length==0){ 
     if($("tr[title='"+$(this).attr('id')+"']").length==0){
        $(this).attr('disabled', true);
        list+='<tr id="row'+$(this).val()+'" title="'+$(this).attr('id')+'"><td><img src="'+$(this).siblings('img').attr('src')+'" alt="" /></td><td><div><small>Photo by</small><input type="text" name="rimage['+$(this).val()+']" value="'+$(this).attr('title')+'"/></div><div><small>Title</small><input type="text" name="rtitle['+$(this).val()+']" value="'+$(this).siblings('img').attr('alt')+'"/></idv></td><td class="center"><button type="button" onclick="removeRelated(\''+$(this).val()+'\')" class="btn btn-mini btn-danger">Dump</button></td></tr>';
    } 
   });
  // alert(list);
    if($('.uploaded-image-list').length>0){
           $('.uploaded-image-list tbody').prepend(list);
       }else{
           list='<table class="table table-striped table-responsive uploaded-image-list" id="tableSortableResMed"><thead class="cf sorthead"><tr><th>Image</th><th>Photo By</th><th>Action</th></tr></thead><tbody>'+list+'</tbody></table>';
           $('.related_image').after(list);
           //alert(2);
       }
          
   // $('.relaed_image_box_outer').addClass('hide');
   // $('.relaed_image_box').html('');
   // $('.related_action_button').addClass('hide');
   }else{
       
       alert('Please select at-least one image')
   }   
    
}
function removeRelated(id){
    $('#row'+id).remove();
    if($("input[type='checkbox'][value='"+id+"']").length>0)
        $("input[type='checkbox'][value='"+id+"']").removeAttr('disabled checked');
    if($('.uploaded-image-list tr').length==1)
        $('.uploaded-image-list').remove();
    
}


function cropImage(url){
    window.open(url, "popupWindow", "width=1200,height=800,scrollbars=yes");
}

function editImageDetail(id,type){
    if(type=== undefined){
        type="aritcle";
    }
    
    BootstrapDialog.show({
                    title: 'Edit image detail',
                    message: $('<div class="devest"></div>').load('/article/image/edit?id='+id+"&type="+type)
                });
                
}


/*


<table class="table table-striped table-responsive uploaded-image-list" id="tableSortableResMed">
                                    <thead class="cf sorthead">
                                        <tr>
                                            <th>Image</th>
                                            <th>Photo By</th>
  <!--                                          <th>Source</th>
                                            <th>Source URL</th>-->
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($photos as $photo)
                                        <tr id="row{{$photo->photo_id}}">
                                            <td>
                                                <img src="{{ config('constants.awsbaseurl').config('constants.awarticleimagethumbtdir').$photo->photopath}}" alt="article" />
                                            </td>
                                            <td>{{$photo->photo_by}}</td>
<!--                                            <td>{{ $photo->title }}</td>-->
                                    <input type="hidden" name="deleteImagel" id="{{ $photo->photo_id }}">
<!--                                    <td class="center">{{ $photo->source }}</td>
                                    <td class="center">{{ $photo->source_url }}</td>-->
                                    <td class="center"><button type="button" onclick="$(this).MessageBox({{ $photo->photo_id }})" name="{{ $photo->photo_id }}" id="deleteImage" class="btn btn-mini btn-danger">Dump</button><img  src="{{ asset('images/photon/preloader/76.gif') }}" alt="loader" style="width:20%; display:block; margin-left:15px;display:none;"/></td>
                                    </tr>
                                    @endforeach

                                    </tbody>
                                </table> */