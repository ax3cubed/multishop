<?php 
$this->module->registerChosen();
$this->module->registerFormCssFile()
?>
<?php
$this->breadcrumbs=[
    Sii::t('sii','Shops')=>url('shops'),
    $model->parseName(user()->getLocale())=>$model->viewUrl,
    Sii::t('sii','Settings'),
//    Sii::t('sii','Settings')=>url('shop/settings'),
//    ShopSetting::getLabel($setting),
];

$this->menu=$this->getSettingsMenu($model,$setting);

$this->getPage([
    'id'=>$setting.'_page',
    'cssClass'=>'bootstrap-page',//to enable support of bootstrap
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'=>get_class($model),
    'heading'=> [
        'name'=> Sii::t('sii','{section} Settings',['{section}'=>ShopSetting::getLabel($setting)]),
        'image'=> $model->getImageThumbnail(Image::VERSION_ORIGINAL,['style'=>'width:'.Image::VERSION_XSMALL.'px;']),
    ],
    'description'=>$model->settings->getAttributeDescription($setting),
    'body'=>$this->renderPartial('_form', ['model'=>$model->settings->getForm($setting),'setting'=>$setting],true),
]);

if (isset($script))
    Helper::registerJs($script);