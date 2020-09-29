<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Import'));

$this->menu=array(
    array('id'=>'create','title'=>Sii::t('sii','Create {object}',array('{object}'=>Product::model()->displayName())),'subscript'=>Sii::t('sii','create'), 'url'=>array('create')),
    array('id'=>'import','title'=>Sii::t('sii','Import {object}',array('{object}'=>Product::model()->displayName())),'subscript'=>Sii::t('sii','import'), 'url'=>array('import')),
    array('id'=>'history','title'=>Sii::t('sii','Import History',array('{object}'=>Product::model()->displayName())),'subscript'=>Sii::t('sii','history'), 'url'=>url('product/management/import/history')),
);

$this->getPage(array(
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash' => $this->action->id,
    'heading'=> array(
        'name'=> Sii::t('sii','Product Import'),
        'image'=> $this->hasParentShop()?$this->getParentShop()->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'max-width:120px;max-height:80px;')):null,
    ),
    'body'=>$this->widget('common.widgets.SDetailView', array(
        'data'=>array('summary','attachment','create_time'),
        'columns'=>array(
            array(
                array('label'=>$model->getAttributeLabel('total_count'),'type'=>'raw','value'=>$model->count),
            ),
            array(
                array('label'=>$model->getAttributeLabel('uploaded_file'),'type'=>'raw','value'=>Helper::htmlDownloadLink($model->attachment,$this->getAssetsUrl('common.assets.images'))),
                array('label'=>$model->getAttributeLabel('create_time'),'value'=>$model->formatDatetime($model->create_time,true)),
            ),
        ),
    ),true),
));
