<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create Inventory'),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
    array('id'=>'all','title'=>Sii::t('sii','All Inventories'),'subscript'=>Sii::t('sii','all'), 'linkOptions'=>array('id'=>'all_items','class'=>$this->getPageMenuCssClass('all'),'onclick'=>'filter("'.$this->route.'","'.$this->modelType.'","all")')),
    array('id'=>'low_stock','title'=>Sii::t('sii','Low Quantity Inventories'),'subscript'=>Sii::t('sii','low-stock'), 'linkOptions'=>array('id'=>'lowStock_items','class'=>$this->getPageMenuCssClass('lowStock'),'onclick'=>'filter("'.$this->route.'","'.$this->modelType.'","lowStock")')),
    array('id'=>'out_of_stock','title'=>Sii::t('sii','Out of Stock'),'subscript'=>Sii::t('sii','no-stock'), 'linkOptions'=>array('id'=>'outOfStock_items','class'=>$this->getPageMenuCssClass('outOfStock'),'onclick'=>'filter("'.$this->route.'","'.$this->modelType.'","outOfStock")')),
);

$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('description' => Sii::t('sii','Know your inventories whether they are in high demand, run low or out of stock.')),
    $this->getPageSidebar(),
    $config));    
