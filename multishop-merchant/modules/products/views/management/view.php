<?php $this->getModule()->registerGridViewCssFile();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','View'),$model);
$this->menu=$this->getPageMenu($model,$this->action->id,array(
        'activateUrl'=>url('products/management/activate',array('Product[id]'=>$model->id)),
        'deactivateUrl'=>url('products/management/deactivate',array('Product[id]'=>$model->id)),
        'extraMenu'=>array(
            array('id'=>'attribute','title'=>Sii::t('sii','Manage Attributes'),'subscript'=>Sii::t('sii','attribute'), 'url'=>url('product/attribute')),
            array('id'=>'inventory','title'=>Sii::t('sii','Manage Inventories'),'subscript'=>Sii::t('sii','inventory'), 'url'=>url('inventories/management/index',array('option'=>$this->pageViewOption,'product'=>$model->id))),
        ),
    ));

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> $model->displayLanguageValue('name',user()->getLocale()),
        'tag'=> $model->getStatusText(),
        'image'=> $model->brand==null?null:$model->brand->getImageThumbnail(Image::VERSION_XSMALL),
        'superscript'=> $model->getInventoryText(),
    ),
    'description' => Sii::t('sii','Product with status {online} means everyone can access and make purchase your product.',array('{online}'=>Process::getHtmlDisplayText(Process::SHOP_ONLINE))),
    'body'=>$this->renderPartial('_view_body', array('model'=>$model),true),
    'sections'=>$this->getSectionsData($model),
));
    
