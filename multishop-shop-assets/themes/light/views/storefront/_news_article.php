<?php 
$this->widget('common.widgets.SDetailView', [
    'data'=>$model,
    'htmlOptions'=>['class'=>'article list-box','id'=>'article_'.$model->id],
    'attributes'=> [
        [
            'type'=>'raw',
            'template'=>'{value}',
            'value'=>$this->widget('common.widgets.SDetailView', [
                'data'=>$model,
                'htmlOptions'=> ['class'=>'data'],
                'attributes'=> [
                    [
                        'type'=>'raw',
                        'template'=>'<div class="element date">{value}</div>',
                        'value'=>CHtml::encode($model->formatDatetime($model->create_time)),
                    ],         
                    [
                        'type'=>'raw',
                        'template'=>'<div class="element heading-element">{value}</div>',
                        'value'=>isset($link) 
                                   ? CHtml::link(CHtml::encode($model->displayLanguageValue('headline',user()->getLocale(),Helper::PURIFY)),$model->getUrl(request()->isSecureConnection))
                                   : CHtml::encode($model->displayLanguageValue('headline',user()->getLocale(),Helper::PURIFY))
                    ],
                    [
                        'type'=>'raw',
                        'template'=>'<div class="news-image">{value}</div>',
                        'value'=>$model->hasImage()?CHtml::image($model->imageOriginalUrl):'',
                    ],         
                    [
                        'type'=>'raw',
                        'template'=>'<div class="element content">{value}</div>',
                        'value'=>Helper::addNofollow($model->getMarkdownContent(user()->getLocale(),isset($length)?$length:null)),
                    ],
                ],
            ],true),
        ],        
    ],
]); 