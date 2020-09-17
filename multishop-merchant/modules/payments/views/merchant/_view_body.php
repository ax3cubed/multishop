<?php 
$this->widget('common.widgets.SDetailView', [
    'data'=>$model,
    'attributes'=>[
        ['name'=>'reference_no','type'=>'raw','value'=>CHtml::link(CHtml::encode($model->reference_no), $this->getReferenceUrl($model->reference_no))],
        ['name'=>'create_time','value'=>$model->formatDatetime($model->create_time,true)],
        ['name'=>'type','value'=>$model->getTypeDesc()],
        ['name'=>'method','value'=>$model->getPaymentMethodName(user()->getLocale())],
        ['name'=>'amount','value'=>$model->formatCurrency($model->amount,$model->currency)],
        ['name'=>'trace_no','type'=>'raw','value'=>$model->getTraceNo(),'cssClass'=>'trace-no'],
    ],
    'htmlOptions'=>['class'=>'detail-view solid'],
]);