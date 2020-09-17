<div class="complete-message">

    <h3><?php echo Sii::t('sii','Congrats! Your new shop is now ready and created under the plan {package}.',['{package}'=>Sii::t('sii',$model->name)]);?></h3>

    <?php if ($model->plan->isRecurringCharge): ?>
        <p>
            <?php echo Sii::t('sii','The subscription is valid from {start_date} to {end_date}.',['{start_date}'=>$model->start_date,'{end_date}'=>$model->end_date]);?>
        </p>
        <p>
            <?php echo Sii::t('sii','Thereafter, you will be automatically billed for {currency} {price} on <strong>{recurring}</strong> renewal basis.',[
                            '{currency}'=>$model->plan->currency,
                            '{price}'=>$model->plan->price,
                            '{recurring}'=>strtolower($model->plan->recurringDesc),
                        ]);
            ?>
        </p>
    <?php endif;?>
</div>
<br><br>
<?php 
$this->widget('zii.widgets.jui.CJuiButton',array(
        'name'=>'action-button',
        'buttonType'=>'button',
        'caption'=>Sii::t('sii','Proceed to setup new shop'),
        'value'=>'shopBtn',
        'onclick'=>'js:function(){window.location.href="'.$model->shop->viewUrl.'";}',
    )); 
