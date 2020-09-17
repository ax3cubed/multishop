<?php
$this->breadcrumbs=[
    Sii::t('sii','Billing')=>url('billing'),
    Sii::t('sii','Overview'),
];

$this->menu = $this->getBillingMenu('view');

$this->widget('common.widgets.spage.SPage',[
    'id'=>'billing_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash' => $this->modelType,
    'heading' => array(
        'name'=> Sii::t('sii','Billing Overview'),
    ),
    'body'=>$this->widget('common.widgets.SListView', [
                'dataProvider'=>$dataProvider,
                'itemView'=>'_overview',
            ],true),
    'sidebars'=> $this->getProfileSidebar(user()->getAccountMenu(),SPageLayout::WIDTH_15PERCENT),
]);