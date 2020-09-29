<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create {object}',array('{object}'=>CampaignSale::model()->displayName())),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
    array('id'=>'bga','title'=>Sii::t('sii','BGA Campaigns'),'subscript'=>Sii::t('sii','bga'), 'url'=>url('campaigns/bga')),
    array('id'=>'code','title'=>Sii::t('sii','Promocode Campaigns'),'subscript'=>Sii::t('sii','code'), 'url'=>url('campaigns/promocode')),
    array('id'=>'sales','title'=>Sii::t('sii','Sale Campaigns'),'subscript'=>Sii::t('sii','sale'), 'url'=>url('campaigns/sale'),'linkOptions' => array('class'=>'active')),
);
    
$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    //array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('hideHeading' => false),
    array('description' => Sii::t('sii','Run a store-wide campaign to boost sales.')),
    $this->getPageSidebar($this->includePageFilter,$this->menu),
    $config));   
