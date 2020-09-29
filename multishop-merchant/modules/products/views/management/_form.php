<?php cs()->registerScript('chosen1','$(\'.chzn-select\').chosen();',CClientScript::POS_END);?>
<?php 
if (!$this->hasParentShop()){
    cs()->registerScript('chosen-shop1','$(\'.chzn-select-shop\').chosen();',CClientScript::POS_END);
    if (!$model->isNewRecord)
        cs()->registerScript('chosen-shop2','$(\'.chzn-select-shop\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
}
?>

<div class="form">
    <div class="row">
        <p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>
    </div>  
</div>

<div class="image-form">
    <?php $this->renderMultiImagesForm($model, Config::getBusinessSetting('limit_product_image'));?>    
</div>

<div class="data-form">
        
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'product-form',
            'enableAjaxValidation'=>false,
    )); ?>

    <div class="form">
        
        <div class="row">
            <?php echo $form->hiddenField($model,'image'); ?>
            <?php echo $form->hiddenField($model,'id'); ?>
            <?php //echo $form->errorSummary($model,null,null,array('style'=>'width:320px;')); ?>
        </div>  
        
        <?php if (!$this->hasParentShop()): ?>
        <div class="row">
            <?php echo $form->labelEx($model,'shop_id',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo Chtml::activeDropDownList($model,
                            'shop_id',
                            $model->getShopsArray(user()->getLocale()),
                            array('prompt'=>'',
                            'class'=>'chzn-select-shop',
                            'data-placeholder'=>Sii::t('sii','Select Shop'),
                                   'style'=>'width:250px;'));
            ?>
            <?php echo $form->error($model,'shop_id'); ?>
        </div>  
        <?php endif;?>
        
        <div class="row">
            <?php $model->renderForm($this,!Helper::READONLY,array('name'));?>
        </div>
        
        <div class="row">
            <?php echo $form->labelEx($model,'code'); ?>
            <?php echo $form->textField($model,'code',array('size'=>80,'maxlength'=>6,'disabled'=>!$model->isNewRecord,'class'=>$model->isNewRecord?'':'disabled')); ?>
            <?php echo $form->error($model,'code'); ?>
        </div>  
        
        <div class="row" style="padding-top: 10px;">
            <div class="column">
                <?php echo $form->labelEx($model,'unit_price'); ?>
                <?php if ($this->hasParentShop()) echo $this->getParentShop()->getCurrency(); ?>
                <?php echo $form->textField($model,'unit_price',array('size'=>10,'maxlength'=>10)); ?>
            </div>
            <div class="column">
                <?php echo $form->labelEx($model,'weight'); ?>
                <?php if ($this->hasParentShop()) echo $this->getParentShop()->getWeightUnit();?>
                <?php echo $form->textField($model,'weight',array('size'=>10,'maxlength'=>10)); ?>
            </div>
        </div>   
        
        <div class="row" style="clear:both">
            <?php echo $form->error($model,'unit_price'); echo $form->error($model,'weight'); ?>
        </div>

        <?php if (!$model->isNewRecord):?>
            <div class="row" style="margin-top:25px;">
                <?php echo $form->labelEx($model,'slug'); ?>
                <p class="note"><?php echo $model->baseUrl.'/'; ?>
                    <?php echo $form->textField($model,'slug',array('size'=>50,'maxlength'=>100,'disabled'=>true,'class'=>'disabled')); ?>
                </p>
            </div>
        <?php endif;?>

        <?php $this->spagesectionWidget($this->getSectionsData($model,true)); ?>

        <div class="row" style="margin-top:30px">
            <?php $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name'=>'actionButton',
                        'buttonType'=>'button',
                        'caption'=>$model->isNewRecord ? Sii::t('sii','Create') : Sii::t('sii','Save'),
                        'value'=>'actionBtn',
                        'onclick'=>'js:function(){submitproduct("'.get_class($model).'");}',
                       )
                );
             ?>
        </div>

    </div><!-- form -->

    <?php $this->endWidget(); ?>
                
</div>