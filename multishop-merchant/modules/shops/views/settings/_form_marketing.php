<?php cs()->registerScript('chosen-share','$(\'.chzn-select-share\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<div class="subform" style="vertical-align: top;">
    <div class="subform-column">
        <div class="row" style='margin-top: 15px;'>
            <?php echo CHtml::activeLabelEx($model,'socialMediaShare',array('style'=>';margin-bottom:5px;')); ?>
            <?php echo CHtml::activeDropDownList($model,'socialMediaShare', 
                                            Helper::getBooleanValues(), 
                                            array('prompt'=>'',
                                                  'class'=>'chzn-select-share',
                                                  'data-placeholder'=>Sii::t('sii','Select Mode'),
                                                  'style'=>'width:80px;'));
            ?>
            <?php echo CHtml::error($model,'socialMediaShare'); ?>
        </div>
    </div>
</div>