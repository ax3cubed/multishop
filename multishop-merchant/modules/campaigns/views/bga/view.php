<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','View'),$model);
$this->menu=$this->getPageMenu($model,$this->action->id,array(
        'activateUrl'=>url('campaigns/bga/activate',array(get_class($model).'[id]'=>$model->id)),
        'deactivateUrl'=>url('campaigns/bga/deactivate',array(get_class($model).'[id]'=>$model->id)),
    ));

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->displayLanguageValue('name',user()->getLocale()),
        'tag'=> $model->getStatusText(),
        'image'=>CHtml::image($model->getImageOriginalUrl(),'',array('style'=>'width:'.Image::VERSION_XSMALL.'px;')),
        //'image'=>$this->simageviewerWidget(array('imageModel'=>$model,'imageName'=>$model->getLanguageValue('name',user()->getLocale()),'imageVersion'=>Image::VERSION_XSMALL),true),
    ),
    'description'=>Sii::t('sii','Campagin with status {online} means it is live and every one can enjoy the offer.',array('{online}'=>Process::getHtmlDisplayText(Process::SHOP_ONLINE))),
    'body'=>$this->renderPartial('_view_body', array('model'=>$model),true),
    'sections'=>$this->getSectionsData($model,false),
));
