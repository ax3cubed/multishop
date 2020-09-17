<?php
$this->widget('common.widgets.spage.SPage', [
    'id'=>'plan_billing_page',
    'flash' => $this->action->id,
    'heading' => [
        'name'=> Sii::t('sii','Subscription Summary'),
    ],
    'body'=>$this->renderPartial('_billing_form', ['model'=>$model,'subscription'=>isset($subscription)?$subscription:null],true),
]);


