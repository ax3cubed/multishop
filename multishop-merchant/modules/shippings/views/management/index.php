<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create Shipping'),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
    array('id'=>'shipping', 'title' => Sii::t('sii', 'Shippings Management'), 'subscript' => Sii::t('sii', 'shipping'), 'url' => url('shippings'), 'linkOptions' => array('class'=>'active')),
    array('id'=>'zone','title'=>Sii::t('sii','Zones Management'),'subscript'=>Sii::t('sii','zone'), 'url'=>url('shippings/zone')),
);
    
$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('description' => Sii::t('sii','Define how you ship your products and the shipping rates required. Add as many shipping methods as you want.')),
    $this->getPageSidebar(),
    $config));
