<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerGridViewCssFile();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Update'),$model->modelInstance);
$this->menu=$this->getPageMenu($model->modelInstance,'update',array('saveOnclick'=>'submitform(\'inventory-form\');'));

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model->modelInstance),
    'heading'=> array(
        'name'=> 'SKU '.$model->sku,
        'image'=> $model->modelInstance->source->getImageThumbnail(Image::VERSION_XSMALL),
        'superscript'=> $this->hasParentProduct()?'':l($model->modelInstance->source->displayLanguageValue('name',user()->getLocale()),$model->modelInstance->source->viewUrl),
    ),
    'body'=>$this->renderPartial('_form_update', array('model'=>$model),true),
));