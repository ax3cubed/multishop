<?php
$this->widget('common.widgets.spage.SPage', array(
    'id'=>'subscription_page',
    'flash' => $this->action->id,
    'heading' => [
        'name'=> Sii::t('sii','Subscription Suspended'),
    ],
    'body'=>$this->renderPartial('_suspend_message',['model'=>$model],true),
));
