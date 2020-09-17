<?php $this->getModule()->registerGridViewCssFile();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','View'),$model);
$this->menu=$this->getPageMenu($model,$this->action->id);

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> 'SKU '.$model->sku,
        'image'=> $model->source->getImageThumbnail(Image::VERSION_XSMALL),
        'superscript'=> $this->hasParentProduct()?'':l($model->source->displayLanguageValue('name',user()->getLocale()),$model->source->viewUrl),
    ),
    'body'=>$this->renderPartial('_view_body', array('model'=>$model),true),
    'sections'=>$this->getSectionsData($model),
));
