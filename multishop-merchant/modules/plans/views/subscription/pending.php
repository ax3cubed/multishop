<?php
$this->widget('common.widgets.spage.SPage', array(
    'id'=>'subscription_page',
    'flash' => $this->action->id,
    'heading' => [
        'name'=> Sii::t('sii','Subscription Summary'),
    ],
    'body'=>$this->renderPartial('_pending_body',array('model'=>$model),true),
));
