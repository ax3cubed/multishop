<?php
/* @var $this CategoryController */
/* @var $data Category */
?>
<div class="list-box float-image">
    <?php $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data'),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="heading-element">{value}</div>',
                    'value'=>CHtml::link(CHtml::encode($data->name), $data->viewUrl),
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('obj_type')).'</strong>'.
                             CHtml::encode($data->getObjectTypeText()),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('code')).'</strong>'.
                             CHtml::encode($data->code),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode(Sii::t('sii','Options')).'</strong>'.
                             '<div style="padding: 10px 10px 0px;">'.$data->getOptionsText().'</div>',
                ),        
            ),
        )); 
    ?>     
</div>