<?php cs()->registerScript('chosen-sitemap','$(\'.chzn-select-sitemap\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<div class="subform">
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'generateSitemap',['style'=>';margin-bottom:5px;']); ?>
        <?php echo CHtml::activeDropDownList($model,'generateSitemap',
            Helper::getBooleanValues(),
            ['prompt'=>'',
             'class'=>'chzn-select-sitemap',
             'data-placeholder'=>Sii::t('sii','Select Mode'),
             'style'=>'width:80px;']);
        ?>
    </div>
</div>