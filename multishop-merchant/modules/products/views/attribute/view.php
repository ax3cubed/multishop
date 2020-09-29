<?php $this->getModule()->registerGridViewCssFile();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','View'),$this->getParentProduct());
$this->menu=$this->getPageMenu($model,$this->action->id,array(
        'deleteUrl'=>url('product/attribute/delete/id/'.$model->id),
    ));

$this->getPage(array(
    'id'=>'attribute',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->displayLanguageValue('name',user()->getLocale()),
        'tag'=> $model->getShareText(),
        //'superscript'=> $this->getParentProduct()->displayLanguageValue('name',user()->getLocale()),
        'image'=> $this->getParentProduct()->getImageThumbnail(Image::VERSION_XSMALL),
    ),
    'body'=>$this->renderPartial('_view_body', array('model'=>$model),true),
    'sections'=>$this->getSectionsData($model),
));
