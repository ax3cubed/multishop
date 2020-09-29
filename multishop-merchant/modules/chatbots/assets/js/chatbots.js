function initchatbotpostfields()
{
    /* select any current APP_CSRF_TOKEN*/
    return {
        'APP_CSRF_TOKEN':$('input[name="APP_CSRF_TOKEN"]').val(),
    };
}

function updatepluginsettings(modal)
{
    sendupdatesetting(modal,'ChatbotPluginForm','chatbots/plugin/update');
}

function updatesupportsettings(modal)
{
    sendupdatesetting(modal,'ChatbotSupportForm','chatbots/support/update');
}

function updateadvancedsettings(modal)
{
    sendupdatesetting(modal,'ChatbotAdvancedForm','chatbots/advancedSettings/update');
}

function sendupdatesetting(modal,form,route)
{
    var postFields = initchatbotpostfields()
    
    $('#'+modal+' [name^="'+form+'"]').each(function(index) {
        postFields[$(this).attr('name')] = $(this).val();
        if ($(this).attr('type')=='checkbox'){
            postFields[$(this).attr('name')] = $(this).prop('checked') ? '1' : '0';
        }
    });
    //console.log('postFields',JSON.stringify(postFields));
    
    $.post('/'+route,postFields, function(data) {
        $('#'+modal+' .form .message').html(data.flash);
    })
    .error(function(XHR) { error(XHR); });

}

function sendgreetingtext(modal)
{
    sendadvancedsetting(modal,'sendGreetingText');
}

function sendgetstartedbutton(modal)
{
    sendadvancedsetting(modal,'sendGetStartedButton');
}

function sendpersistentmenu(modal)
{
    sendadvancedsetting(modal,'sendPersistentMenu');
}

function sendadvancedsetting(modal,setting)
{
    var postFields = initchatbotpostfields();
    postFields['ChatbotAdvancedForm[clientId]'] = $('#ChatbotAdvancedForm_clientId').val();
    if (setting=='sendGreetingText'){
        postFields['ChatbotAdvancedForm[greetingText]'] = $('#ChatbotAdvancedForm_greetingText').val();
    }
    
    $.post('/chatbots/advancedSettings/'+setting,postFields, function(data) {
        $('#'+modal+' .form .message').html(data.flash);
        $('#'+modal+' .form .'+data.last_sent_field+' .last-sent-time').text(data.last_sent_time);
    })
    .error(function(XHR) { error(XHR); });

}