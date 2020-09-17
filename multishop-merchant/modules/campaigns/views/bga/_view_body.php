<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>$model,
    'columns'=>array(
        array(
            array('name'=>'shop_id','value'=>$model->shop->displayLanguageValue('name',user()->getLocale())),
            array('name'=>'create_time','value'=>$model->formatDatetime($model->create_time,true)),
            array('name'=>'update_time','value'=>$model->formatDatetime($model->update_time,true)),
        ),
    ),
));

$this->renderPartial('_view_banner',array('model'=>$model,'showStatus'=>true));