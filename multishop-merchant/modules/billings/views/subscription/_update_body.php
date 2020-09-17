<?php                 
    $this->widget('common.widgets.SDetailView', [
        'data'=>$model,
        'columns'=>[
            $this->getPlanOverviewContent($model,['billing']),
        ],
    ]);
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', [
        'id'=>'payment_method_form',
        'action'=>url('billings/subscription/update/shop/'.$model->shop_id),
]); ?>

    <p>
        <?php echo Sii::t('sii','You may change the payment card used to pay for this subscription.');?>
    </p>
    
    <?php $this->renderPartial('billings.views.payment._card',['model'=>$model]);?>

    <div class="row" style="margin-top:20px;">
        <?php   $this->widget('zii.widgets.jui.CJuiButton',[
                    'id'=>'changCardButton',
                    'name'=>'actionButton',
                    'caption'=> Sii::t('sii','Change Payment Card'),
                    'value'=>'actionbtn',
                ]);
         ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
