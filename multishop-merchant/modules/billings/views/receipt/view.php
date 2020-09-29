<?php
$this->breadcrumbs=array(
	Sii::t('sii','Billing')=>url('billing'),
	Sii::t('sii','Subscriptions')=>url('subscriptions'),
	$model->displayName(),
);

$this->menu = $this->getBillingMenu();

$this->widget('common.widgets.spage.SPage',array(
    'id'=>'receipt_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->getAttributeLabel('receipt_no').' '.$model->receipt_no,
        'superscript'=> $model->getAttributeLabel('receipt_date').' '.$model->formatDatetime($model->create_time),
        'tag'=>$model->formatCurrency($model->amount,$model->currency),
    ),
    'body'=>$this->renderPartial('_view_body',array('model'=>$model),true),
    'sidebars'=>  $this->getProfileSidebar(user()->getAccountMenu(),SPageLayout::WIDTH_15PERCENT),
));
