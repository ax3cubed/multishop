<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create Category'),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
    array('id'=>'product','title'=>Sii::t('sii','Products Management'),'subscript'=>Sii::t('sii','product'), 'url'=>url('products')),
    array('id'=>'category','title'=>Sii::t('sii','Categories Management'),'subscript'=>Sii::t('sii','category'), 'url'=>url('categories'),'linkOptions' => array('class'=>'active')),
    array('id'=>'brand','title'=>Sii::t('sii','Brands Management'),'subscript'=>Sii::t('sii','brand'), 'url'=>url('brands')),
);

$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    array('menu' => $this->menu),
    array('flash'=> $this->modelType),
    array('description'=> Sii::t('sii','Group your products into main categories and sub-categories. This make your customers find your products more easily.')),
    $this->getPageSidebar(),
    $config));