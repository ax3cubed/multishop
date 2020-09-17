<?php 
$this->breadcrumbs = [
    Sii::t('sii','Tutorials')=>url('community/tutorials'),
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
        //'superscript'=> $this->getSeriesTags($model),
    ],
    'linebreak'=>false,
    'body'=>CHtml::tag('div',['class'=>'mobile-display-only'],$this->renderPartial('_view_siderbar',['model'=>$model],true)).
            $model->displayContent(user()->getLocale()).
            CHtml::tag('div',['class'=>'doc-end-line'],'<i class="fa fa-angle-double-left"></i> '.Sii::t('sii','End').' <i class="fa fa-angle-double-right"></i>').
            ($model->hasTags()?Sii::t('sii','Tagged in').'</span>'.Helper::htmlList($model->parseTags(),['class'=>'tags tutorial-tags-wrapper']):'').
            ($model->hasSeries()?CHtml::tag('div',['class'=>'tutorial-series-wrapper'],$this->renderPartial('_tutorial_series',['dataProvider'=>$model->searchSeries(),'currentTutorial'=>$model->id],true)):'').
            CHtml::tag('div',['class'=>'tutorial-comments-wrapper'],$this->renderPartial('_tutorial_comments',['dataProvider'=>$this->getCommentsDataProvider($model),'commentForm'=>$this->getCommentForm($model)],true)),
    'sidebars' => [
        SPageLayout::COLUMN_RIGHT=>[
            'content'=>$this->renderPartial('_view_siderbar',['model'=>$model],true),
            'cssClass'=> SPageLayout::WIDTH_20PERCENT,
        ],
    ],
]);

$this->smodalWidget();