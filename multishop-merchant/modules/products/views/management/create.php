<?php $this->module->registerFormCssFile();?>
<?php $this->module->registerChosen();?>
<?php $this->module->registerGridViewCssFile();?>
<?php $this->module->registerCkeditor('product');?>
<?php $this->module->registerMediaGalleryAssets();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Create'));

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create {object}',array('{object}'=>Product::model()->displayName())),'subscript'=>Sii::t('sii','create'), 'url'=>array('create'),'linkOptions'=>array('class'=>'active')),
    array('id'=>'import','title'=>Sii::t('sii','Import {object}',array('{object}'=>Product::model()->displayName())),'subscript'=>Sii::t('sii','import'), 'url'=>array('import')),
);

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> Sii::t('sii','Create Product'),
        'image'=> $this->hasParentShop()?$this->getParentShop()->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'width:'.Image::VERSION_XSMALL.'px;')):null,
    ),
    'description'=>Sii::t('sii','Give your product a good name, and remember nice product pictures worth a thousand words of description.'),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));
    
//if (YII_DEBUG) {
//    echo 'session images<br>'.dump($model->modelInstance->loadSessionImages());    
//}
