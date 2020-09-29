<?php cs()->registerScript('chosen','$(\'.chzn-select\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<?php 
if (!$this->hasParentShop()){
    cs()->registerScript('chosen3','$(\'.chzn-select-shop\').chosen();',CClientScript::POS_END);
    if (!$model->isNewRecord)
        cs()->registerScript('chosen-shop','$(\'.chzn-select-shop\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
}
?>
<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'shipping-form',
            'enableAjaxValidation'=>false,
    )); ?>

	<p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>

	<?php //echo $form->errorSummary($model); ?>
        
        <?php echo $form->hiddenField($model,'id'); ?>
        
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
        <?php else:?>
            <?php echo $form->hiddenField($model,'shop_id'); ?>
        <?php endif;?>
        
        <div class="row">
            <?php $model->renderForm($this);?>
        </div>
                
        <div class="row">
            <?php echo $form->labelEx($model,'zone_id',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo $form->dropDownList(
                                $model,'zone_id', 
                                $model->getZonesArray(user()->getLocale()), 
                                array('prompt'=>'',
                                      'class'=>'chzn-select',
                                      'data-placeholder'=>Sii::t('sii','Select Zone'),
                                      'style'=>'width:250px;'));
            ?>
            <?php echo $form->error($model,'zone_id'); ?>
	</div>

	<div class="row" style="margin-top:10px">
            <div class="column">
                <?php echo $form->labelEx($model,'method',array('style'=>'margin-bottom:3px;')); ?>
                <?php echo $form->dropDownList($model,'method', 
                                                Shipping::model()->getMethods(), 
                                                array('prompt'=>'',
                                                      'class'=>'chzn-select',
                                                      'data-placeholder'=>Sii::t('sii','Select Method'),
                                                      'style'=>'width:250px;'));?>
		<?php echo $form->error($model,'method'); ?>
            </div>
            <div class="column">
                    <?php echo $form->labelEx($model,'speed'); ?>
                    <?php echo $form->textField($model,'speed',array('size'=>3)); ?> 
                    <?php echo Sii::t('sii','day(s)');?>
                    <?php echo $form->error($model,'speed'); ?>
            </div>
	</div>

	<div class="row" style="clear:both;padding-top:5px">
            <div class="column">
		<?php echo $form->labelEx($model,'type',array('style'=>'margin-bottom:3px;')); ?>
                <?php echo $form->dropDownList($model,'type', 
                                                Shipping::model()->getTypes(), 
                                                array('prompt'=>'',
                                                      'class'=>'chzn-select',
                                                      'data-placeholder'=>Sii::t('sii','Select Type'),
                                                      'style'=>'width:250px;'));?>
		<?php echo $form->error($model,'type'); ?>
            </div>
            <div id="column_rate" class="column" style="<?php echo $model->type==Shipping::TYPE_FLAT?'':'display:none';?>">
		<?php echo $form->labelEx($model,'rate'); ?>
		<?php if ($this->hasParentShop()) echo $this->getParentShop()->getCurrency(); ?>
		<?php echo $form->textField($model,'rate',array('size'=>3,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'rate'); ?>
            </div>
            <div id="column_tierBase" class="column" style="<?php echo $model->type==Shipping::TYPE_TIERS?'':'display:none';?>">
		<?php echo $form->labelEx($model->tierBase,'base',array('style'=>'margin-bottom:3px;')); ?>
                <?php echo CHtml::activeDropDownList($model->tierBase,'base', 
                                                ShippingTier::getBases(), 
                                                array('prompt'=>'',
                                                      'class'=>'chzn-select-base',
                                                      'data-placeholder'=>Sii::t('sii','Select Base'),
                                                      'style'=>'width:250px;'));?>
                <?php echo CHtml::link('<i class="fa fa-plus-square-o"></i>','javascript:void(0);',
                                    array('width'=>15,
                                          'class'=>'add-button',
                                          'style'=>'vertical-align:middle;margin-left:10px;',
                                          'title'=>Sii::t('sii','Add Tier')));
                ?>
            </div>
	</div>
        
        <?php $this->loadChildFormWidget($model); ?>        
                
        <div class="row" style="padding-top:20px;clear:both">
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name'=>'actionButton',
                        'buttonType'=>'button',
                        'caption'=>$model->isNewRecord ?  Sii::t('sii','Create') :  Sii::t('sii','Save'),
                        'value'=>'actionbtn',
                        'onclick'=>'js:function(){submitform(\'shipping-form\');}',
                    )
                );
             ?>
	</div>


<?php $this->endWidget(); ?>

</div><!-- form -->