<?php $form=$this->beginWidget('CActiveForm', array('id'=>'order-sonum-settings-form','action'=>url('shop/settings/orders?service='.Feature::$customizeOrderNumber))); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo CHtml::hiddenField('Subscription[service]',Feature::$customizeOrderNumber);?>

    <div class="subform">
        
        <div class="row" style="margin-top:15px;">
            <?php echo CHtml::activeLabelEx($model,'soNumRandomString',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo CHtml::activeDropDownList($model,'soNumRandomString', 
                                            $model->getOrderNumRandomStringOptions(), 
                                            array('prompt'=>'',
                                                  'class'=>'chzn-select-so-random',
                                                  'data-placeholder'=>Sii::t('sii','Select Fashion'),
                                                  'style'=>'width:320px;'));
            ?>
            <?php echo CHtml::error($model,'soNumRandomString'); ?>
        </div>
        
        <div class="row" style="margin-top:15px;">
            <?php echo CHtml::activeLabelEx($model,'soNumSeparator',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo CHtml::activeDropDownList($model,'soNumSeparator', 
                                            $model->getOrderNumSeparatorOptions(), 
                                            array('prompt'=>'',
                                                  'class'=>'chzn-select-so-separator',
                                                  'data-placeholder'=>Sii::t('sii','Select Separator'),
                                                  'style'=>'width:40px;'));
            ?>
            <?php echo CHtml::error($model,'soNumSeparator'); ?>
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model,'soNumPrefix',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo CHtml::activeTextField($model,'soNumPrefix',array('size'=>3,'maxlength'=>2)); ?>
            <?php echo CHtml::error($model,'soNumPrefix'); ?>
        </div>
        
        <div class="row">
            <?php echo CHtml::activeLabelEx($model,'soNumTemplate',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo CHtml::activeTextField($model,'soNumTemplate',array('size'=>60,'maxlength'=>60)); ?>
            <?php echo CHtml::error($model,'soNumTemplate'); ?>
        </div>

        <p class="note">
            <?php echo OrderNumberGenerator::example(ShippingOrder::model(),$model->soNumTemplate,$model->soNumRandomString,$model->soNumSeparator,$model->soNumPrefix);?>
        </p>
        
        <div class="row" style="padding-top:20px;clear:left">
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name'=>'actionButtonSONum',
                        'buttonType'=>'button',
                        'caption'=>Sii::t('sii','Save'),
                        'value'=>'actionbtnsonum',
                        'onclick'=>'js:function(){servicepostcheck("order-sonum-settings-form");}',
                    )
                );
            ?>
        </div> 
        
    </div>

<?php $this->endWidget(); ?>