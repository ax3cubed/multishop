<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>$data,
    'htmlOptions'=>array('class'=>'list-box float-image'),
    'attributes'=>array(
        array(
            'type'=>'raw',
            'template'=>'<div class="status">{value}</div>',
            'value'=>Helper::htmlColorText($data->getStatusText(),false),
        ),
        array(
            'type'=>'raw',
            'template'=>'<div class="image">{value}</div>',
            'value'=>$data->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'max-width:120px;max-height:80px;')),
        ),
        array(
            'type'=>'raw',
            'template'=>'{value}',
            'value'=>$this->widget('common.widgets.SDetailView', array(
                'data'=>$data,
                'htmlOptions'=>array('class'=>'data'),
                'attributes'=>array(
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="heading-element">{value}</div>',
                        'value'=>CHtml::link(CHtml::encode(Helper::purify($data->displayLanguageValue('headline',user()->getLocale()))), $data->viewUrl),
                    ),
//                    array(
//                        'type'=>'raw',
//                        'template'=>'<div class="element">{value}</div>',
//                        'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('shop_id')).'</strong>'.
//                                 CHtml::link(CHtml::encode($data->shop->displayLanguageValue('name',user()->getLocale())), $data->shop->viewUrl),
//                    ),         
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="element">{value}</div>',
                        'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('create_time')).'</strong>'.
                                 CHtml::encode($data->formatDatetime($data->create_time,true)),
                    ),         
                ),
            ),true),
        ),        
    ),
)); 