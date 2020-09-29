<?php $form=$this->beginWidget('CActiveForm', array('id'=>'order-ponum-settings-form','action'=>url('shop/settings/orders?service='.Feature::$customizeOrderNumber))); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo CHtml::hiddenField('Subscription[service]',Feature::$customizeOrderNumber);?>

    <div class="subform">
        
        <div class="row" style="margin-top:15px;">
            <?php echo CHtml::activeLabelEx($model,'poNumRandomString',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo CHtml::activeDropDownList($model,'poNumRandomString', 
                                            $model->getOrderNumRandomStringOptions(), 
                                            array('prompt'=>'',
                                                  'class'=>'chzn-select-po-random',
                                                  'data-placeholder'=>Sii::t('sii','Select Fashion'),
                                                  'style'=>'width:320px;'));
            ?>
            <?php echo CHtml::error($model,'poNumRandomString'); ?>
        </div>

        <div class="row" style="margin-top:15px;">
            <?php echo CHtml::activeLabelEx($model,'poNumSeparator',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo CHtml::activeDropDownList($model,'poNumSeparator', 
                                            $model->getOrderNumSeparatorOptions(), 
                                            array('prompt'=>'',
                                                  'class'=>'chzn-select-po-separator',
                                                  'data-placeholder'=>Sii::t('sii','Select Separator'),
                                                  'style'=>'width:40px;'));
            ?>
            <?php echo CHtml::error($model,'poNumSeparator'); ?>
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model,'poNumPrefix',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo CHtml::activeTextField($model,'poNumPrefix',array('size'=>3,'maxlength'=>2)); ?>
            <?php echo CHtml::error($model,'poNumPrefix'); ?>
        </div>
        
        <div class="row">
            <?php echo CHtml::activeLabelEx($model,'poNumTemplate',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo CHtml::activeTextField($model,'poNumTemplate',array('size'=>60,'maxlength'=>60)); ?>
            <?php echo CHtml::error($model,'poNumTemplate'); ?>
        </div>
        
        <p class="note">
            <?php echo OrderNumberGenerator::example(Order::model(),$model->poNumTemplate,$model->poNumRandomString,$model->poNumSeparator,$model->poNumPrefix);?>
        </p>
        
        <div class="row" style="padding-top:20px;clear:left">
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name'=>'actionButtonPONum',
                        'buttonType'=>'button',
                        'caption'=>Sii::t('sii','Save'),
                        'value'=>'actionbtnponum',
                        'onclick'=>'js:function(){servicepostcheck("order-ponum-settings-form");}',
                    )
                );
            ?>
        </div> 
        
    </div>

<?php $this->endWidget(); ?>