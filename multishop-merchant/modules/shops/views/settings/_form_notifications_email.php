<?php $form=$this->beginWidget('CActiveForm', array('id'=>'email-settings-form','action'=>url('shop/settings/notifications?service='.Feature::$hasEmailTemplateConfigurator))); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo CHtml::hiddenField('Subscription[service]',Feature::$hasEmailTemplateConfigurator);?>

    <div class="subform">
        
        <div class="row">
            <?php echo CHtml::activeLabelEx($model,'emailSenderName',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo CHtml::activeTextField($model,'emailSenderName',array('size'=>50,'maxlength'=>100)); ?>
            <?php echo CHtml::error($model,'emailSenderName'); ?>
        </div>
        
        <div class="row" style="padding-top:20px;clear:left">
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name'=>'actionButtonEmail',
                        'buttonType'=>'button',
                        'caption'=>Sii::t('sii','Save'),
                        'value'=>'actionbtnemail',
                        'onclick'=>'js:function(){servicepostcheck("email-settings-form");}',
                    )
                );
            ?>
        </div> 
        
    </div>

<?php $this->endWidget(); ?>