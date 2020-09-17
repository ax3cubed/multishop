<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create {object}',array('{object}'=>Product::model()->displayName())),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
    array('id'=>'import','title'=>Sii::t('sii','Import {object}',array('{object}'=>Product::model()->displayName())),'subscript'=>Sii::t('sii','import'), 'url'=>array('import')),
    array('id'=>'product','title'=>Sii::t('sii','Products Management'),'subscript'=>Sii::t('sii','product'), 'url'=>url('products'),'linkOptions' => array('class'=>'active')),
    array('id'=>'category','title'=>Sii::t('sii','Categories Management'),'subscript'=>Sii::t('sii','category'), 'url'=>url('categories')),
    array('id'=>'brand','title'=>Sii::t('sii','Brands Management'),'subscript'=>Sii::t('sii','brand'), 'url'=>url('brands')),
);

$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    //array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('description' => Sii::t('sii','Add and manage all your products here. You can create many categories to group your products, and do bulk upload products by file.')),
    $this->getPageSidebar($this->includePageFilter,$this->menu),
    $config));