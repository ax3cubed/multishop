<div class="segment pricing top">
    <?php echo $this->renderMarkdown('pricing',['SITE_NAME','TRIAL_DURATION'=>$this->getFreeTrialDuration()]); ?>
    <?php
//    $this->widget('zii.widgets.jui.CJuiButton',array(
//        'name'=>'signup-button',
//        'buttonType'=>'button',
//        'caption'=>Sii::t('sii','Start Free Trial Now'),
//        'value'=>'btn',
//        'htmlOptions'=>array('class'=>'form-input pricing','onclick'=>'window.location.href="'.Yii::app()->urlManager->createHostUrl('account/signup/merchant').'"'),
//    ));
    ?>
    <?php $this->renderPackages(); ?>
</div>
<div class="segment common-features">
    <?php echo $this->renderMarkdown('common_features'); ?>
</div>
<div id="faq_segment" class="segment faq">
    <h1><?php echo Sii::t('sii','FAQ');?></h1>
    <div class="question-wrapper clearfix">
        <?php $this->renderFAQ(); ?>
    </div>
    <br><br><br><br>    
    <h3><?php echo Sii::t('sii','If you don\'t see your answer please contact us at: <em>{email}</em>',['{email}'=>CHtml::link(Config::getSystemSetting('email_contact'),url('/contact'))]);?></h3>
    <br>
</div>
<div class="segment custom">
    <?php echo $this->renderMarkdown('custom_quote'); ?>
    <?php echo bootstrap()->button([
            'label' => Sii::t('sii','Let\'s talk'),
            'options' => ['class' => 'form-input custom-quote ui-button','onclick'=>'window.location.href="'.url('contact').'"'],
        ]);
    ?>
</div>
<?php
$this->renderPartial('_plan_signup');
