<?php 
    if (!$this->hasParentShop()){
        cs()->registerScript('chosen3','$(\'.chzn-select-shop\').chosen();',CClientScript::POS_END);
        cs()->registerScript('chosen-shop','$(\'.chzn-select-shop\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
    }
?>
<div class="form">

    <?php $this->beginWidget('CActiveForm', array(
            'id'=>'payment-method-form',
            'enableAjaxValidation'=>false,
    )); ?>

	<p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>

	<?php //echo $form->errorSummary($model); ?>

        <?php if (!$this->hasParentShop()):?>
        <div class="row" style="margin:10px 0px">
		<?php echo CHtml::activeLabelEx($model,'shop_id',array('style'=>'margin-bottom:3px;')); ?>
                <?php echo CHtml::activeDropDownList($model,'shop_id', 
                                                $model->getShopsArray(user()->getLocale()),
                                                array('prompt'=>'',
                                                      'class'=>'chzn-select-shop',
                                                      'data-placeholder'=>Sii::t('sii','Select Shop'),
                                                      'style'=>'width:250px;'));?>
		<?php echo CHtml::error($model,'shop_id'); ?>
	</div>
        <?php elseif (isset($model->shop_id)):?>
            <?php echo CHtml::activeHiddenField($model,'shop_id'); ?>
        <?php endif;?>

        <div class="row" style="margin-top:10px;">
            <?php echo CHtml::activeHiddenField($model, 'method'); ?>
            <?php echo CHtml::activeLabelEx($model,'method',array('style'=>'margin-bottom:3px;')); ?>
            <?php if ($model->isOfflineMethod())
                    echo PaymentMethod::getName(trim($model->getMethodParam('sourceMethod'),'"'));
                  else
                    echo PaymentMethod::getName($model->method);
            ?>
	</div>
        
        <div class="grid-view" style="margin-top:0;padding-top:0">
            <?php echo $this->getMethodFormView($model->method,$model);?>
        </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
