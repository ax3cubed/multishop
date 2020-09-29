<?php
$this->widget('common.widgets.spage.SPage', [
    'id'=>'plan_checkout_page',
    'flash' => $this->action->id,
    'heading' => [
        'name'=> Sii::t('sii','Subscription Checkout'),
    ],
    'body'=>$this->renderPartial('_checkout_form', ['model'=>$model],true),
]);

