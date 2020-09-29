<?php
$this->breadcrumbs=[
    Sii::t('sii','Billing')=>url('billing'),
    Sii::t('sii','Subscriptions')=>url('subscriptions'),
    Sii::t('sii','Update'),
];

$this->menu = $this->getBillingMenu();

$this->widget('common.widgets.spage.SPage',[
    'id'=>'subscription_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => $this->modelType,
    'heading'=> [
        'name'=> $model->name.' '.$model->subscription_no,
        'tag'=> $model->getStatusText(),
    ],
    'body'=>$this->renderPartial('_update_body',['model'=>$model],true),
    'sidebars'=> $this->getProfileSidebar(user()->getAccountMenu(),SPageLayout::WIDTH_15PERCENT),
]);
