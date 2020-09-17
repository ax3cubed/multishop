<div id="chatbot_support_form_wrapper" class="form">
    
    <div class="row message"><!-- To input return message --></div>
    
    <?php echo CHtml::activeHiddenField($model,'clientId'); ?>

    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'support'); ?>
        <?php echo CHtml::activeCheckBox($model,'support',['style'=>'width:auto']); ?>
            <?php echo $model->getToolTip('support'); ?>
        <?php echo CHtml::error($model,'support'); ?>
    </div>
 
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'workingDays'); ?>
        <?php 
            foreach (ChatbotSupport::getWorkingDaysArray() as $key => $value) {
                echo CHtml::checkBox('ChatbotSupportForm[workingDays]['.$key.']', isset($model->workingDays[$key])?$model->workingDays[$key]:false, ['style'=>'width:auto']);
                echo CHtml::tag('span',['style'=>'padding:0px 5px'],$value);
            }
        ?>
        <?php echo CHtml::error($model,'workingDays'); ?>
    </div>
    
    <div class="row">
        <label><?php echo $model->getToolTip('workingHours'); ?>
        </label>
        <p>
            <?php echo $model->getToolTip('workingHoursTip'); ?>
        </p>
        <div class="column">
            <?php echo CHtml::activeLabelEx($model,'openTime'); ?>
            <?php echo CHtml::activeTextField($model,'openTime',['style'=>'','maxlength'=>4]); ?>
            <?php echo CHtml::error($model,'openTime'); ?>
        </div>
        <div class="column">
            <?php echo CHtml::activeLabelEx($model,'closeTime'); ?>
            <?php echo CHtml::activeTextField($model,'closeTime',['style'=>'','maxlength'=>4]); ?>
            <?php echo CHtml::error($model,'closeTime'); ?>
        </div>

    </div>

    <div class="row">
        <div class="column">
            <label><?php echo $model->getToolTip('agentAssignment'); ?>
                   <span class="required">*</span>
            </label>
            <?php if ($model->hasAgent)
                      echo CHtml::tag('p', ['class'=>'registered-agent'], Sii::t('sii','Registered agent: {agent}',['{agent}'=>$model->agentId]));
            ?>
            <p>
                <?php echo CHtml::encode($model->getToolTip('agentsSignupTip')); ?>
            </p>
            <?php   $this->widget('common.extensions.facebook.messenger.SendToMessenger',[
                        'appId'=>$model->chatbot->getOwner(true)->messenger->messengerAppId,
                        'pageId'=>$model->chatbot->getOwner(true)->messenger->messengerPageId,
                        'dataRef'=>$model->getPayload(user()->getId()),
                        'clickedCallback'=>$model->getMessengerCallbackScript(),
                    ]);
            ?>
        </div>
        <div class="column">
            <?php echo CHtml::activeLabelEx($model,'agentName'); ?>
            <p>
                <?php echo CHtml::encode($model->getToolTip('agentNameTip')); ?>
            </p>
            <?php echo CHtml::activeTextField($model,'agentName',['style'=>'','maxlength'=>20,'placeholder'=>$model->getToolTip('agentName')]); ?>
            <?php echo CHtml::error($model,'agentName'); ?>
            
        </div>
    </div>
        
</div>