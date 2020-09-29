<?php
/* @var $this ManagementController */
/* @var $data Tax */
?>
<div class="list-box">
    <span class="status">
        <?php echo Helper::htmlColorText($data->getStatusText(),false); ?>
    </span>
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
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('shop_id')).'</strong>'.
                             CHtml::link(CHtml::encode($data->shop->displayLanguageValue('name',user()->getLocale())), $data->shop->viewUrl),
                    'visible'=>!$this->hasParentShop(),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('rate')).'</strong>'.
                             CHtml::encode($data->formatPercentage($data->rate)),
                ),        
            ),
        )); 
    ?> 
</div>