<?php $this->getModule()->registerGridViewCssFile();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create Brand'),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),    
    array('id'=>'product','title'=>Sii::t('sii','Products Management'),'subscript'=>Sii::t('sii','product'), 'url'=>url('products')),
    array('id'=>'category','title'=>Sii::t('sii','Categories Management'),'subscript'=>Sii::t('sii','category'), 'url'=>url('categories')),
    array('id'=>'brand','title'=>Sii::t('sii','Brands Management'),'subscript'=>Sii::t('sii','brand'), 'url'=>url('brands'),'linkOptions' => array('class'=>'active')),
);
    
$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('hideHeading' => false),
    array('description' => Sii::t('sii','Manage your product brands here.')),
    $this->getPageSidebar(),
    $config));
