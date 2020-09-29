<?php
$this->breadcrumbs=$this->getBreadcrumbsData();

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create {object}',array('{object}'=>CampaignBga::model()->displayName())),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
    array('id'=>'bga','title'=>Sii::t('sii','BGA Campaigns'),'subscript'=>Sii::t('sii','bga'), 'url'=>url('campaigns/bga'),'linkOptions' => array('class'=>'active')),
    array('id'=>'code','title'=>Sii::t('sii','Promocode Campaigns'),'subscript'=>Sii::t('sii','code'), 'url'=>url('campaigns/promocode')),
    array('id'=>'sales','title'=>Sii::t('sii','Sale Campaigns'),'subscript'=>Sii::t('sii','sale'), 'url'=>url('campaigns/sale')),
);
    
$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    //array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('hideHeading' => false),
    array('description' => Sii::t('sii','Attract more customers by offering various kind of product level promotions through configurable and powerful BGA campaigns.')),
    $this->getPageSidebar($this->includePageFilter,$this->menu),
    $config));    
