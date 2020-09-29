<?php
$this->widget('common.widgets.spage.SPage', array(
    'id'=>'plan_cancel_page',
    'flash' => $this->action->id,
    'heading' => [
        'name'=> Sii::t('sii','Cancel {package} Plan "{id}" for {shop}',['{id}'=>$model->subscription_no,'{package}'=>Package::siiName($model->package_id),'{shop}'=>$model->shop->parseName(user()->getLocale())]),
    ],
   'body'=>$this->renderPartial('_cancel_form', array('model'=>$model),true),
));

