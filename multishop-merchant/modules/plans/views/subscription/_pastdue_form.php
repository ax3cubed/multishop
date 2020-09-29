<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'subscription_retry_charge_form',
        'action'=>url('plans/subscription/retryCharge/shop/'.$model->shop_id),
)); ?>
    
        <div class="row" style="margin-top:10px;">
            <?php echo $form->hiddenField($formModel,'subscription_no'); ?>
            <ul>
                <li><?php echo Sii::t('sii','Shop <strong>{shop}</strong>.',array('{shop}'=>$model->shop->parseName(user()->getLocale())));?></li>
                <li><?php echo Sii::t('sii','Subscription No is <strong>{no}</strong>.',array('{no}'=>$model->subscription_no));?></li>
                <li><?php echo Sii::t('sii','Subscription Start Date is <strong>{date}</strong>.',array('{date}'=>$model->start_date));?></li>
                <li><?php echo Sii::t('sii','Subscription End Date is <strong>{date}</strong>.',array('{date}'=>$model->end_date));?></li>
                <li><?php echo Sii::t('sii','You are paying amount for this overdue subscription:');?></li>
            </ul>            
        </div>
    
        <div class="row" style="margin-left:25px;">
            <?php //echo $form->labelEx($formModel,'amount'); ?>
            <?php echo $model->plan->currency; ?>
            <?php echo $form->textField($formModel,'amount',array('size'=>8,'maxlength'=>10)); ?>
            <?php echo $form->error($formModel,'amount'); ?>
        </div>

        <div class="row" style="margin-top:30px;">
            <h2>
                <?php echo Sii::t('sii','Payment Method');?>
            </h2>
            <?php 
                $this->widget('common.widgets.SDetailView', [
                    'data'=>['payment'],//dummy
                    'columns'=>[
                        $this->module->runControllerMethod('billings/payment/index','findSubscriptionPaymentMethod',$model,['payment']),
                    ],
                ]);
            ?>
        </div>
    
        <div class="row" style="margin-top:20px;">
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'id'=>'purchaseButton',
                        'name'=>'actionButton',
                        'caption'=> Sii::t('sii','Pay Now'),
                        'value'=>'actionbtn',
                    )
                );
            ?>
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'id'=>'skipButton',
                        'buttonType'=>'button',
                        'name'=>'actionButton',
                        'caption'=> Sii::t('sii','Pay Later'),
                        'value'=>'actionbtn',
                        'htmlOptions'=>['style'=>'background:gray;vertical-align: baseline;'],
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
$('#skipButton').click(function(){
    window.location = '$model->skipPastduePaymentUrl';
});
EOJS;
Helper::registerJs($script);