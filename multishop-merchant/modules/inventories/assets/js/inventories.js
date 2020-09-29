function getskuform(pid){
   clearerror();
   $('#sku-table').html('');
   $(".page-loader").show();
   $.get('/inventories/management/skuformget/pid/'+pid, function(data) {
        if (data.status=='success'){
            $('.grid-view').html(data.form);
            $('.chzn-select-attr').chosen();
            $('.chzn-select-attr').change(function(){getskupartialform();});
        }
        else 
            errormsg(data.message);
        $(".page-loader").hide();
   })
   .error(function(XHR) { error(XHR);  }); 
}
function getskupartialform(){
   clearerror();
   $(".page-loader").show();
   $.post('/inventories/management/skupartialformget', $('#inventory-form').serialize(), function(data) {
        if (data.status=='success')
            $('#sku-table').html(data.form);
        else 
            errormsg(data.message);
        $(".page-loader").hide();
   })
   .error(function(XHR) { error(XHR);  }); 
}
function updatestock(adjust,manual){
    if (isNaN($('#InventoryForm_adjust').val())){
        alert('Please enter correct adjust number.');
        $('#Inventory_total').html($('#Inventory_currentTotal').text());
        $('#Inventory_available').html($('#Inventory_currentAvailable').text());
        return;
    }
    var newTotal = Number($('#Inventory_total').text())+Number(adjust);
    var newAvail = Number($('#Inventory_available').text())+Number(adjust);
    if (manual){/*get current total and avail instead*/
        newTotal = Number($('#Inventory_currentTotal').text())+Number(adjust);
        newAvail = Number($('#Inventory_currentAvailable').text())+Number(adjust);
    }
    if (newAvail<0){
        alert('Stock cannot go below zero.');
        return;
    }
    $('#Inventory_total').html(newTotal);
    $('#Inventory_available').html(newAvail);
    if (!manual)
        $('#InventoryForm_adjust').val(Number($('#InventoryForm_adjust').val())+Number(adjust));
}


