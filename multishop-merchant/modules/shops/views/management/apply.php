<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php
$this->breadcrumbs=array(
	Sii::t('sii','Shops')=>url('shops'),
	Sii::t('sii','Apply'),
);

$this->menu=array(
//    array('id'=>'apply','title'=>Sii::t('sii','Apply Shop'),'subscript'=>Sii::t('sii','apply'), 'linkOptions'=>array('onclick'=>'submitform(\'shop-form\');','class'=>'primary-button')),
);

$this->widget('common.widgets.spage.SPage',array(
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash' => get_class($model),
    'heading'=> array(
        'name'=> Sii::t('sii','Apply Shop'),
    ),
    'body'=>$this->renderPartial($model->formView, array('model'=>$model),true),
));