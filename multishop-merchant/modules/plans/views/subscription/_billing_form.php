<div class="form">

<?php $form=$this->beginWidget('CActiveForm', [
        'id'=>'subscription_form',
        'action'=>url('plans/subscription/complete'),
]); ?>
    
    <?php if (isset($model->shop_id)):?>
    <div class="row" style="margin-top:10px;">
        <h2><?php echo Sii::t('sii','Current Subscription');?></h2>
        <ul>
            <li><?php echo Sii::t('sii','Shop <strong>{shop}</strong>',['{shop}'=>$model->getShopName(user()->getLocale())]);?></li>
            <li><?php echo Sii::t('sii','Plan <strong>{plan}</strong>',['{plan}'=>$model->getCurrentPlanName()]);?></li>
            <li><?php echo Sii::t('sii','This plan will be cancelled when you subscribe to new plan below');?></li>
        </ul>
        <?php echo $form->hiddenField($model,'shop_id'); ?>
    </div>
    <?php endif;?>
    
    <div class="row" style="margin-top:10px;">
        <h2><?php echo Sii::t('sii','New Subscription');?></h2>
        <ul>
            <li><?php echo Sii::t('sii','You are buying <strong>{package}</strong>',['{package}'=>Sii::t('sii',$model->planData['package'])]);?></li>
            <?php if ($model->packageType==Plan::RECURRING):?>
                <li><?php echo Sii::t('sii','You are paying <strong>{recurring}</strong>',['{recurring}'=>Plan::getRecurringsDesc($model->planData['recurring'])]);?></li>
            <?php endif;?>
            <li><?php echo Sii::t('sii','You will pay <strong>{currency} {price}</strong> this month',['{currency}'=>$model->planData['currency'],'{price}'=>$model->planData['price']]);?></li>
        </ul>
        <?php echo $form->hiddenField($model,'package'); ?>
        <?php echo $form->hiddenField($model,'plan'); ?>
    </div>

    <?php if ($this->showCreditCardForm($model)):?>
    <div class="row" style="margin-top:30px;">
        <h2>
            <span style="margin-right:20px;"><?php echo Sii::t('sii','Add Payment Card');?></span>
            <i class="fa fa-lock" style="font-size: 0.75em;">
                <span style="margin-left: 5px;">
                    <?php echo Sii::t('sii','Secure Form');?>
                </span>
            </i>
        </h2>
        <div class="card-form">
            <?php $this->widget('common.extensions.braintree.widgets.SubscriptionBraintreeForm',$model->braintreeData); ?>
        </div>
    </div>
    <?php endif;?>

    <?php if ($model->requiresPayment && !$this->showCreditCardForm($model)):?>
    <div class="row" style="margin-top:30px;">
        <h2>
            <?php echo Sii::t('sii','Payment Method');?>
            <div class="change-card">
                <?php echo CHtml::link(Sii::t('sii','Change Card'),url('billing/payment'));?>
            </div>                
        </h2>
        <?php echo $this->getFlashAsString('success',Sii::t('sii','Please check if the card below is good for payment. If not, please change card.'),null);?>
        <br>
        <?php $this->renderPartial('billings.views.payment._card',['model'=>$model]);?>
    </div>
    <?php endif;?>    

    <div class="row" style="margin-top:20px;">
        <?php 
            $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'id'=>'purchaseButton',
                    'name'=>'actionButton',
                    'caption'=> Sii::t('sii','Complete Purchase'),
                    'value'=>'actionbtn',
                    'htmlOptions'=>['disabled'=>$this->showCreditCardForm($model)?true:false],
                )
            );
        ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$script = <<<EOJS
$('#purchaseButton').click(function(){
    $('.page-loader').show();
});
EOJS;
$amount = $model->planData['price'];
$braintreeScript = <<<EOJS
loadbraintreecustom('subscription_form','purchaseButton',$amount,function(){
    $('#subscription_form').submit();
});
EOJS;
if ($this->showCreditCardForm($model))
    $script .= $braintreeScript;
Helper::registerJs($script);