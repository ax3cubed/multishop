<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'billing_settings_form',
            'action'=>url('billing/settings'),
    )); ?>
    
        <div class="row">
            <?php echo $form->labelEx($model,'billed_to'); ?>
            <?php echo $form->textField($model,'billed_to',array('size'=>60,'maxlength'=>100)); ?>
            <?php $this->stooltipWidget($model->getToolTip('billed_to')); ?>
            <?php echo $form->error($model,'billed_to'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
            <?php $this->stooltipWidget($model->getToolTip('email')); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>
    
        <div class="row" style="margin-top:20px;">
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'id'=>'settingsButton',
                        'name'=>'actionButton',
                        'caption'=> Sii::t('sii','Save'),
                        'value'=>'actionbtn',
                    )
                );
             ?>
        </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->