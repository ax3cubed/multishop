<?php 
$script = <<<EOJS
$("#$formId .btn-save").click(function(){updateadvancedsettings("$formId");});
$("#$formId .btn-send-greeting-text").click(function(){sendgreetingtext("$formId");});
$("#$formId .btn-send-get-started-button").click(function(){sendgetstartedbutton("$formId");});
$("#$formId .btn-send-persistent-menu").click(function(){sendpersistentmenu("$formId");});
EOJS;
Helper::registerJs($script,$formId.'_script');

