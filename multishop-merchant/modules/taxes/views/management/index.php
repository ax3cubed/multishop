<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create Tax'),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
    array('id'=>'tax', 'title' => Sii::t('sii', 'Taxes Management'), 'subscript' => Sii::t('sii', 'tax'), 'url' => url('taxes'), 'linkOptions' => array('class'=>'active')),
);
    
$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('description' => Sii::t('sii','Add and manage taxes you want to charge on top of total order price e.g. VAT, GST.')),
    $this->getPageSidebar(),
    $config));
