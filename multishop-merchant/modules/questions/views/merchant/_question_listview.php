<div class="list-box float-image">
    <div class="image">
        <?php echo $data->getReferenceImage(); ?> 
    </div>
    <?php $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data'),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="status">{value}</div>',
                    'value'=>Helper::htmlColorText($data->getStatusText(),false).Helper::htmlColorText($data->getTypeLabel(),false),
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="heading-element">{value}</div>',
                    'value'=>CHtml::link(CHtml::encode($data->title), $data->viewUrl),
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('tags')).'</strong>'.
                             ($data->hasTags()?Helper::htmlList($data->parseTags(),array('class'=>'tags')):Sii::t('sii','not set')),
                ),        
            ),
        )); 
    
    ?> 
</div>