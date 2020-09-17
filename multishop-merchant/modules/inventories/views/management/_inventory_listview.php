<div class="list-box float-image">
    <div class="image">
        <?php echo $data->source->getImageThumbnail(Image::VERSION_SMEDIUM);?>
    </div>
    <?php $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data'),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="heading-element">{value}</div>',
                    'value'=>CHtml::link(CHtml::encode($data->sku), $data->viewUrl),
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.Sii::t('sii','Product').'</strong>'.
                             CHtml::link(CHtml::encode($data->source->displayLanguageValue('name',user()->getLocale())), $data->source->viewUrl),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('quantity')).'</strong>'.
                             CHtml::encode($data->quantity),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('available')).'</strong>'.
                             CHtml::encode($data->available),
                ),        
            ),
        )); 
    ?> 
</div>