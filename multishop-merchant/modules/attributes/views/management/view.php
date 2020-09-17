<?php $this->getModule()->registerGridViewCssFile();?>
<?php
$this->breadcrumbs=array(
	Sii::t('sii','Attributes')=>url('attributes'),
	$model->name,
    	Sii::t('sii','View')    
);

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create Attribute'),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
    array('id'=>'update','title'=>Sii::t('sii','Update Attribute'),'subscript'=>Sii::t('sii','update'), 'url'=>array('update', 'id'=>$model->id),'visible'=>$model->updatable()),
    array('id'=>'delete','title'=>Sii::t('sii','Delete Attribute'),'subscript'=>Sii::t('sii','delete'), 'visible'=>$model->deletable(), 
            'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),
                                 'onclick'=>'$(\'.page-loader\').show();',
                                 'confirm'=>Sii::t('sii','Are you sure you want to delete this {object}?',array('{object}'=>strtolower($model->displayName()))))),
);

$this->widget('common.widgets.spage.SPage',array(
    'id'=>'attribute',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->name,
    ),
    'body'=>$this->renderPartial('_view_body', array('model'=>$model),true),
    'sections'=>$this->getSectionsData($model),
));
