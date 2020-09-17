<?php $this->module->registerFormCssFile(); ?>
<?php $this->module->registerGridViewCssFile(); ?>
<?php $this->module->registerChosen(); ?>
<?php $this->module->registerTab(); ?>
<?php $this->module->registerMediaGalleryAssets();?>
<?php

$this->breadcrumbs = array(
    Sii::t('sii', 'Shops') => url('shops'),
    $model->parseName(user()->getLocale()) => $model->viewUrl,
    Sii::t('sii', 'Update'),
);
$this->menu = array(
    //array('id'=>'save', 'title' => Sii::t('sii', 'Save Shop'), 'subscript' => Sii::t('sii', 'save'), 'linkOptions' => array('onclick' => 'submitform(\'shop-form\');', 'class' => 'primary-button')),
    array('id'=>'view', 'title' => Sii::t('sii', 'View Shop'), 'subscript' => Sii::t('sii', 'view'), 'url' => $model->viewUrl),
    array('id'=>'update', 'title' => Sii::t('sii', 'Update Shop'), 'subscript' => Sii::t('sii', 'update'), 'url' => array('update', 'id' => $model->id), 'linkOptions' => array('class' => 'active'), 'visible' => $model->updatable()),
    array('id'=>'activate','title'=>Sii::t('sii','Activate Shop'),'subscript'=>Sii::t('sii','activate'), 'visible'=>$model->activable()||$model->approval(), 
            'linkOptions'=>array('submit'=>url('shops/management/activate',array('Shop[id]'=>$model->id)),
                                 'onclick'=>'$(\'.page .page-loader\').show();',
                                 'confirm'=>Sii::t('sii','Are you sure you want to activate this {object}?',array('{object}'=>strtolower($model->displayName()))),
                                 //'class'=>'activate',
    )),
    array('id'=>'deactivate','title'=>Sii::t('sii','Deactivate Shop'),'subscript'=>Sii::t('sii','deactivate'), 'visible'=>$model->deactivable(), 
            'linkOptions'=>array('submit'=>url('shops/management/deactivate',array('Shop[id]'=>$model->id)),
                                 'onclick'=>'$(\'.page .page-loader\').show();',
                                 'confirm'=>Sii::t('sii','Are you sure you want to deactivate this {object}?',array('{object}'=>strtolower($model->displayName()))),
                                 //'class'=>'deactivate',
    )),
);

$this->getPage(array(
    'id' => 'shop_update_page',
    'cssClass'=>'bootstrap-page',//to enable support of bootstrap
    'breadcrumbs' => $this->breadcrumbs,
    'menu' => $this->menu,
    'flash' => get_class($model),
    'heading' => array(
        'name' => $model->parseName(user()->getLocale()),
        'tag' => $model->getStatusText(),
    ),
    'description' => Sii::t('sii','Edit your shop basic information. If shop is shown with status {online} means everyone can access your shop.',array('{online}'=>Process::getHtmlDisplayText(Process::SHOP_ONLINE))),
    'body' => $this->renderPartial('_update_body', array('model' => $model), true),
    ),$this->showSidebar()
);
