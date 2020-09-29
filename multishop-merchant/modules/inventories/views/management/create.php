<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerGridViewCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Create'));

$this->menu=array();

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> Sii::t('sii','Create {object}',array('{object}'=>$model->displayName())),
        'image'=> $this->hasParentProduct()?$this->getParentProduct()->getImageThumbnail(Image::VERSION_XSMALL):null,
    ),
    'description'=>Sii::t('sii','Define SKU and inventory level by product code. SKU can be set according to combination of product attributes too.'),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));
