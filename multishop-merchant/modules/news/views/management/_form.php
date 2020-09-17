<?php 
if (!$this->hasParentShop()){
    cs()->registerScript('chosen','$(\'.chzn-select-shop\').chosen();',CClientScript::POS_END);
    if (!$model->isNewRecord)
        cs()->registerScript('chosen-shop','$(\'.chzn-select-shop\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
}
?>

<?php $this->renderImageForm($model->modelInstance);?>     

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'news-form',
            'enableAjaxValidation'=>false,
    )); ?>

	<p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>
        
	<?php //echo $form->errorSummary($model); ?>
        
        <?php if (!$this->hasParentShop()): ?>
	<div class="row">
            <?php echo $form->labelEx($model,'shop_id',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo $form->dropDownList($model,'shop_id', 
                                            $model->getShopsArray(user()->getLocale()),
                                            array('prompt'=>'',
                                                  'class'=>'chzn-select',
                                                  'data-placeholder'=>Sii::t('sii','Select Shop'),
                                                  'style'=>'width:250px;'));?>
            <?php echo $form->error($model,'shop_id'); ?>
	</div>
        <?php endif;?>

        <div class="row">
            <?php $model->renderForm($this);?>
        </div>

        <div class="row" style="margin-top:20px;">
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name'=>'actionButton',
                        'buttonType'=>'button',
                        'caption'=>$model->isNewRecord ? Sii::t('sii','Create') : Sii::t('sii','Save'),
                        'value'=>'actionbtn',
                        'onclick'=>'js:function(){submitform(\'news-form\');}',
                        )
                );
             ?>
	</div>

    <?php $this->endWidget(); ?>

</div><!-- form -->