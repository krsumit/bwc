// FUNCTIONS FOR ARTICLES RELATED IMAGE
function searchRelated() {
var token = $('input[name=_token]');
        var selectedck = '';
        $('#related_image_error').remove();
        if ($('#related_image_search').val().trim() == '') {//alert(1);
$('#related_image_button').after('<div class="error noborder" id="related_image_error" >Please enter some text to search.</div>');
} else {
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
                if (respText.length > 0) {
        $.each(respText, function (key, imgdata) {
        selectedck = '';
                if ($("tr[title='" + imgdata.image_name + "']").length > 0) {
        selectedck = 'disabled="diabled" checked="checked"';
        }
        $('.relaed_image_box').append('<div class="related_image_element">\n\
<img  src="' + imgdata.image_url + '"/ style="width:90px; height:63px; float:left;" alt="' + imgdata.title + '">\n\
<input type="checkbox" ' + selectedck + ' class="inline_image" id="' + imgdata.image_name + '" title="' + imgdata.photo_by + '" name="relatedImages[]" value="' + imgdata.image_id + '"/ ><div class="related-img-tag-name">' + imgdata.tag_name + '</div></div>');
        });
                $('.related_action_button').removeClass('hide');
        } else {
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

function closeRelated() {
$('.relaed_image_box_outer').addClass('hide');
        $('.relaed_image_box').html('');
        $('.related_action_button').addClass('hide');
        }
function relatedImageSelected() {
if ($("input[name='relatedImages[]']:checked").length > 0) {
var list = '';
        $("input[name='relatedImages[]']:checked:enabled").each(function () {
//alert($(this).attr('title'));
//if($('#row'+$(this).val()).length==0){ 
if ($("tr[title='" + $(this).attr('id') + "']").length == 0) {
$(this).attr('disabled', true);
        list += '<tr id="row' + $(this).val() + '" title="' + $(this).attr('id') + '"><td><img src="' + $(this).siblings('img').attr('src') + '" alt="" /></td><td><div><small>Photo by</small><input type="text" name="rimage[' + $(this).val() + ']" value="' + $(this).attr('title') + '"/></div><div><small>Title</small><input type="text" name="rtitle[' + $(this).val() + ']" value="' + $(this).siblings('img').attr('alt') + '"/></idv></td><td class="center"><button type="button" onclick="removeRelated(\'' + $(this).val() + '\')" class="btn btn-mini btn-danger">Dump</button></td></tr>';
}
});
        // alert(list);
        if ($('.uploaded-image-list').length > 0) {
$('.uploaded-image-list tbody').prepend(list);
} else {
list = '<table class="table table-striped table-responsive uploaded-image-list" id="tableSortableResMed"><thead class="cf sorthead"><tr><th>Image</th><th>Photo By</th><th>Action</th></tr></thead><tbody>' + list + '</tbody></table>';
        $('.related_image').after(list);
        //alert(2);
}

// $('.relaed_image_box_outer').addClass('hide');
// $('.relaed_image_box').html('');
// $('.related_action_button').addClass('hide');
} else {

alert('Please select at-least one image')
}

}
function removeRelated(id) {
$('#row' + id).remove();
        if ($("input[type='checkbox'][value='" + id + "']").length > 0)
        $("input[type='checkbox'][value='" + id + "']").removeAttr('disabled checked');
        if ($('.uploaded-image-list tr').length == 1)
        $('.uploaded-image-list').remove();
        }


function cropImage(url) {
window.open(url, "popupWindow", "width=1200,height=800,scrollbars=yes");
        }

function editImageDetail(id, type) {
if (type === undefined) {
type = "aritcle";
}

//alert('Edit image detail.'); 

BootstrapDialog.show({
title: 'Edit image detail',
        message: $('<div class="devest"></div>').load('/article/image/edit?id=' + id + "&type=" + type)
});
setTimeout(function(){
    var prepop=$('#prepop_tag').val();
$('body').find("#image_tags").tokenInput("/tags/getJson", {
        theme: "facebook",
        searchDelay: 300,
        minChars: 3,
        preventDuplicates: true,
        prePopulate:JSON.parse(prepop)
 });
}, 1000);

}
// FUNCTIONS FOR QUICKBYTE RELATED IMAGE

function searchRelatedImageQb(pageNo) {
var token = $('input[name=_token]');
        var selectedck = '';
        var sel_values = $('input[name="searchFor[]"]:checked').map(function () {
return $(this).val();
}).get();

       
        $('#related_image_error').remove();
        if ($('#related_image_search').val().trim() == '' || sel_values.length==0 ){//alert(1);
$('#related_image_button').after('<div class="error noborder" id="related_image_error" >Please enter some text and select at-least one option to search.</div>');
} else{
document.getElementById("js-paging").innerHTML ='';  
$.ajax({
type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url: '/quickbyte/relatedimage',
        data: {
        search_key: $('#related_image_search').val(),
        selected_values:sel_values,
        page:pageNo
        },
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
                
                if (respText.images.length > 0) {
        $.each(respText.images, function (key, imgdata) {
            //alert(imgdata);
        selectedck = '';
                if ($("tr[title='" + imgdata.image_name + "']").length > 0) {
        selectedck = 'disabled="diabled" checked="checked"';
        }
        $('.relaed_image_box').append('<div class="related_image_element">\n\
<img  src="' + imgdata.image_url + '"/ style="width:90px; height:63px; float:left;" image-desc="'+imgdata.description+'" alt="' + imgdata.title + '">\n\
<input type="checkbox" ' + selectedck + ' class="inline_image"  id="' + imgdata.image_name + '" title="' + imgdata.photo_by + '" name="relatedImages[]" value="' + imgdata.image_id + '"/ ><div class="related-img-tag-name">' + imgdata.tag_name + '</div></div>');
        });
                $('.related_action_button').removeClass('hide');
                pagination(pageNo,respText.info.total_pages);
        } else {
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


function closeRelatedImageQb(){
$('.relaed_image_box_outer').addClass('hide');
        $('.relaed_image_box').html('');
        $('.related_action_button').addClass('hide');
        }

function relatedImageSelectedQb() {
if ($("input[name='relatedImages[]']:checked").length > 0) {
var list = '';
var ids = [];
$("input[name='relatedImages[]']:checked:enabled").each(function () {
//alert($(this).attr('title'));
//if($('#row'+$(this).val()).length==0){ 
if ($("tr[title='" + $(this).attr('id') + "']").length == 0) {
$(this).attr('disabled', true);
        list += '<tr id="row' + $(this).val() + '" title="' + $(this).attr('id') + '"><td><img src="' + $(this).siblings('img').attr('src') + '" alt="" /></td><td><div><small>Title</small><input type="text" name="rtitle[' + $(this).val() + ']" value="' + $(this).siblings('img').attr('alt') + '"/></idv><div><small>Description</small><textarea name="rdesc['+$(this).val()+']">'+$(this).siblings('img').attr('image-desc')+'</textarea></div><div><small>Photo by</small><input type="text" name="rimage[' + $(this).val() + ']" value="' + $(this).attr('title') + '"/></div><div><small>Image Tags</small><input type="text" name="rimage_tags[' + $(this).val() + ']" /></div></td><td class="center"><button type="button" onclick="removeRelatedQb(\'' + $(this).val() + '\')" class="btn btn-mini btn-danger">Dump</button></td></tr>';
 ids.push($(this).val());
}


});
        // alert(list); <input type="text" class="valid" name="Taglist" id="Taglist"/>
if ($('.uploaded-image-list').length > 0) {
    $('.uploaded-image-list tbody').prepend(list);
} else {
    list = '<table class="table table-striped table-responsive uploaded-image-list" id="tableSortableResMed"><thead class="cf sorthead"><tr><th>Image</th><th>Photo By</th><th>Action</th></tr></thead><tbody>' + list + '</tbody></table>';
    $('.related_image').after(list);
        //alert(2);
}
$.each( ids, function( key, value ) {
    $('body').find("input[name='rimage_tags["+value+"]']").tokenInput("/tags/getJson", {
            theme: "facebook",
            searchDelay: 300,
            minChars: 3,
            preventDuplicates: true,
    });
});
} else {

alert('Please select at-least one image')
}
}

function removeRelatedQb(id) {
$('#row' + id).remove();
        if ($("input[type='checkbox'][value='" + id + "']").length > 0)
        $("input[type='checkbox'][value='" + id + "']").removeAttr('disabled checked');
        if ($('.uploaded-image-list tr').length == 1)
        $('.uploaded-image-list').remove();
        }

function editImageDetailQb(id, type) {
if (type === undefined) {
type = "quickbyte";
}
//alert('Edit image detail.'); 
BootstrapDialog.show({
title: 'Edit image detail',
        message: $('<div class="devest"></div>').load('/quickbyte/image/edit?id=' + id + "&type=" + type)
});
}
        
function pagination(c, m) {
    var current = c,
            last = m,
            delta = 2,
            left = current - delta,
            right = current + delta + 1,
            range = [],
            rangeWithDots = [],
            pageStr = '',
            pageStrDots = '',
            l;
            for (let i = 1; i <= last; i++) {
                if (i == 1 || i == last || i >= left && i < right) {
                    range.push(i);
                    pageStr += '<li><a href="javascript:;" onClick="searchRelatedImageQb('+i+')">' + i + '</a></li>';
                }
            }

            for (let i of range) {
                if (l) {
                if (i - l === 2) {
                temp = l + 1;
                        rangeWithDots.push(temp);
                        pageStrDots += '<li><a href="javascript:;" onClick="searchRelatedImageQb('+temp+')">' + temp + '</a></li>';
                        } else if (i - l !== 1) {
                rangeWithDots.push('...');
                        pageStrDots += '<li><a href="javascript:;">...</a></li>';
                        }
                }

                pageStrDots += '<li><a href="javascript:;" onClick="searchRelatedImageQb('+i+')">' + i + '</a></li>';
                        rangeWithDots.push(i);
                        l = i;
            }

            //document.getElementById("js-paging").innerHTML = rangeWithDots;
            document.getElementById("js-paging").innerHTML = pageStrDots;
            //return rangeWithDots;
}

