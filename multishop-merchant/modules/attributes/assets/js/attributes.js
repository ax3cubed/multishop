function getoption(controller,type){
   $(".page-loader").show();
   $(".del-button").hide();
   $.get('/'+controller+'/optionformget/type/'+type, function(data) {
        $('#options_table').addClass('items');
        $('#options_table thead').show();
        $('#options_table tbody').append(data.form);
        $(".del-button:last").show();
        $(".page-loader").hide();
        var tr = $('#options_table tbody tr:last-child').attr('id');
        $('#'+tr+' .page-tab').yiitab();
   })
   .error(function(XHR) { error(XHR); }); 
}
function removeoption(controller,key){
   $(".page-loader").show();
   $(".del-button").hide();
   $.get('/'+controller+'/optionformdel/key/'+key, function(data) {
        $(".page-loader").hide();
        if (data.status=='success'){
            $('#option_'+key).remove();
            $(".del-button:last").show();
            if (data.count==0){
                $('#options_table thead').remove();
                $('#options_table').removeClass('items');
            }
        }
        else
            alert(data.message);
   })
   .error(function(XHR) { error(XHR); }); 
}
function removealloptions(controller){
   $(".page-loader").show();
   $.get('/'+controller+'/optionformdel/key/all', function(data) {
        if (data.status=='success'){
            $('#options_table thead').remove();
            $('#options_table tbody').remove();
            $('#options_table').removeClass('items');
            $(".page-loader").hide();
        }
        else
            alert('Option cannot be deleted');
   })
   .error(function(XHR) { error(XHR); }); 
}



