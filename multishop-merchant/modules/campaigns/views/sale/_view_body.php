<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>$model,
    'columns'=>array(
        array(
            array('name'=>'shop_id','value'=>$model->shop->displayLanguageValue('name',user()->getLocale())),
            array('name'=>'create_time','value'=>$model->formatDatetime($model->create_time,true)),
            array('name'=>'update_time','value'=>$model->formatDatetime($model->update_time,true)),
        ),
        array(
            array('name'=>'sale_value','value'=>$model->getCampaignText(user()->getLocale(),CampaignSale::TAG_SALE)),
            array('name'=>'offer_value','value'=>$model->getCampaignText(user()->getLocale(),CampaignSale::TAG_OFFER)),
            array('name'=>'validity','type'=>'raw','value'=>CHtml::encode($model->getValidityText()).($model->hasExpired()?$model->getExpiredTag():''))
        ),
    ),
));
