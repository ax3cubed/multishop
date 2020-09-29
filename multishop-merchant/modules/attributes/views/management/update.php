<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerGridViewCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php
$this->breadcrumbs=array(
	Sii::t('sii','Attributes')=>url('attributes'),
    	$model->name=>$model->viewUrl,
	Sii::t('sii','Update'),
);

$this->menu=array(
    array('id'=>'save','title'=>Sii::t('sii','Save Attribute'),'subscript'=>Sii::t('sii','save'), 'linkOptions'=>array('onclick'=>'submitform(\'attribute-form\');','class'=>'primary-button')),
    array('id'=>'create','title'=>Sii::t('sii','Create Attribute'),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
    array('id'=>'view','title'=>Sii::t('sii','View Attribute'),'subscript'=>Sii::t('sii','view'), 'url'=>$model->viewUrl),
);

$this->widget('common.widgets.spage.SPage',array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->name,
    ),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));

if (YII_DEBUG) {
    echo 'session objects<br>'.dump(SActiveSession::load($this->stateVariable));   
}
