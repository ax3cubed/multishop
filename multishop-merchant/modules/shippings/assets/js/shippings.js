function scrolltotier(){
    var new_position = $('#ShippingForm_type').offset();
    window.scrollTo(new_position.left,new_position.top);
}
function getshippingtier(base){
    getchildform('/shippings/management/tierformget/sid/'+$('#ShippingForm_shop_id').val()+'/base/'+base);
    scrolltotier();
    $('#row_tiers').show();
}
function removeshippingtier(key){
   removechildform('/shippings/management/tierformdel',key);
}
function removeallshippingtiers(){
   removeallchildforms('/shippings/management/tierformdel');
}
function resetshippingtier(){
    $('#row_tiers').hide();
    $('#child_table').removeClass('items');
    removeallshippingtiers();
}

function showselectbase(){
    $('.chzn-select-base').chosen();
    $('.chzn-search').hide();
}
function showrate(){
    $('#ShippingForm_rate').val('');
    $('#column_rate').show();
    $('#column_tierBase').hide();
}
function hiderate(){
    $('#ShippingForm_rate').val(0);
    $('#column_rate').hide();
    $('#column_tierBase').hide();
}
function showbase(){
    $('#ShippingForm_rate').val('');
    $('#column_rate').hide();
    $('#column_tierBase').show();
    showselectbase();
}