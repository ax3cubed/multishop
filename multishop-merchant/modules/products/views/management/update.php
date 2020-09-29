<?php $this->module->registerFormCssFile();?>
<?php $this->module->registerChosen();?>
<?php $this->module->registerGridViewCssFile();?>
<?php $this->module->registerCkeditor('product');?>
<?php $this->module->registerMediaGalleryAssets();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Update'),$model);
$this->menu=$this->getPageMenu($model,'update',array(
        'saveOnclick'=>'submitproduct();',
        'extraMenu'=>array(
            array('id'=>'attribute','title'=>Sii::t('sii','Manage Attributes'),'subscript'=>Sii::t('sii','attribute'), 'url'=>url('product/attribute')),
            array('id'=>'inventory','title'=>Sii::t('sii','Manage Inventories'),'subscript'=>Sii::t('sii','inventory'), 'url'=>url('inventories/management/index',array('option'=>$this->pageViewOption,'product'=>$model->id))),
        ),
    ));

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->displayLanguageValue('name',user()->getLocale()),
        'tag'=> $model->getStatusText(),
        'image'=> $this->hasParentShop()?$this->getParentShop()->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'width:'.Image::VERSION_XSMALL.'px;')):null,
    ),
    'description' => Sii::t('sii','Edit product setup. If product is shown with status {online} means everyone can access and make purchase your product.',array('{online}'=>Process::getHtmlDisplayText(Process::SHOP_ONLINE))),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));

if (YII_DEBUG) {
    echo 'session images<br>'.dump($model->modelInstance->loadSessionMedia());
}
 