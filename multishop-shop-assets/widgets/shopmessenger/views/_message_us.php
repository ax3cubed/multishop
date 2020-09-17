<?php
if ($this->welcomeView!=null)
    $this->render($this->welcomeView);

$this->widget('common.extensions.facebook.messenger.MessageUs',[
    'appId'=>$appId,
    'pageId'=>$pageId,
]);
