<?php $this->widget('common.widgets.SDetailView', [
        'data'=>$data,
        'columns'=>[
            $this->getCardQuickViewColumn($data),
        ],
    ]);
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'payment_method_form',
        'action'=>url('billing/payment/change'),
)); ?>
    
    <?php echo CHtml::hiddenField('payment_data',$this->encodePaymentCard($data));?>

    <div class="row" style="margin-top:30px;">
        <h3>
            <i class="fa fa-lock">
                <span style="margin-left: 5px;">
                    <?php echo Sii::t('sii','Secure Form');?>
                </span>
            </i>
        </h3>
        <div class="card-form">
            <?php $this->widget('common.extensions.braintree.widgets.SubscriptionBraintreeForm',$this->getBraintreeConfig()); ?>
        </div>
    </div>

    <div class="row" style="margin-top:20px;">
        <?php 
            $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'id'=>'cardButton',
                    'name'=>'actionButton',
                    'caption'=> Sii::t('sii','Change Payment Card'),
                    'value'=>'actionbtn',
                    'htmlOptions'=>array('disabled'=>true),
                )
            );
         ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$script = <<<EOJS
$('#cardButton').click(function(){
    $('.page-loader').show();
});
loadbraintreecustom('payment_method_form','cardButton',0,function(){
    $('#payment_method_form').submit();
});
EOJS;
Helper::registerJs($script);