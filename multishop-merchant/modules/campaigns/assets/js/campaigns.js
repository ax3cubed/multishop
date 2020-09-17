function renderfreeshippingfield(offertype,form){
    if (offertype=='S'){
        $('#'+form+'_offer_value').val(0);
        $('#campaign-form').append('<input type="hidden" id="'+form+'_offer_value" name="'+form+'[offer_value]" value="0">');
        $('#'+form+'_offer_value').attr({'disabled':true});
    }
    else{
        $('#'+form+'_offer_value[type=hidden]').remove();
        $('#'+form+'_offer_value').val('');
        $('#'+form+'_offer_value').attr({'disabled':false});
    }
}
function rendercampaignpromocodeform(){
    $('.chzn-select-shop').chosen();
    $('.chzn-select-offer').chosen();
    $('#CampaignPromocodeForm_offer_type_chzn .chzn-search').hide();
    $('#CampaignPromocodeForm_offer_type').change(function() {
        renderfreeshippingfield($('#CampaignPromocodeForm_offer_type').val(),'CampaignPromocodeForm');
    });
}
function rendercampaignsaleform(){
    $('.chzn-select-shop').chosen();
    $('.chzn-select-sale').chosen();
    $('#CampaignSaleForm_sale_type_chzn .chzn-search').hide();
    $('.chzn-select-offer').chosen();
    $('#CampaignSaleForm_offer_type_chzn .chzn-search').hide();
    $('#CampaignSaleForm_offer_type').change(function() {
        renderfreeshippingfield($('#CampaignSaleForm_offer_type').val(),'CampaignSaleForm');
    });
}
function rendercampaignbgaform(){
    $('.chzn-select-x').chosen();
    $('.chzn-select-x-qty').chosen();
    $('.chzn-select-y').chosen();
    $('.chzn-select-y-qty').chosen();
    $('.chzn-select-offer').chosen();
    $('.chzn-select-shop').chosen();
    $('#CampaignBgaForm_buy_x_qty_chzn .chzn-search').hide();
    $('#CampaignBgaForm_get_y_qty_chzn .chzn-search').hide();
    $('#CampaignBgaForm_offer_type_chzn .chzn-search').hide();
    $('#CampaignBgaForm_buy_x').change(function() {
        if (hasB())
            renderproduct($('#CampaignBgaForm_buy_x').val(),'ny');
    });
    $('#CampaignBgaForm_buy_x_qty').change(function() {
        renderoffer();/*since no get_y, render x product offer*/
    });
    $('#CampaignBgaForm_get_y').change(function() {
        if ($('#CampaignBgaForm_get_y').val()==''){
            $('#y_area').html('');
            $('#CampaignBgaForm_get_y_qty').val('').trigger("liszt:updated");
            $('.y-product-area').hide();
            renderoffer();/*since no get_y, render x product offer*/
        }
        else{
            if ($('#CampaignBgaForm_get_y_qty').val()=='')
                $('#CampaignBgaForm_get_y_qty').val(1).trigger("liszt:updated");
            renderproduct('nx',$('#CampaignBgaForm_get_y').val());
        }
    });
    $('#CampaignBgaForm_get_y_qty').change(function() {
        if ($('#CampaignBgaForm_get_y_qty').val()==''||
            $('#CampaignBgaForm_get_y').val()==''){
            /*if no get_y_qty, set get_y to blank product */
            $('.chzn-select-y').val('').trigger("liszt:updated");
            $('#y_area').html('');
            $('#y_offer').html('');
            $('.y-product-area').hide();
            renderoffer();/*since no get_y, render x product offer*/
        }
        else{
            renderproduct('nx',$('#CampaignBgaForm_get_y').val());
        }
    });
    $('#CampaignBgaForm_at_offer').change(function() {
        renderoffer();
    });
    $('#CampaignBgaForm_offer_type').change(function() {
        if ($('#CampaignBgaForm_offer_type').val()=='0')
            $('#CampaignBgaForm_at_offer').hide();
        else
            $('#CampaignBgaForm_at_offer').show();
        renderoffer();
    });
}
function renderproduct(x,y){
    if (x!='nx' && hasB())/*has buyX*/
        showproduct('x',x);
    if (y!='ny' && hasG())/*has getY*/
        showproduct('y',y);
}
function showproduct(area,p){
    $('.'+area+'-product-area').show();
    $('#'+area+'_loader').show();
    $('.offer_area').html('');
    $.get('/campaigns/bga/productinfoget/p/'+p, function(data) {
          $('#'+area+'_area').html(data);
          if ($('#CampaignBgaForm_offer_type').val()!='')
              renderoffer();
          $('#'+area+'_loader').hide();
    })
    .error(function(XHR) { error(XHR); }); 
}   
function renderoffer(){
    if (hasB()||hasG()){
        var area = hasG()?'y':'x';
        $('#'+area+'_loader').show();
        $('.offer_area').html('');
        var p = $('#CampaignBgaForm_'+(hasG()?'get_y':'buy_x')).val();
        var qty = $('#CampaignBgaForm_'+(hasG()?'get_y':'buy_x')+'_qty').val();
        var o = $('#CampaignBgaForm_at_offer').val();
        var ot = $('#CampaignBgaForm_offer_type').val();
        $.get('/campaigns/bga/offerinfoget/p/'+p+'/o/'+o+'/ot/'+ot+'/qty/'+qty, function(data) {
            $('#'+area+'_offer').html(data);
            $('#'+area+'_offer').show();
            $('#'+area+'_loader').hide();
        })
        .error(function(XHR) { error(XHR); }); 
    }
}   
function hasB(){
    return $('#CampaignBgaForm_buy_x').val()!='';
}
function hasG(){
    return $('#CampaignBgaForm_get_y').val()!=''&&$('#CampaignBgaForm_get_y_qty').val()!='';
}
function getshipping(ids){
   if (ids!=null){
       $.get('/campaigns/bga/shippingformget/pid/'+$('#CampaignBgaForm_id').val()+'/ids/'+ids, function(data) {
          $('#shipping_table').addClass('items');
          $('#shipping_table thead').show();
          $('#shipping_table tbody').html(data.form);
       })
       .error(function(XHR) { error(XHR); }); 
   }
   else{
        $('#shipping_table').removeClass('items');
        $('#shipping_table thead').hide();
        $('#shipping_table tbody').html('');
   }
}
