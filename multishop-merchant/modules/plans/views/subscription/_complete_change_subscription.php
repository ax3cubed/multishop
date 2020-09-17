<div class="complete-message">

    <h3><?php echo Sii::t('sii','Your shop "{shop}" is now changed to plan {package}.',['{shop}'=>$model->shop->parseName(user()->getLocale()),'{package}'=>Sii::t('sii',$model->name)]);?></h3>

    <p>
        <?php echo Sii::t('sii','Previous plan is cancelled and will be prorated charged till today when it is stopped.');?>
    </p>
        
        
    <?php if ($model->plan->isRecurringCharge): ?>
        <p>
            <?php echo Sii::t('sii','New subscription is valid from {start_date} to {end_date}.',['{start_date}'=>$model->start_date,'{end_date}'=>$model->end_date]);?>
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
        'caption'=>Sii::t('sii','View shop'),
        'value'=>'shopBtn',
        'onclick'=>'js:function(){window.location.href="'.$model->shop->viewUrl.'";}',
    )); 
