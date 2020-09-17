<div id="chatbot_settings_form_wrapper" class="form">
    
    <div class="row message"><!-- To input return message --></div>
    
    <?php echo CHtml::activeHiddenField($model,'clientId'); ?>

    <div class="row greetingText">
        
        <?php echo CHtml::activeLabelEx($model,'greetingText',['style'=>'margin-bottom:3px;']); ?>
        
        <p>
            <?php echo Sii::t('sii','You can set a greeting for new conversations. This can be used to communicate your bot\'s functionality. If the greeting text is not set, the page description will be shown in the welcome screen. You can personalize the text with the person\'s name.');?>
        </p>
        
        <p>
            <i><?php echo 'Use {{user_first_name}}, {{user_last_name}} or {{user_full_name}}';?></i>
        </p>
        
        <?php echo CHtml::activeTextArea($model,'greetingText',['rows'=>3,'style'=>'width:100%','maxlength'=>$model::$greetingTextLengthLimit,'placeholder'=>$model->getToolTip('greetingText')]); ?>
        <?php echo CHtml::error($model,'greetingText'); ?>

        <button type="button" class="btn btn-success btn-save"><?php echo Sii::t('sii','Save');?></button>    

        <div class="send-button-wrapper">
            <p class="last-sent-time">
                <?php echo $model->getLastSentTimeText('greetingText');?>
            </p>
            <button type="button" class="btn btn-primary btn-send-greeting-text"><?php echo Sii::t('sii','Send Greeting Text');?></button>
        </div>

    </div>

    <div class="row persistentMenu">
        
        <?php echo CHtml::label(Sii::t('sii','Persistent Menu'),''); ?>
        
        <p>
            <?php echo Sii::t('sii','The Persistent Menu is a menu that is always available to the user. Having a persistent menu easily communicates the basic capabilities of your bot for first-time and returning users.');?>
            <?php echo Sii::t('sii','This menu follows your shop\'s navigation menu.');?> 
        </p>
        
        <div class="send-button-wrapper">
            <p class="last-sent-time">
                <?php echo $model->getLastSentTimeText('persistentMenu');?>
            </p>
            <button type="button" class="btn btn-primary btn-send-persistent-menu"><?php echo Sii::t('sii','Send Persistent Menu');?></button>
        </div>
    
    </div>
    
    <div class="row getStartedButton">
        
        <?php echo CHtml::label(Sii::t('sii','Get Started Button'),''); ?>
        
        <p>
            <?php echo Sii::t('sii','The first time customers visit your shop chatbot, the welcome Screen can display a Get Started button presenting them a guided tour of your shop when they tap on it. Click send to enable.');?>
        </p>
        
        <div class="send-button-wrapper">
            <p class="last-sent-time">
                <?php echo $model->getLastSentTimeText('getStartedButton');?>
            </p>
            <button type="button" class="btn btn-primary btn-send-get-started-button"><?php echo Sii::t('sii','Send Get Started Button');?></button>
        </div>

    </div>
    
</div>