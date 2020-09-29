<?php 
$this->breadcrumbs = [
    Sii::t('sii','Questions')=>url('community/questions'),
    $model->title,
];

$this->widget('common.widgets.spage.SPage',[
    'id'=>'question_page',
    'layoutId'=>'view_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'homeLink'=>url('community'),
    'menu'=>$this->menu,
    'flash'  => false,
    'heading'=> [
        'name'=> $model->title,
    ],
    'linebreak'=>false,
    'body'=> CHtml::tag('div',['class'=>'mobile-display-only'],$this->renderPartial('_view_siderbar',['model'=>$model],true)).
            '<i class="fa fa-quote-left"></i>'.Helper::purify($model->question).'<i class="fa fa-quote-right"></i>'.
            CHtml::tag('div',['class'=>'question-answers-wrapper'],$this->renderPartial('_question_answers',['dataProvider'=>$this->getCommentsDataProvider($model),'commentForm'=>$this->getCommentForm($model)],true)),
    'sidebars' => [
        SPageLayout::COLUMN_RIGHT=>[
            'content'=>$this->renderPartial('_view_siderbar',['model'=>$model],true),
            'cssClass'=> SPageLayout::WIDTH_20PERCENT,
        ],
    ],
]);

$this->smodalWidget();