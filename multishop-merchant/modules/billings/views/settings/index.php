<?php
$this->breadcrumbs = [
    Sii::t('sii','Billing')=>url('billing'),
    Sii::t('sii','Settings'),
];

$this->menu = $this->getBillingMenu('settings');

$this->widget('common.widgets.spage.SPage',[
    'id'=>'billig_settings_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu' => $this->menu,
    'flash' => $this->model,
    'heading' => array(
        'name'=> Sii::t('sii','Billing Settings'),
    ),
    'body'=>$this->renderPartial('_form',['model'=>$model],true),
    'sidebars'=>  $this->getProfileSidebar(user()->getAccountMenu(),SPageLayout::WIDTH_15PERCENT),
]);