<?php
$this->breadcrumbs=array(
    Sii::t('sii','Shops')=>url('shops'),
    $shopModel->parseName(user()->getLocale())=>$shopModel->viewUrl,
    Sii::t('sii','Settings'),
);

$this->menu=$this->getSettingsMenu($shopModel);

$this->getPage(array(
    'id'=>'shop_settings',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'=>get_class($shopModel),
    'heading'=> array(
        'name'=> Sii::t('sii','Shop Settings'),
        'image'=> $shopModel->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'width:'.Image::VERSION_XSMALL.'px;')),
    ),
    'description'=>Sii::t('sii','Powerful and flexible shop configuration tool.'),
    'sections'=>$this->getSettingsSectionsData($shopModel->settings),
));