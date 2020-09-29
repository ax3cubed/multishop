<?php
$this->breadcrumbs=array(
	Sii::t('sii','Attributes'),
);

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create Attribute'),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
);
    
$this->spageindexWidget(array_merge(array('breadcrumbs'=>$this->breadcrumbs),
                                    array('menu'  => $this->menu),
                                    array('flash' => $this->modelType),
                                    $config));