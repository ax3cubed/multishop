<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerGridViewCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Update'),$model);
$this->menu=$this->getPageMenu($model,'update',array('saveOnclick'=>'submitform(\'shipping-form\');'));

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
    'description' => Sii::t('sii','Edit your shipping setup. Shipping with status {online} means everyone can view and choose as a product shipping option.',array('{online}'=>Process::getHtmlDisplayText(Process::SHOP_ONLINE))),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));

//if (YII_DEBUG) {
//    SActiveSession::debug($this->stateVariable);
//} 