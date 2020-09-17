<div id="chatbot_plugin_form_wrapper" class="form">
    
    <div class="row message"><!-- To input return message --></div>
    
    <?php echo CHtml::activeHiddenField($model,'clientId'); ?>

    <div class="row">
        <p>
            <?php echo Sii::t('sii','Build awareness and let people know your shop are on Messenger with buttons for website, email and more.'); ?>
        </p>
        <?php echo CHtml::activeLabelEx($model,'appId',['style'=>'']); ?>
        <?php echo CHtml::activeTextField($model,'appId',['style'=>'','maxlength'=>100,'placeholder'=>$model->getToolTip('appId')]); ?>
        <?php echo CHtml::error($model,'appId'); ?>

        <?php echo CHtml::activeLabelEx($model,'pageId',['style'=>'margin-top:10px;']); ?>
        <?php echo CHtml::activeTextField($model,'pageId',['style'=>'','maxlength'=>100,'placeholder'=>$model->getToolTip('pageId')]); ?>
        <?php echo CHtml::error($model,'pageId'); ?>
    </div>
    
    <div class="row messageUsPlugin">
        
        <?php echo CHtml::activeLabelEx($model,'messageUsPlugin',['style'=>'margin-bottom:3px;']); ?>
        
        <p>
            <?php echo Sii::t('sii','Automatically takes guests to Messenger where they can send the first message.');?>
        </p>
        
        <div class="enable-button">
            <?php echo CHtml::activeCheckBox($model,'messageUsPlugin',['style'=>'width:auto']); ?>
            <span><?php echo Sii::t('sii','Enable');?></span>
            <img src="<?php echo $model->getPluginImage('message_us_plugin.png');?>" width="300">
            <?php echo CHtml::error($model,'messageUsPlugin'); ?>
        </div>

    </div>    
    
    <div class="row sendToMessengerPlugin">
        
        <?php echo CHtml::activeLabelEx($model,'sendToMessengerPlugin',['style'=>'margin-bottom:3px;']); ?>
        
        <p>
            <?php echo Sii::t('sii','Gives customers the option to receive information from your shop in Messenger, such as notifications for receipts, shipping alerts, etc');?>
        </p>
        
        <div class="enable-button">
            <?php echo CHtml::activeCheckBox($model,'sendToMessengerPlugin',['style'=>'width:auto']); ?>
            <span><?php echo Sii::t('sii','Enable');?></span>
            <img src="<?php echo $model->getPluginImage('send_to_messenger_plugin.png');?>" width="300">
            <?php echo CHtml::error($model,'sendToMessengerPlugin'); ?>
        </div>

    </div>    
    
</div>