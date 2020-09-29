<?php $this->getModule()->registerGridViewCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData('index',$this->getParentProduct());

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create Attribute'),'subscript'=>Sii::t('sii','create'), 'url'=>url('product/attribute/create')),
);
$this->getPageIndex(array_merge(
    array('breadcrumbs'=>$this->breadcrumbs),
    array('menu'  => $this->menu),
    array('flash' => $this->modelType),
    array('heading'=> array(
            'name'=> Sii::t('sii','Attributes'),
            'superscript'=> $this->hasParentProduct()?'':$this->getParentProduct()->name,
            'image'=> $this->getParentProduct()->getImageThumbnail(Image::VERSION_XSMALL),
        )),
    $this->getPageSidebar(),
    $config));