<?php 
$script = <<<EOJS
$("#$formId .btn-save").click(function(){updatepluginsettings("$formId");});
EOJS;
Helper::registerJs($script,$formId.'_script');

