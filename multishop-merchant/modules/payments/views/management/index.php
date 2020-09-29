<?php $this->getModule()->registerFormCssFile();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create Payment Method'),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
);
    
$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('description' => Sii::t('sii','Setup payment methods that you want to accept to pay for your orders. You can set multiple online and offline payment methods.')),
    $this->getPageSidebar(),
    $config));    