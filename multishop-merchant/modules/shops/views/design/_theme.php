<div class="list-box">
    <?php 
$updateBtn = $this->getThemeButtonLabel($owner,$data);
$defaultStyle = $owner->getTheme()==$data->theme ? $owner->getThemeStyle() : $data->style->id;
$this->widget('common.widgets.SDetailView', [
    'data'=>$data,
    'htmlOptions'=>[
        'class'=>'data',
        'data-tid'=>$data->id,
        'data-theme'=>$data->theme,
        'data-current-style'=>$owner->getThemeStyle(),
        'data-preview-url'=>$this->getThemePreviewUrl($owner,$data),
        'data-choose-url'=>$this->getThemeUpdateUrl($owner),
    ],
    'attributes'=>[
        [
            'type'=>'raw',
            'template'=>'<div class="image-element">{value}</div>',
            'value'=>$this->getThemeImages($data,$owner->getThemeStyle()),
        ],
        [
            'type'=>'raw',
            'template'=>'<div class="element">{value}</div>',
            'value'=>CHtml::tag('h3',['class'=>'theme-info name'],$data->displayLanguageValue('name',user()->getLocale())).
                     CHtml::tag('span',['class'=>'theme-info total-styles'],Sii::t('sii','n<=1#{n} Style|n>1#{n} Styles',[$data->totalStyles])).
                     CHtml::tag('span',['class'=>'theme-info price'],$data->formatPrice()),
        ],
        [
            'type'=>'raw',
            'template'=>'<div class="element">{value}</div>',
            'value'=>CHtml::radioButtonList($data->id.'_style',$defaultStyle,$this->getThemeStyleSelections($data),[
                        'template'=>'<div class="theme-selection">{input}{label}</div>',
                        'separator'=>'',
                    ]),
        ],
        [
            'type'=>'raw',
            'template'=>'<div class="button-element">{value}</div>',
            'value'=>CHtml::button($updateBtn['label'],['class'=>'ui-button update '.$updateBtn['cssClass'].' '.$data->style->id]).
                     ($updateBtn['current']?CHtml::button(Sii::t('sii','Choose'),['style'=>'display:none;','class'=>'ui-button update publish alternate']):'').
                     CHtml::button(Sii::t('sii','Preview'),['class'=>'ui-button preview']),
        ],
    ],
]); 
?> 
</div>