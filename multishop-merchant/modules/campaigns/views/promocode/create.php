<?php $this->getModule()->registerFormCssFile();?>
<?php $this->getModule()->registerChosen();?>
<?php
$this->breadcrumbs=$this->getBreadcrumbsData(Sii::t('sii','Create'));

$this->menu=array();

$this->getPage(array(
    'id'=>$this->modelType,
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> array(
        'name'=> Sii::t('sii','Create {object}',array('{object}'=>CampaignPromocode::model()->displayName())),
        'image'=> $this->hasParentShop()?$this->getParentShop()->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'width:'.Image::VERSION_XSMALL.'px;')):null,
    ),
    'description'=>Sii::t('sii','Customer can enter code to enjoy additional discount after order price.'),
    'body'=>$this->renderPartial('_form', array('model'=>$model),true),
));

