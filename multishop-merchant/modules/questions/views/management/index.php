<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'all','title'=>Sii::t('sii','All Questions'),'subscript'=>Sii::t('sii','all'), 'linkOptions'=>array('id'=>'all_items','class'=>$this->getPageMenuCssClass('all'),'onclick'=>'filter("'.$this->route.'","'.$this->modelType.'","all")')),
    array('id'=>'asked','title'=>Sii::t('sii','Asked Questions'),'subscript'=>Sii::t('sii','asked'), 'linkOptions'=>array('id'=>'asked_items','class'=>$this->getPageMenuCssClass('asked'),'onclick'=>'filter("'.$this->route.'","'.$this->modelType.'","asked")')),
    array('id'=>'answered','title'=>Sii::t('sii','Answered Questions'),'subscript'=>Sii::t('sii','answered'), 'linkOptions'=>array('id'=>'answered_items','class'=>$this->getPageMenuCssClass('answered'),'onclick'=>'filter("'.$this->route.'","'.$this->modelType.'","answered")')),
);

$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('description' => Sii::t('sii','Customers comes first. Their questions are important that you should not ignore.')),
    $this->getPageSidebar(),
    $config));    
