<div class="row" style="padding-top:20px;clear:both">
    <?php 
        $this->widget('zii.widgets.jui.CJuiButton',
            array(
                'name'=>'paymentButton',
                'buttonType'=>'button',
                'caption'=>$model->isNewRecord ? Sii::t('sii','Create') : Sii::t('sii','Save'),
                'value'=>'actionbtn',
                'onclick'=>'js:function(){submitform(\'payment-method-form\');}',
            )
        );
     ?>
</div>