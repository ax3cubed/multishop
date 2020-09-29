<?php
$this->breadcrumbs=array(
	Sii::t('sii','Shops'),
);

//moved to page filter quick menu
//$this->menu=array(
//    //array('id'=>'apply','title'=>Sii::t('sii','Apply Shop'),'subscript'=>Sii::t('sii','apply'), 'url'=>array('apply'), 'linkOptions'=>array('class'=>'primary-button')),    
//    array('id'=>'create','title'=>Sii::t('sii','Create Shop'),'subscript'=>Sii::t('sii','create'), 'url'=>array('create'),'linkOptions'=>array()),    
//);

$this->spageindexWidget(array_merge(
    ['breadcrumbs'=>$this->breadcrumbs],
    ['flash' => $this->modelType],
    ['description' => Sii::t('sii','Manage all your shops and get overview of your shops setting information.')],
    ['hideHeading' => false],
    //['menu'  => $this->menu],
    ['sidebars'=>$this->getPageFilterSidebarData()],
    $config)
);
