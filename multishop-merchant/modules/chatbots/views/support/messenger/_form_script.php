<?php 
$script = <<<EOJS
$("#$formId .btn-save").click(function(){updatesupportsettings("$formId");});
EOJS;
Helper::registerJs($script,$formId.'_script');

