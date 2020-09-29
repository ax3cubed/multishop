<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'all','title'=>Sii::t('sii','All'),'subscript'=>Sii::t('sii','all'), 'url'=>url('campaigns'),'linkOptions'=>array('class'=>'active')),
    array('id'=>'bga','title'=>Sii::t('sii','BGA Campaigns'),'subscript'=>Sii::t('sii','bga'), 'url'=>url('campaigns/bga')),
    array('id'=>'code','title'=>Sii::t('sii','Promocode Campaigns'),'subscript'=>Sii::t('sii','code'), 'url'=>url('campaigns/promocode')),
    array('id'=>'sales','title'=>Sii::t('sii','Sale Campaigns'),'subscript'=>Sii::t('sii','sale'), 'url'=>url('campaigns/sale')),
);

$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    //array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('hideHeading' => false),
    array('description' => Sii::t('sii','You have many ways to drive sales through campaigns such as store-wide discount, promotional code, or Buy X Get Y etc.')),
    $this->getPageSidebar($this->includePageFilter,$this->menu),
    $config));
