<?php 
$this->breadcrumbs=array(
	Sii::t('sii','Billing')=>url('billing'),
	Sii::t('sii','Subscriptions'),
);

$this->menu = $this->getBillingMenu('history');

$this->spageindexWidget(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    //array('description' => Sii::t('sii','This shows your subscription history.')),
    array('sidebars'=> $this->getProfileSidebar(user()->getAccountMenu(),SPageLayout::WIDTH_15PERCENT)),
    $config));   
