<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','View'),$model);
$this->menu=$this->getPageMenu($model,$this->action->id);

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash' => get_class($model),
    'heading'=> array(
        'name'=> $model->displayLanguageValue('name',user()->getLocale()),
    	'image'=>CHtml::image($model->getImageOriginalUrl(),'',array('style'=>'width:'.Image::VERSION_XSMALL.'px;')),
        //'image'=>$this->simageviewerWidget(array('imageModel'=>$model,'imageName'=>$model->getLanguageValue('name',user()->getLocale()),'imageVersion'=>Image::VERSION_XSMALL),true),
    ),
    'body'=>$this->renderPartial('_view_body', array('model'=>$model),true),
    'sections'=>$this->getSectionsData($model,user()->getLocale()),
));
