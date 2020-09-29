<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php $this->getModule()->registerTab();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Create'));

$this->menu=array();

$this->getPage(array(
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> Sii::t('sii','Create Payment Method'),
        'image'=> $this->hasParentShop()?$this->getParentShop()->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'width:'.Image::VERSION_XSMALL.'px;')):null,
    ),
    'description' => Sii::t('sii','You have many payment methods, either online or offline, to choose from to pay for your order.'),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));    