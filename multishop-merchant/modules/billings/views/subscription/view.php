<?php
$this->breadcrumbs = [
    Sii::t('sii','Billing')=>url('billing'),
    Sii::t('sii','Subscriptions')=>url('subscriptions'),
    Sii::t('sii','View'),
];

$this->menu = $this->getBillingMenu();

$this->widget('common.widgets.spage.SPage',[
    'id'=>'subscription_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> [
        'name'=> $model->name.' '.Sii::t('sii','{start_date} to {end_date}',['{start_date}'=>$model->start_date,'{end_date}'=>$model->end_date]),
        'tag'=> $model->getStatusText(),
    ],
    'body'=>$this->renderPartial('_view_body',['model'=>$model],true),
    'sidebars'=> $this->getProfileSidebar(user()->getAccountMenu(),SPageLayout::WIDTH_15PERCENT),
]);
