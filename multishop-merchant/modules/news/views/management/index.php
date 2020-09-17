<?php $this->getModule()->registerGridViewCssFile();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create News'),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
);

$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('hideHeading' => false),
    array('description' => Sii::t('sii','Communicate and share your exciting news to customers.')),
    $this->getPageSidebar(),
    $config));    
