<?php cs()->registerScript('chosen-locale','$(\'.chzn-select\').chosen();$(\'.chzn-select-currency\').chosen();$(\'.chzn-select-weight\').chosen();',CClientScript::POS_END);?>
<?php cs()->registerScript('chosen-locale2','$(\'#Shop_timezone_chzn .chzn-search\').show();',CClientScript::POS_END);?>
<?php if ($model instanceof ShopForm && !$model->isNewRecord && !$model->prototype())
        cs()->registerScript('chosen-locale3','$(\'.chzn-select-currency\').attr(\'disabled\', true).trigger("liszt:updated");$(\'.chzn-select-weight\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
?>
<div class="subform">  
    <div>
        <?php echo CHtml::activeLabelEx($model, 'timezone',array('style'=>'margin-bottom:3px;')); ?>
        <?php echo Chtml::activeDropDownList($model,
                                             'timezone',
                                             SLocale::getTimeZones(), 
                                             array('prompt'=>'',
                                                   'class'=>'chzn-select locale',
                                                   'data-placeholder'=>Sii::t('sii','Select {field}',array('{field}'=>$model->getAttributeLabel('timezone'))),
                                                ));
        ?>
        <?php $this->stooltipWidget($model->getToolTip('timezone')); ?>
        <?php echo Chtml::error($model,'timezone'); ?>
    </div>
    <div>
        <?php echo CHtml::activeLabelEx($model, 'language',array('style'=>'margin-bottom:3px;')); ?>
        <?php echo Chtml::activeDropDownList($model,
                                             'language',
                                             SLocale::getLanguages(), 
                                             array('prompt'=>'',
                                                   'class'=>'chzn-select locale',
                                                   'data-placeholder'=>Sii::t('sii','Select {field}',array('{field}'=>$model->getAttributeLabel('language'))),
                                                ));
        ?>
        <?php $this->stooltipWidget($model->getToolTip('language')); ?>
        <?php echo Chtml::error($model,'language'); ?>
    </div>
    <div>
        <?php echo CHtml::activeLabelEx($model, 'currency',array('style'=>'margin-bottom:3px;')); ?>
        <?php echo Chtml::activeDropDownList($model,
                                             'currency',
                                             SLocale::getCurrencies(), 
                                             array('prompt'=>'',
                                                   'class'=>'chzn-select-currency locale',
                                                   'data-placeholder'=>Sii::t('sii','Select {field}',array('{field}'=>$model->getAttributeLabel('currency'))),
                                                ));
        ?>
        <?php $this->stooltipWidget($model->getToolTip('currency')); ?>
        <?php echo Chtml::error($model,'currency'); ?>
    </div>
    <div>
        <?php echo CHtml::activeLabelEx($model, 'weight_unit',array('style'=>'margin-bottom:3px;')); ?>
        <?php echo Chtml::activeDropDownList($model,
                                             'weight_unit',
                                             SLocale::getWeightUnits(), 
                                             array('prompt'=>'',
                                                   'class'=>'chzn-select-weight locale',
                                                   'data-placeholder'=>Sii::t('sii','Select {field}',array('{field}'=>$model->getAttributeLabel('weight_unit'))),
                                                ));
        ?>
        <?php $this->stooltipWidget($model->getToolTip('weight_unit')); ?>
        <?php echo Chtml::error($model,'weight_unit'); ?>
    </div>
</div>