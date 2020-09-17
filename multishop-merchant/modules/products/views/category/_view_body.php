<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>$model,
    'columns'=>array(
        array(
            array('name'=>'shop_id','type'=>'raw','value'=>l($model->shop->displayLanguageValue('name',user()->getLocale()),$model->shop->viewUrl)),
            array('name'=>'create_time','value'=>$model->formatDatetime($model->create_time,true)),
            array('name'=>'update_time','value'=>$model->formatDatetime($model->update_time,true)),
            array('name'=>'slug','type'=>'raw','value'=>$model->getUrl()),
            array('label'=>CategorySub::model()->getAttributeLabel('slug'),'type'=>'raw','value'=>Helper::htmlList($model->getSubcategoriesToArray(user()->getLocale(),true),array('style'=>'margin:0px;padding:5px 20px 0px;'))),
        ),
    ),    
));
//$model->languageForm->renderForm($this,Helper::READONLY);