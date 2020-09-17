$(window).scroll(function () {
    var scrollTop = $(window).scrollTop();
    if(scrollTop>50){
        $('.header-container').css({'background':'white','height':'50px'});
    }
    else{
        $('.header-container').css({'background':'none'});
    }
});
function comment(form) {
    $.post('/comments/management/create', $('#'+form).serialize(), function(data) {
        if (data.status=='loginrequired'){
            signin();
        }
        else {
            $('.'+form+'.postcomment').html(data.form);
            if (data.status=='success')
                $('.'+form+'.comments .items').append(data.comment);
            $('.comment-total-message').html(data.total);
            $('.'+form+' .comment-total').show();
            $('.summary').show();
            $('.comment-button').button([]).click(function(){comment($(this).attr('form'));});
        }
    })
    .error(function(XHR) { 
        alert(XHR.status+' '+XHR.statusText+'\r\n' + XHR.responseText);
    });
}
function prevdata(model,route) {
    $('.prevlink-'+model+' .page-loader .text').css({background:'white',width:0});
    $('#'+model+'_loader').show();
    $.post(route, $('#prev_'+model+'_form').serialize(), function(data) {
        if (data.prevlink=='lastpage' || data.prevlink=='onepage')
            $('.prevlink-'+model).html('');
        else
            $('.prevlink-'+model).html(data.prevlink);
        $('.prevdata-'+model).prepend(data.prevdata);
    })
    .error(function(XHR) { 
        alert(XHR.status+' '+XHR.statusText+'\r\n' + XHR.responseText);
    });
}
function liketoggle(form){
    $.post('/likes/management/toggle', form.serialize(), function(data) {
        if (data.status=='loginrequired'){
            signin();
        }
        else {
            $('.like-'+data.type+'-button-'+data.target).html(data.button);
            $('.like-'+data.type+'-modal-button-'+data.target).html(data.button);
            $('.like-'+data.type+'-total-'+data.target).html(data.total_text);
            data.total>0?$('.like-total').show():$('.like-total').hide();
            $('.summary').show();
        }
    })
    .error(function(XHR) { 
        alert(XHR.status+' '+XHR.statusText+'\r\n' + XHR.responseText);
    });
}
function communitysearch() {
    $('#page_modal .page-loader').show();
    var url = '/community/portal/search/?q='+$('#community_q').val();
    window.location.href = url;
}