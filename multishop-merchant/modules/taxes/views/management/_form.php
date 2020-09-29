<?php cs()->registerScript('chosen','$(\'.chzn-select\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<?php if (!$this->hasParentShop()){
    cs()->registerScript('chosen2','$(\'.chzn-select-shop\').chosen();',CClientScript::POS_END);
    if (!$model->isNewRecord)
        cs()->registerScript('chosen-shop','$(\'.chzn-select-shop\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
}
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'tax-form',
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
        <?php endif;?>
        
	<div class="row">
            <?php $model->renderForm($this);?>
	</div>
        
        <div class="row">
            <?php echo $form->labelEx($model,'rate'); ?>
            <?php echo $form->textField($model,'rate',array('size'=>3,'maxlength'=>10)); ?>
            <span style="margin-left: 10px;"><?php echo Sii::t('sii','For example, input 0.07 to indicate 7%');?></span>
            <?php echo $form->error($model,'rate'); ?>
	</div>

        <div class="row" style="padding-top:20px;clear:both">
            <?php   $this->widget('zii.widgets.jui.CJuiButton',array(
                        'name'=>'actionButton',
                        'buttonType'=>'button',
                        'caption'=>$model->isNewRecord ?  Sii::t('sii','Create') :  Sii::t('sii','Save'),
                        'value'=>'actionbtn',
                        'onclick'=>'js:function(){submitform(\'tax-form\');}',
                    ));
            ?>
	</div>


<?php $this->endWidget(); ?>

</div><!-- form -->