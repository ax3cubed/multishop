<?php 
$this->widget('common.widgets.SDetailView', [
    'data'=>$data,
    'htmlOptions'=>['class'=>'article list-box','id'=>'article_'.$data->id],
    'attributes'=> [
        [
            'type'=>'raw',
            'template'=>'{value}',
            'value'=>$this->widget('common.widgets.SDetailView', [
                'data'=>$data,
                'htmlOptions'=> ['class'=>'data'],
                'attributes'=> [
                    [
                        'type'=>'raw',
                        'template'=>'<div class="news-image">{value}</div>',
                        'value'=>$data->hasImage()?CHtml::image($data->imageOriginalUrl):'',
                    ],         
                    [
                        'type'=>'raw',
                        'template'=>'<div class="element heading-element">{value}</div>',
                        'value'=>isset($link) 
                                   ? CHtml::link(CHtml::encode($data->displayLanguageValue('headline',user()->getLocale(),Helper::PURIFY)),$data->getUrl(request()->isSecureConnection))
                                   : CHtml::encode($data->displayLanguageValue('headline',user()->getLocale(),Helper::PURIFY))
                    ],
                    [
                        'type'=>'raw',
                        'template'=>'<div class="element date">{value}</div>',
                        'value'=>CHtml::encode($data->formatDatetime($data->create_time)),
                    ],         
                    [
                        'type'=>'raw',
                        'template'=>'<div class="element content">{value}</div>',
                        'value'=>Helper::addNofollow($data->getMarkdownContent(user()->getLocale(),isset($length)?$length:null)),
                    ],
                ],
            ],true),
        ],        
    ],
]); 