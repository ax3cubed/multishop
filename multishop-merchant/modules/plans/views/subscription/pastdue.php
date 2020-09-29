<?php
$this->widget('common.widgets.spage.SPage', array(
    'id'=>'plan_pastdue_page',
    'flash' => array($this->action->id,'Pastdue'),
    'heading' => array(
        'name'=> Sii::t('sii','Overdue Subscription'),
    ),
    'body'=>$this->renderPartial('_pastdue_form', array('formModel'=>$form,'model'=>$model),true),
));


