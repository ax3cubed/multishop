<?php $form=$this->beginWidget('CActiveForm', array('id'=>'order-settings-form','action'=>url('shop/settings/orders?service='.Feature::$processOrders))); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo CHtml::hiddenField('Subscription[service]',Feature::$processOrders);?>

    <div class="subform">
        
        <div class="row">
            <?php echo CHtml::activeLabelEx($model,'processEachItems',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo CHtml::activeDropDownList($model,'processEachItems', 
                                            $model->getItemProcessingOptions(), 
                                            array('prompt'=>'',
                                                  'class'=>'chzn-select-process-items',
                                                  'data-placeholder'=>Sii::t('sii','Select Mode'),
                                                  'style'=>'width:320px;'));
            ?>
            <?php echo CHtml::error($model,'processEachItems'); ?>
        </div>
        
        <div class="row" style="padding-top:20px;clear:left">
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name'=>'actionButtonProcessItems',
                        'buttonType'=>'button',
                        'caption'=>Sii::t('sii','Save'),
                        'value'=>'actionbtnprocessitems',
                        'onclick'=>'js:function(){servicepostcheck("order-settings-form");}',
                    )
                );
            ?>
        </div> 
        
    </div>

<?php $this->endWidget(); ?>