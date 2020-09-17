<?php $form=$this->beginWidget('CActiveForm', array('id'=>'facebook_messenger_settings_form','action'=>url('shop/settings/chatbot?service='.Feature::$integrateFacebookMessenger))); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="subform">
        
        <?php echo CHtml::activeHiddenField($model,'fbBotClientId'); ?>
        <?php echo CHtml::hiddenField('Subscription[service]',Feature::$integrateFacebookMessenger);?>
        
        <p class="notes">
            <?php echo Sii::t('sii','Note: Use the Callback URL and Verify Token to create an event in the Facebook Messenger Webhook Setup.'); ?>    
        </p>

        <div class="row" style="margin-top:15px">
            <?php echo CHtml::activeLabelEx($model,'fbCallbackUrl'); ?>
            <?php echo CHtml::activeTextField($model,'fbCallbackUrl',['style'=>'width:90%;cursor:not-allowed','class'=>'disabled','disabled'=>true]); ?>
            <?php echo CHtml::activeHiddenField($model,'fbCallbackUrl'); ?>
            <?php echo CHtml::error($model,'fbCallbackUrl'); ?>
        </div>
        
        <div class="row" style="margin-top:15px">
            <?php echo CHtml::activeLabelEx($model,'fbVerifyToken'); ?>
            <?php echo CHtml::activeTextField($model,'fbVerifyToken',['style'=>'width:90%','placeholder'=>$model->getToolTip('fbVerifyToken')]); ?>
            <?php echo CHtml::error($model,'fbVerifyToken'); ?>
        </div>
                
        <div class="row" style="margin-top:15px">
            <?php echo CHtml::activeLabelEx($model,'fbPageAccessToken'); ?>
            <?php echo CHtml::activeTextField($model,'fbPageAccessToken',['style'=>'width:90%','placeholder'=>$model->getToolTip('fbPageAccessToken')]); ?>
            <?php echo CHtml::error($model,'fbPageAccessToken'); ?>
        </div>

        <div class="row">
            <?php echo CHtml::activeLabelEx($model,'fbVerifyRequestSignature',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo CHtml::activeDropDownList($model,'fbVerifyRequestSignature', 
                                            Helper::getBooleanValues(), 
                                            ['prompt'=>'',
                                                  'class'=>'chzn-select-verify-signature',
                                                  'data-placeholder'=>Sii::t('sii','Select Yes or No'),
                                                  'style'=>'width:80px;']);
            ?>
            <?php $this->stooltipWidget($model->getToolTip('fbVerifyRequestSignature')); ?>
            <?php echo CHtml::error($model,'fbVerifyRequestSignature'); ?>
        </div>
        
        <div class="row" style="margin-top:15px">
            <?php echo CHtml::activeLabelEx($model,'fbSecret'); ?>
            <?php echo CHtml::activeTextField($model,'fbSecret',['style'=>'width:60%','placeholder'=>$model->getToolTip('fbSecret')]); ?>
            <?php echo CHtml::error($model,'fbSecret'); ?>
        </div>
        
        <div class="row" style="padding-top:20px;clear:left">
            <?php $this->widget('zii.widgets.jui.CJuiButton',[
                        'name'=>'actionButtonFormFacebook',
                        'buttonType'=>'button',
                        'caption'=>Sii::t('sii','Save'),
                        'value'=>'actionbtnfacebook',
                        'onclick'=>'js:function(){servicepostcheck("facebook_messenger_settings_form");}',
                        'htmlOptions'=>['class'=>'ui-button'],
                    ]);
            ?>
            <?php 
                if ($model->hasFbChatbot) {
                    $model->fbPluginForm->renderForm();
                    $model->fbSupportForm->renderForm();
                    $model->fbAdvancedForm->renderForm();
                }
            ?>
        </div> 
        
    </div>

<?php $this->endWidget();