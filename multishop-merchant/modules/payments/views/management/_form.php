<?php cs()->registerScript('chosen','$(\'.chzn-select-method\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<?php 
if (!$this->hasParentShop()){
    cs()->registerScript('chosen3','$(\'.chzn-select-shop\').chosen();',CClientScript::POS_END);
    if (isset($model->method))
        cs()->registerScript('chosen-shop','$(\'.chzn-select-shop\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
}
?>
<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'payment-method-form',
            'enableAjaxValidation'=>false,
    )); ?>

	<p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>

	<?php //echo $form->errorSummary($model); ?>

        <?php if (!$this->hasParentShop()):?>
        <div class="row" style="margin:10px 0px">
            <?php echo $form->labelEx($model,'shop_id',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo $form->dropDownList($model,'shop_id', 
                                            $model->getShopsArray(user()->getLocale()),
                                            array('prompt'=>'',
                                                  'class'=>'chzn-select-shop',
                                                  'data-placeholder'=>Sii::t('sii','Select Shop'),
                                                  'style'=>'width:250px;'));?>
            <?php echo $form->error($model,'shop_id'); ?>
	</div>
        <?php elseif (isset($model->shop_id)):?>
            <?php echo $form->hiddenField($model,'shop_id'); ?>
        <?php endif;?>
        
        <div class="row" style="margin-top:10px;">
            <div class="column">
                <?php echo $form->labelEx($model,'method',array('style'=>'margin-bottom:3px;')); ?>
                <?php echo $form->dropDownList($model,'method', 
                                                $model->getAvailableMethods(), 
                                                array('prompt'=>'',
                                                      'class'=>'chzn-select-method',
                                                      'data-placeholder'=>Sii::t('sii','Select Method'),
                                                      'style'=>'width:250px;'));
                ?>
		<?php echo $form->error($model,'method'); ?>
            </div>
	</div>
        
        <div class="grid-view" style="margin-top:10px;">
            <?php   if ($model->hasErrors())
                        echo $this->getMethodFormView($model->method,$model);
            ?>
        </div>
        
    <?php $this->endWidget(); ?>

</div><!-- form -->
<script>
$(document).ready(function(){
    $('#PaymentMethodForm_method').change(function() {
      if ($('#PaymentMethodForm_method').val().length > 0){
            getmethodform($('#PaymentMethodForm_method').val());
      }
    });    
});
</script>
