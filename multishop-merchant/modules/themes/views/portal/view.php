<?php

$this->renderPartial('_theme_header');

$this->breadcrumbs=[
    Sii::t('sii','Theme Store')=>url('themes'),
    $model->displayLanguageValue('name',user()->getLocale()),
];

$this->menu=[];

$extraSection = $this->renderPartial('_form_theme',[],true);

$this->widget('common.widgets.spage.SPage',[
    'id'=>'theme_view_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'hideHomeLink'=>true,
    'menu'=>$this->menu,
    'flash'  => get_class($model),
    'heading'=> false,
    'linebreak'=>false,
    'body'=>$this->renderPartial('_view_body',['model'=>$model,'extraSection'=>$extraSection],true),
]);

