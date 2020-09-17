<?php
/* @var $this ZoneController */
/* @var $data Zone */
?>
<div class="list-box">
    <?php $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data'),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="heading-element">{value}</div>',
                    'value'=>CHtml::link(CHtml::encode($data->displayLanguageValue('name',user()->getLocale())), $data->viewUrl),
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('create_time')).'</strong>'.
                             CHtml::encode($data->formatDatetime($data->create_time,true)),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('update_time')).'</strong>'.
                             CHtml::encode($data->formatDatetime($data->update_time,true)),
                ),        
            ),
        )); 
    ?> 
</div>