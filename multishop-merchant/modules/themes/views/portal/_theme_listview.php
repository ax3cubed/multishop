<div class="list-box">
<?php 
    $this->widget('common.widgets.SDetailView', [
        'data'=>$data,
        'htmlOptions'=>[
            'class'=>'data',
            'data-tid'=>$data->id,
            'data-theme'=>$data->theme,
        ],
        'attributes'=>[
            [
                'type'=>'raw',
                'template'=>'<div class="image-element">{value}</div>',
                'value'=>CHtml::link($this->getThemeStoreImage($data),$data->themeStoreUrl),
            ],
            [
                'type'=>'raw',
                'template'=>'<div class="element">{value}</div>',
                'value'=>CHtml::tag('h3',['class'=>'theme-info name'],$data->displayLanguageValue('name',user()->getLocale())).
                         CHtml::tag('span',['class'=>'theme-info total-styles'],Sii::t('sii','n<=1#{n} Style|n>1#{n} Styles',[$data->totalStyles])).
                         CHtml::tag('span',['class'=>'theme-info price'],$data->formatPrice()),
            ],
        ],
    ]); 
?> 
</div>