<div class="list-box">
<?php 
$this->widget('common.widgets.SDetailView', [
        'data'=>$data,
        'htmlOptions'=>['class'=>'data'],
        'attributes'=> [
            [
                'type'=>'raw',
                'template'=>'<div class="heading-element">{value}</div>',
                'value'=>CHtml::link(CHtml::encode($data->displayName().' '.$data->payment_no), $data->viewUrl),
            ],
            [
                'type'=>'raw',
                'template'=>'<div class="element">{value}</div>',
                'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('reference_no')).'</strong>'.
                         CHtml::link(CHtml::encode($data->reference_no), $this->getReferenceUrl($data->reference_no)),
            ],         
            [
                'type'=>'raw',
                'template'=>'<div class="element">{value}</div>',
                'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('create_time')).'</strong>'.
                         CHtml::encode($data->formatDatetime($data->create_time,true)),
            ],         
            [
                'type'=>'raw',
                'template'=>'<div class="element">{value}</div>',
                'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('amount')).'</strong>'.
                         CHtml::encode($data->formatCurrency($data->amount,$data->currency)),
            ],         
        ],
    ]);
?>
</div>