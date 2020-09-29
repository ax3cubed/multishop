<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerGridViewCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php
$this->breadcrumbs=array(
	Sii::t('sii','Attributes')=>url('attributes'),
	Sii::t('sii','Create'),
);

$this->menu=array();

$this->widget('common.widgets.spage.SPage',array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> Sii::t('sii','Create Attribute'),
    ),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));

if (YII_DEBUG) {
    echo 'session objects<br>'.dump(SActiveSession::load($this->stateVariable));   
}