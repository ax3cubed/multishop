<?php
$this->breadcrumbs=[
    Sii::t('sii','Shipping Orders')=>url('orders'),
    Sii::t('sii','View')
];

$this->menu=[
    [
        'id'=>'process','title'=>SButtonColumn::getButtonTitle('process'),
        'subscript'=>SButtonColumn::getButtonSubscript('process'), 'visible'=>$model->actionable(user()->currentRole,user()->getId()), 
        'linkOptions'=>[
            'onclick'=>'qwom('.$model->id.',\''.$model->getWorkflowAction().'\')',
            'class'=>'workflow-button'
        ],
    ],
];

$this->widget('common.widgets.spage.SPage',[
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> [
        'name'=> $model->shipping_no,
        'tag'=> $model->getStatusText(),
        'subscript'=>$model->getShippingName(user()->getLocale()),
    ],
    'sections'=>$this->getSectionsData($model),
    'csrfToken' => true, //needed by tasks.js
]);