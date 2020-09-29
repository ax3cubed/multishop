<?php $this->module->registerFormCssFile();?>
<?php $this->module->registerChosen();?>
<?php $this->module->registerMediaGalleryAssets();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Update'),$model);
$this->menu=$this->getPageMenu($model,'update',array('saveOnclick'=>'submitform(\'news-form\');'));

$this->getPage(array(
    'id'=>'news_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->displayLanguageValue('headline',user()->getLocale()),
        'tag'=> $model->getStatusText(),
        'image'=> $model->shop->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'width:'.Image::VERSION_XSMALL.'px;')),
        'subscript'=> $model->formatDateTime($model->create_time,true),
    ),
    'description' => Sii::t('sii','Edit news. News with status {online} means everyone can read to your news.',array('{online}'=>Process::getHtmlDisplayText(Process::SHOP_ONLINE))),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));
