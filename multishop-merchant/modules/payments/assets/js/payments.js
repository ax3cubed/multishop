function getmethodform(id){
    clearerror();
    $(".page-loader").show();
    $('.grid-view').html('');
    $.get('/payments/management/methodformget/id/'+id, function(data) {
        if (data.status=='success'){
            $('.grid-view').html(data.form);
            if ($('.chzn-select-method').length>0){
                $('.chzn-select-method').chosen();
                $('.chzn-search').hide();
            }
            if ($('.chzn-select-apimode').length>0){
                $('.chzn-select-apimode').chosen();
                $('.chzn-search').hide();
            }
            if ($('.chzn-select-mode').length>0){
                $('.chzn-select-mode').chosen();
                $('.chzn-search').hide();
                $(document).ready(function(){
                    $('#OfflinePaymentForm_method').change(function() {
                      if ($('#OfflinePaymentForm_method').val().length > 0){
                            gettemplate($('#OfflinePaymentForm_method').val());
                      }
                    });    
                });                
            }
            if ($('.subform .page-tab-container .page-tab').length>0){
                $('.subform .page-tab-container .page-tab').yiitab();
            }
            $('#paymentButton').button([]).click(function(){submitform('payment-method-form');});
        }
        else 
            errormsg(data.message);
        $(".page-loader").hide();
    })
    .error(function(XHR) { error(XHR);  }); 
}
function gettemplate(method){
    clearerror();
    $(".page-loader").show();
    $.get('/payments/management/templateget/method/'+method, function(template) {
        $.each(template, function(locale,data) {
            $('#OfflinePaymentForm_name_'+locale).val(data.name);
            $('#OfflinePaymentForm_instructions_'+locale).val(data.instructions);
        });
        $(".page-loader").hide();
    })
    .error(function(XHR) { error(XHR);  }); 
}