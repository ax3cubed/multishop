<?php
/* @var $this ManagementController */
/* @var $data Brand */
?>
<div class="list-box float-image">
    <div class="image">
        <?php echo $data->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'width:'.Image::VERSION_SMEDIUM.'px;'));?>
    </div>
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
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.Sii::t('sii','Product|Products',array($data->productCount)).'</strong>'.
                             CHtml::encode($data->productCount).
                             $this->widget($this->module->getClass('listview'), 
                                        array(
                                            'dataProvider'=> new CArrayDataProvider($data->products),
                                            'template'=>'{items}',
                                            'emptyText'=>'',
                                            'itemView'=>'_product',
                                        ),true),
                ),        
            ),
        )); 
    ?> 
</div>