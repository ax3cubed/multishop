<?php
$this->breadcrumbs = [
    Sii::t('sii','Billing')=>url('billing'),
    Sii::t('sii','Payment Cards')=>url('billing/payment'),
    Sii::t('sii','Change'),
];

$this->menu = $this->getBillingMenu('credit-card');

$this->widget('common.widgets.spage.SPage',[
    'id'=>'payment_method_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu' => $this->menu,
    'flash' => $this->modelType,
    'heading' => [
        'name'=> Sii::t('sii','Change Payment Card'),
    ],
    'body'=>$this->renderPartial('_form_change',['data'=>$data],true),
    'sidebars'=>  $this->getProfileSidebar(user()->getAccountMenu(),SPageLayout::WIDTH_15PERCENT),
]);