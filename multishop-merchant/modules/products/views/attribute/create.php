<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerGridViewCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php $this->module->registerScriptFile('attributes.assets.js','attributes.js');
      $this->module->registerCssFile('attributes.assets.css','attributes.css');
?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Create'),$this->getParentProduct());

$this->menu=array();

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> Sii::t('sii','Create Attribute'),
        //'superscript'=> $this->hasParentProduct()?$this->getParentProduct()->displayLanguageValue('name',user()->getLocale()):'',
        'image'=> $this->getParentProduct()->getImageThumbnail(Image::VERSION_XSMALL),        
    ),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));

//if (YII_DEBUG) {
    //echo CHtml::tag('div',array(),dump($model));
    //SActiveSession::debug($this->stateVariable);
//}