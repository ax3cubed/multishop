<?php $this->module->registerFormCssFile();?>
<?php $this->module->registerCkeditor('brand');?>
<?php $this->module->registerChosen();?>
<?php $this->module->registerMediaGalleryAssets();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Create'));
$this->menu=array();

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> Sii::t('sii','Create Brand'),
        'image'=> $this->hasParentShop()?$this->getParentShop()->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'width:'.Image::VERSION_XSMALL.'px;')):null,
    ),
    'description'=>Sii::t('sii','A good brand helps your products to sell better.'),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));
