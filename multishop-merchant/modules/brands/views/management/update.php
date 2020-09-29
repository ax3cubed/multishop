<?php $this->module->registerFormCssFile();?>
<?php $this->module->registerCkeditor('brand');?>
<?php $this->module->registerChosen();?>
<?php $this->module->registerMediaGalleryAssets();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Update'),$model);
$this->menu=$this->getPageMenu($model,'update',array('saveOnclick'=>'submitform(\'brand-form\');'));

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->displayLanguageValue('name',user()->getLocale()),
    ),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));