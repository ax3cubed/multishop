<?php $form=$this->beginWidget('CActiveForm', array('id'=>'inventory-settings-form','action'=>url('shop/settings/notifications?service='.Feature::$receiveLowStockAlert))); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="subform">
        
        <div class="row">
            <?php echo CHtml::activeLabelEx($model,'lowInventory',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo CHtml::activeDropDownList($model,'lowInventory', 
                                            Helper::getBooleanValues(), 
                                            array('prompt'=>'',
                                                  'class'=>'chzn-select-inventory',
                                                  'data-placeholder'=>Sii::t('sii','Select Notification'),
                                                  'style'=>'width:80px;'));
            ?>
            <?php echo CHtml::error($model,'lowInventory'); ?>
        </div>
        
        <div class="row" style="margin-top:15px">
            <?php echo CHtml::activeLabelEx($model,'lowInventoryThreshold',array('style'=>'margin-bottom:3px;')); ?>
            <span style="margin-right: 5px;"><?php echo Sii::t('sii','Below');?></span>
            <?php echo CHtml::activeTextField($model,'lowInventoryThreshold',array('size'=>8,'maxlength'=>4)); ?>
            <?php $this->stooltipWidget($model->getToolTip('lowInventoryThreshold')); ?>
            <?php echo CHtml::error($model,'lowInventoryThreshold'); ?>
        </div>
        
        <div class="row" style="padding-top:20px;clear:left">
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name'=>'actionButtonFormInventory',
                        'buttonType'=>'button',
                        'caption'=>Sii::t('sii','Save'),
                        'value'=>'actionbtninventory',
                        'onclick'=>'js:function(){servicepostcheck("inventory-settings-form");}',
                    )
                );
            ?>
        </div> 
        
    </div>

<?php $this->endWidget(); ?>