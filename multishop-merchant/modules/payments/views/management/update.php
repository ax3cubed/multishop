<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php $this->getModule()->registerTab();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Update'),$model);
$this->menu=$this->getPageMenu($model,'update',array('saveOnclick'=>'submitform(\'payment-method-form\');'));

$this->getPage(array(
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->getMethodName(user()->getLocale()),
        'tag'=> $model->getStatusText(),
        'image'=> $this->hasParentShop()?$this->getParentShop()->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'width:'.Image::VERSION_XSMALL.'px;')):null,
    ),
    'description' => Sii::t('sii','Edit payment method setup. Payment method with status {online} means everyone can start use it to pay your order.',array('{online}'=>Process::getHtmlDisplayText(Process::SHOP_ONLINE))),
    'body'=>$this->renderPartial('_update_form', array('model'=>$model),true),
));    
