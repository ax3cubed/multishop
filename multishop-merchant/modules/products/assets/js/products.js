function submitproduct(model) {
    $('.page-loader').show();
    $('#'+model+'_image').val($('.upload-form').find('input[type=radio]:checked').val());
    $('#product-form').submit();
}
function getattroption(type){
   getchildform('/products/attribute/optionformget/type/'+type);
}
function removeattroption(key){
   removechildform('/products/attribute/optionformdel',key);
}
function removeallattroptions(){
   removeallchildforms('/products/attribute/optionformdel');
}
function getsubcategory(cid){
   getchildform('/product/category/subcategoryformget?cid='+cid);
}
function removesubcategory(key){
   removechildform('/product/category/subcategoryformdel',key);
}

function getshipping(ids){
   if (ids!=null){
       $.get('/products/management/shippingformget/pid/'+$('#ProductForm_id').val()+'/ids/'+ids, function(data) {
          $('#child_table').addClass('items');
          $('#child_table thead').show();
          $('#child_table tbody').html(data.form);
       })
       .error(function(XHR) { error(XHR); }); 
   }
   else{
        $('#child_table').removeClass('items');
        $('#child_table thead').hide();
        $('#child_table tbody').html('');
   }
}
/*below is used for autocomplete; could remove if no longer wanted*/
function getattrform(col,val){
   clearerror();
   $(".page-loader").show();
   $.get('/products/attribute/attrformget/col/'+col+'/val/'+val+'/pid/'+$('#ProductAttribute_product_id').val(), function(data) {
        if (data.status=='success'){
            $('.form').html(data.form);
            refreshattrform(data);
        }
        else 
            errormsg(data.message);
        $(".page-loader").hide();
   })
   .error(function(XHR) { error(XHR);  }); 
}
function refreshchosen(){
    $('.chzn-select').chosen();
}
function refreshattrform(data){
    refreshchosen();
    $('#ProductAttribute_name').val(data.name);
    $('#ProductAttribute_name').autocomplete({'minLength':'1','select':function(){getattrform('name',$(this).val())},'source':data.allowed.name});
    $('#ProductAttribute_code').val(data.code);
    $('#ProductAttribute_code').autocomplete({'minLength':'1','select':function(){getattrform('code',$(this).val())},'source':data.allowed.code});
    $('#ProductAttributeButton').button().click(function(){$('#product-attribute-form').submit();});
    $(".del-button:last").show();
}

function previewproductimport(previewcontent) {
    $('#product_import_page .main-view .body').append(previewcontent);
    var hasError = false;
    $.each($('td.error'), function(idx, obj) {
        if (!(obj.innerHTML==$('.products-import-preview').data('okIcon')||obj.innerHTML==$('.products-import-preview').data('errorIcon'))){
            $(this).addClass('message');   
            $(this).attr('colspan',$('.products-import-preview').data('totalColumns'));   
            $(this).parent().find('td:not(:first)').remove();/*remove all other td*/           
            hasError = true;
        }
    });
    if (hasError){
        $('#flash-bar').html($('.products-import-preview').data('flash'));
        $('#importButton').button({'disabled':true});
    }
    else {
        $('#importButton').button().click(function(){confirmproductimport($('.products-import-preview').data('file'));});
    }
}

function afterdestroyimportfile() {
    $('.product-preview-container').remove();
    $('#flash-bar').html('');
}

function confirmproductimport(file) {
    $(".page-loader").show();
    $.get('/product/management/importupload?process=1&f='+file, function(data) {
        if (data.status=='success'){
            window.location.href = data.redirect;
        }
        else {
            $('#flash-bar').html(data.message);
            scrolltop();
        }
        $(".page-loader").hide();
    })
    .error(function(XHR) { 
        error(XHR); 
        $(".page-loader").hide();
    });
}