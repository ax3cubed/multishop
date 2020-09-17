<div class="row payment-cards-wrapper">
    <?php   echo CHtml::activeRadioButtonList($model,'payment_token',$this->module->runControllerMethod('billings/payment/index','getPaymentCardTemplates'),[
                'template'=>'<div class="payment-card">{label}{input}</div>',
                'separator'=>'',
            ]);
    ?>
</div>    