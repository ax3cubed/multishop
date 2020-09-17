<?php 
$this->breadcrumbs = [
    Sii::t('sii','Tutorial Series')=>url('community/tutorials/series'),
    $model->localeName(user()->getLocale()),
];

$this->widget('common.widgets.spage.SPage',[
    'id'=>'tutorial_page',
    'layoutId'=>'view_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'homeLink'=>url('community'),
    'menu'=>$this->menu,
    'flash'  => false,
    'heading'=> [
        'name'=> $model->localeName(user()->getLocale()),
    ],
    'linebreak'=>false,
    'body'=>CHtml::tag('div',['class'=>'mobile-display-only'],$this->renderPartial('_view_siderbar',['model'=>$model],true)).
            $model->displayDescripion(user()->getLocale()).
            $this->widget('common.widgets.SListView', [
                'id' => 'tutorial_list',
                'dataProvider' => $model->searchTutorials(user()->getLocale()),
                'itemView' => 'common.modules.tutorials.views.series._tutorial',
                'viewData'=>['showStatus'=>false],
                'template' => '{items}',
                ],true).
            ($model->hasTags()?Sii::t('sii','Tagged in').'</span>'.Helper::htmlList($model->parseTags(),['class'=>'tags']):'').
            CHtml::tag('div',['class'=>'tutorial-comments-wrapper'],$this->renderPartial('../tutorials/_tutorial_comments',['dataProvider'=>$this->getCommentsDataProvider($model),'commentForm'=>$this->getCommentForm($model)],true)),
    'sidebars' => [
        SPageLayout::COLUMN_RIGHT=>[
            'content'=>$this->renderPartial('_view_siderbar',['model'=>$model],true),
            'cssClass'=> SPageLayout::WIDTH_20PERCENT,
        ],
    ],
]);


$this->smodalWidget();