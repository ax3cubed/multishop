<?php 
if (!$this->hasParentShop()){
    cs()->registerScript('chosen','$(\'.chzn-select-shop\').chosen();',CClientScript::POS_END);
    if (!$model->isNewRecord)
        cs()->registerScript('chosen-shop','$(\'.chzn-select-shop\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
  }
?>
<div class="image-form">
    <?php $this->renderImageForm($model->modelInstance);?>
</div>

<div class="data-form">

    <div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'brand-form',
            'enableAjaxValidation'=>false,
    )); ?>

            <?php if ($model->isNewRecord):?>
            <p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>
            <?php endif;?>
            
            <?php //echo $form->errorSummary($model); ?>

            <?php if (!$this->hasParentShop()): ?>
            <div class="row" style="margin-bottom:15px;">
                <?php echo $form->labelEx($model,'shop_id',array('style'=>'margin-bottom:3px;')); ?>
                <?php echo Chtml::activeDropDownList($model,
                                'shop_id',
                                $model->getShopsArray(user()->getLocale()),
                                array('prompt'=>'',
                                       'class'=>'chzn-select-shop',
                                       'data-placeholder'=>Sii::t('sii','Select Shop'),
                                       'style'=>'width:450px;'));
                ?>
                <?php echo $form->error($model,'shop_id'); ?>
             </div>
            <?php endif;?>

            <div class="row">
                <?php $model->renderForm($this);?>
            </div>

            <div class="row brand-slug" style="margin-top: 10px;">
                <?php echo $form->labelEx($model,'slug'); ?>
                <?php $this->stooltipWidget($model->getToolTip('slug')); ?>
                <p class="note"><?php echo $model->baseUrl.'/'; ?>
                    <?php echo $form->textField($model,'slug',array('size'=>50,'maxlength'=>100,'disabled'=>!$model->isNewRecord,'class'=>!$model->isNewRecord?'disabled':'enabled')); ?>
                </p>
            </div>

            <div class="row" style="margin-top:20px;">
                <?php $this->widget('zii.widgets.jui.CJuiButton',
                        array(
                            'name'=>'actionButton',
                            'buttonType'=>'button',
                            'caption'=>$model->isNewRecord ? Sii::t('sii','Create') : Sii::t('sii','Save'),
                            'value'=>'actionbtn',
                            'onclick'=>'js:function(){submitform(\'brand-form\');}',
                            )
                    );
                ?>
            </div>

    <?php $this->endWidget(); ?>

    </div>

</div>