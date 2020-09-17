<?php
$this->breadcrumbs=[
    Sii::t('sii','Purchase Orders')=>url('purchase-orders'),
    Sii::t('sii','View'),
];

$this->menu=[
    [
        'id'=>'process','title'=>SButtonColumn::getButtonTitle('process-po'),
        'subscript'=>SButtonColumn::getButtonSubscript('process'), 'visible'=>$model->actionable(user()->currentRole,user()->getId()), 
        'linkOptions'=>[
            'onclick'=>'qwo('.$model->id.',\''.$model->getWorkflowAction().'\')',
            'class'=>'workflow-button'
        ],
    ],
];

$this->widget('common.widgets.spage.SPage',[
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash' => get_class($model),
    'heading'=> [
        'name'=> $model->order_no,
        'tag'=> $model->getStatusText(),
    ],
    'sections'=>$this->getSectionsPOData($model),
    'csrfToken' => true, //needed by tasks.js
]);
