<?php $this->module->registerFormCssFile();?>
<?php $this->module->registerGridViewCssFile();?>
<?php $this->module->registerChosen();?>
<?php $this->module->registerScriptFile('attributes.assets.js','attributes.js');
      $this->module->registerCssFile('attributes.assets.css','attributes.css');
?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Update'),$model->product);
$this->menu=$this->getPageMenu($model,'update',array(
        'saveOnclick'=>'submitform(\'product-attribute-form\');',
        'deleteUrl'=>url('product/attribute/delete/id/'.$model->id),
    ));

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->displayLanguageValue('name',user()->getLocale()),
        //'superscript'=> $model->product->displayLanguageValue('name',user()->getLocale()),
        'image'=> $model->product->getImageThumbnail(Image::VERSION_XSMALL),
    ),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));

//if (YII_DEBUG) {
//    SActiveSession::debug($this->stateVariable);
//}