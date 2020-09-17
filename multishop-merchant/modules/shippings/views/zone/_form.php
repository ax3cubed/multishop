<?php cs()->registerScript('chosen','$(\'.chzn-select\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<?php cs()->registerScript('chosen-country','$(\'.chzn-select-country\').chosen();',CClientScript::POS_END);?>
<?php 
if (!$this->hasParentShop()){
    cs()->registerScript('chosen3','$(\'.chzn-select-shop\').chosen();',CClientScript::POS_END);
    if (!$model->isNewRecord)
        cs()->registerScript('chosen-shop','$(\'.chzn-select-shop\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
}
?>
<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'zone-form',
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
		<?php echo $form->labelEx($model,'country',array('style'=>'margin-bottom:3px;')); ?>
                <?php echo $form->dropDownList($model,'country', 
                                                SLocale::getCountries(),
                                                array('prompt'=>'',
                                                      'class'=>'chzn-select-country',
                                                      'data-placeholder'=>Sii::t('sii','Select Country'),
                                                      'style'=>'width:380px;'));?>
		<?php echo $form->error($model,'country'); ?>
	</div>

	<div class="row" style="margin-top: 10px">
            <?php //echo $form->labelEx($model,'state'); ?>
            <?php //echo $form->textField($model,'state',array('size'=>60,'maxlength'=>100)); ?>
            <?php //echo $form->error($model,'state'); ?>
	</div>

	<div class="row">
            <?php //echo $form->labelEx($model,'city'); ?>
            <?php //echo $form->textField($model,'city',array('size'=>60,'maxlength'=>100)); ?>
            <?php //echo $form->error($model,'city'); ?>
	</div>

	<div class="row">
            <?php //echo $form->labelEx($model,'postcode'); ?>
            <?php //echo $form->textField($model,'postcode',array('size'=>20,'maxlength'=>20)); ?>
            <?php //echo $form->error($model,'postcode'); ?>
	</div>

        <div class="row" style="margin-top:20px;">
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name'=>'actionButton',
                        'buttonType'=>'button',
                        'caption'=>$model->isNewRecord ? Sii::t('sii','Create') :  Sii::t('sii','Save'),
                        'value'=>'actionbtn',
                        'onclick'=>'js:function(){submitform(\'zone-form\');}',
                        )
                );
             ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->