<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','View'),$model);

$this->menu=$this->getPageMenu($model,$this->action->id);

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->displayLanguageValue('name',user()->getLocale()),
        'image'=>$model->getImageThumbnail(Image::VERSION_XSMALL),
    ),
    'body'=>$this->renderPartial('_view_body', array('model'=>$model),true),
    'sections'=>$this->getSectionsData($model),
));

