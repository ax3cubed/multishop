<?php 
$this->module->registerProcessCssFile();
$this->module->registerTaskScript();
$this->module->registerChosen();
$this->module->registerSUploadScript();

$this->breadcrumbs = [
    Sii::t('sii','Items'),
];

//Moved to page filter quick menu
//$this->menu=array(
//    array('id'=>'process','title'=>Sii::t('sii','View Purchase Orders'),'subscript'=>Sii::t('sii','PO'), 'url'=>url('purchase-orders')),
//    array('id'=>'process','title'=>Sii::t('sii','View Shipping Orders'),'subscript'=>Sii::t('sii','SO'), 'url'=>url('shipping-orders')),
//    array('id'=>'process','title'=>Sii::t('sii','View Items'),'subscript'=>Sii::t('sii','items'), 'url'=>url('items'),'linkOptions'=>['class'=>'active']),
//);

$this->spageindexWidget(array_merge(
    ['breadcrumbs'=>$this->breadcrumbs],
    ['flash' => $this->id],
    //['menu'  => $this->menu],
    ['sidebars'=>$this->getPageFilterSidebarData()],
    $config)
);                                 