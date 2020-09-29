<h4>
    <?php echo Sii::t('sii','Subscribe to us!');?>
</h4>
<p>
    <?php echo Sii::t('sii','You will get regular {updates} via Messenger.',['{updates}'=>$updates]);?>
</p>
<?php
$this->widget('common.extensions.facebook.messenger.SendToMessenger',[
    'appId'=>$appId,
    'pageId'=>$pageId,
    'dataRef'=>$dataRef,
]);
