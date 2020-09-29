<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','View'),$model);

$this->menu=$this->getPageMenu($model,$this->action->id,array(
        'activateUrl'=>url('news/management/activate',array('News[id]'=>$model->id)),
        'deactivateUrl'=>url('news/management/deactivate',array('News[id]'=>$model->id)),
    ));

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
    'description' => Sii::t('sii','News with status {online} means everyone can read to your news.',array('{online}'=>Process::getHtmlDisplayText(Process::SHOP_ONLINE))),
    'body'=>$this->renderPartial('_view_body', array('model'=>$model),true),
));