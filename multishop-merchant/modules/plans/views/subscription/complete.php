<?php
$this->widget('common.widgets.spage.SPage', [
    'id'=>'plan_complete_page',
    'flash' => $this->action->id,
    'heading' => [
        'name'=> $newSubscription ? Sii::t('sii','Thanks for your purchase') : Sii::t('sii','Plan is changed successfully'),
    ],
    'body'=>$this->renderPartial($newSubscription ? '_complete_new_subscription' : '_complete_change_subscription', ['model'=>$model],true),
]);
