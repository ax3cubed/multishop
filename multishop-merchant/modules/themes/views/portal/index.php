<?php

$this->renderPartial('_theme_header');

$this->widget('common.widgets.spage.SPage',[
    'id' => 'themes_page',
    'breadcrumbs'=>[],
    'menu'=>[],
    'flash'=>false,
    'heading'=> false,
    'linebreak'=>false,
    'body'=>$this->widget('common.widgets.SListView', [
                'dataProvider' => $dataProvider,
                'htmlOptions' => ['class'=>'themes-portal-listing themes-container'],
                'itemView' => '_theme_listview',
            ],true),
]);