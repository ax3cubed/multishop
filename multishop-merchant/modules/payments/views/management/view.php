<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','View'),$model);

$this->menu=$this->getPageMenu($model,$this->action->id,array(
        'activateUrl'=>url('payments/management/activate',array('PaymentMethod[id]'=>$model->id)),
        'deactivateUrl'=>url('payments/management/deactivate',array('PaymentMethod[id]'=>$model->id)),
    ));

$this->getPage(array(
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash' => get_class($model),
    'heading'=> array(
        'name'=> $model->getMethodName(user()->getLocale()),
        'tag'=> $model->getStatusText(),
        'image'=> $this->hasParentShop()?$this->getParentShop()->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'width:'.Image::VERSION_XSMALL.'px;')):null,
    ),
    'description' => Sii::t('sii','Payment method with status {online} means everyone can start use it to pay your order.',array('{online}'=>Process::getHtmlDisplayText(Process::SHOP_ONLINE))),
    'body'=>$this->renderPartial('_view_body', array('model'=>$model),true),
));