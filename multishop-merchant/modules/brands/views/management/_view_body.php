<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        array('name'=>'shop_id','type'=>'raw','value'=>l($model->shop->displayLanguageValue('name',user()->getLocale()),$model->shop->viewUrl)),
        array('name'=>'create_time','value'=>$model->formatDatetime($model->create_time,true)),
        array('name'=>'update_time','value'=>$model->formatDatetime($model->update_time,true)),
        array('name'=>'slug','type'=>'raw','value'=>$model->getUrl()),
    ),
));

$model->languageForm->renderForm($this,Helper::READONLY);