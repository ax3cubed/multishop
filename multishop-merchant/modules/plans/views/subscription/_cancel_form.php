<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'subscription_form',
        'action'=>url('plans/subscription/cancel/shop/'.$model->shop_id),
)); ?>

    <?php echo $form->hiddenField($model,'package_id'); ?>
    <?php echo $form->hiddenField($model,'subscription_no'); ?>
    
    <div class="row" style="margin-top:20px;">
        <?php $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'name'=>'actionButton',
                    'caption'=> Sii::t('sii','Yes, I want to cancel'),
                    'value'=>'actionbtn',
                )
            );
        ?>
        <?php 
            $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'id'=>'viewButton',
                    'buttonType'=>'button',
                    'name'=>'viewButton',
                    'caption'=> Sii::t('sii','No, I will keep my shop'),
                    'value'=>'actionbtn',
                    'htmlOptions'=>['style'=>'background:gray;vertical-align: baseline;'],
                )
            );
        ?>
    </div>
    
<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$shopUrl = $model->shop->viewUrl;
$script = <<<EOJS
$('#viewButton').click(function(){
    window.location = '$shopUrl';
});
EOJS;
Helper::registerJs($script);