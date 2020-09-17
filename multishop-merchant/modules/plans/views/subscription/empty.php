<?php
$this->widget('common.widgets.spage.SPage', [
    'id'=>'plan_checkout_page',
    'flash' => $this->action->id,
    'heading' => [
        'name'=> Sii::t('sii','Subscription Checkout'),
    ],
    'body'=>Sii::t('sii','No available plans for subscription. <br><br>Please <em>{contact us}</em> should you need any help.',['{contact us}'=>CHtml::link(Sii::t('sii','contact us'),url('/contact'))]),
]);

