<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create Zone'),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
    array('id'=>'shipping','title'=>Sii::t('sii','Shippings'),'subscript'=>Sii::t('sii','shipping'), 'url'=>url('shippings')),
    array('id'=>'zone','title'=>Sii::t('sii','Zones Management'),'subscript'=>Sii::t('sii','zone'), 'url'=>url('shippings/zone'), 'linkOptions' => array('class'=>'active')),
);
    
$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('description' => Sii::t('sii','Specify the zone areas that you want to ship your products.')),
    $this->getPageSidebar(),
    $config));
