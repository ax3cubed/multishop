<?php $this->module->registerFormCssFile();?>
<?php $this->module->registerCkeditor('category');?>
<?php $this->module->registerMediaGalleryAssets();?>
<?php if (!$this->hasParentShop()) $this->module->registerChosen();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Create'));

$this->menu=array();

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> Sii::t('sii','Create Category'),
        'image'=> $this->hasParentShop()?$this->getParentShop()->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'width:'.Image::VERSION_XSMALL.'px;')):null,
    ),
    'description'=>Sii::t('sii','You can customize category URL. Click [+] to add sub-category.'),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));
