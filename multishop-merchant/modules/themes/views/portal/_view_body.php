<div class="image column">
    <?php
        echo $this->getThemeImages($model,$model->style->id);
    ?>
</div>
<div class="data column">
    <h1><?php echo $model->displayLanguageValue('name',user()->getLocale());?></h1>
    <p><?php echo $model->displayLanguageValue('desc',user()->getLocale());?></p>
    <div class="info">
        <span class="price">
            <?php echo $model->formatPrice(); ?>
        </span>
        <span class="total-styles">
            <?php echo Sii::t('sii','n<=1#{n} Style included|n>1#{n} Styles included',[$model->totalStyles]); ?>
        </span>
    </div>
    <div class="selections">
        <?php echo CHtml::radioButtonList($model->id.'_style',$model->style->id,$this->getThemeStyleSelections($model),[
                        'template'=>'<div class="style-selection">{input}{label}</div>',
                        'separator'=>'',
                    ]);
        ?>
    </div>
    
    <?php 
        if (isset($extraSection))
            echo $extraSection;
    ?>

</div>

<?php
$script = <<<EOJS
$('.style-selection input:radio').click(function(){
    $('.style-image').hide();
    $('.style-image.'+$(this).val()).show();
});
EOJS;
Helper::registerJs($script);