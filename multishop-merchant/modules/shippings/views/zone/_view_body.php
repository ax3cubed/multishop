<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>$model,
    'columns'=>array(
        array(
            array('name'=>'country','value'=>SLocale::getCountries($model->country)),
//            array('name'=>'state'),
//            array('name'=>'city'),
//            array('name'=>'postcode'),
        ),
        array(
            array('name'=>'create_time','value'=>$model->formatDatetime($model->create_time,true)),
            array('name'=>'update_time','value'=>$model->formatDatetime($model->update_time,true)),
        ),
    ),
));
