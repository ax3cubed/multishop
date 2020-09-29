<?php
$this->breadcrumbs=[
    Sii::t('sii','Billing')=>url('billing'),
    Sii::t('sii','Payment Cards'),
];

$this->menu = $this->getBillingMenu('credit-card');

$this->widget('common.widgets.spage.SPage',[
    'id'=>'billing_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash' => $this->modelType,
    'heading' => [
        'name'=> Sii::t('sii','Payment Cards'),
    ],
    'body'=>$this->widget('common.widgets.SListView', [
                'dataProvider'=>$dataProvider,
                'itemView'=>'_card_listview',
            ],true).
            $this->widget('zii.widgets.jui.CJuiButton',[
                'id'=>'addCardButton',
                'name'=>'actionButton',
                'caption'=> Sii::t('sii','Add Payment Card'),
                'value'=>'actionbtn',
            ],true),
    'sidebars'=> $this->getProfileSidebar(user()->getAccountMenu(),SPageLayout::WIDTH_15PERCENT),
]);

$url = url('billing/payment/create');
$script = <<<EOJS
$('#addCardButton').click(function(){
    window.location = '$url';
});
EOJS;
Helper::registerJs($script);