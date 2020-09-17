<?php 
cs()->registerScript('chosen','$(\'.chzn-select\').chosen();',CClientScript::POS_END);

if (!$this->hasParentShop()){
    cs()->registerScript('chosen-shop1','$(\'.chzn-select-shop\').chosen();',CClientScript::POS_END);
    if (!$model->isNewRecord)
        cs()->registerScript('chosen-shop2','$(\'.chzn-select-shop\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
}
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'inventory-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>

	<?php //echo $form->errorSummary($model); ?>
        
        <?php if (!$this->hasParentShop()): ?>
        <div class="row" style="margin-bottom:15px;">
            <div class="column">
                <?php echo $form->labelEx($model,'shop_id',array('style'=>'margin-bottom:3px;')); ?>
                <?php echo Chtml::activeDropDownList($model,
                                'shop_id',
                                $model->getShopsArray(user()->getLocale()),
                                array('prompt'=>'',
                                       'class'=>'chzn-select-shop',
                                       'data-placeholder'=>Sii::t('sii','Select Shop'),
                                       'style'=>'width:250px;margin-top:3px;'));
                ?>
                <?php echo $form->error($model,'shop_id'); ?>
            </div>
         </div>
        <?php endif;?>
        
        <?php if (!$this->hasParentProduct()): ?>
        <div class="row">
            <div class="column">
                    <?php echo $form->labelEx($model,'obj_id',array('style'=>'margin-bottom:3px;')); ?>
                    <?php echo $form->dropDownList($model,'obj_id', Inventory::getItemCandidates(user()->getLocale(),$this->hasParentShop()?$this->getParentShop()->id:null), 
                                                    array('class'=>'chzn-select',
                                                      'prompt'=>'',
                                                      'data-placeholder'=>Sii::t('sii','Select Product'),
                                                      'style'=>'width:300px;margin-top:3px;'
                                                   )); 
                    ?>
                    <?php echo $form->error($model,'obj_id',array('style'=>'margin-top:6px;')); ?>
            </div>
        </div>
        <?php endif;?>
        
        <div class="grid-view">
            <?php if ($model->hasErrors()) {
                        echo $this->renderPartial('_form_sku',array('model'=>$model));
                        $this->enableSelectAttributeJs();
                  }
                  else {
                    if ($this->hasParentProduct())
                        echo $this->renderPartial('_form_sku',array('model'=>$this->getInitialSKU()));
                        $this->enableSelectAttributeJs();
                  }
            ?>
        </div>
        
        <p class="clearfix"></p>
        
        <div class="row buttons" style="margin-top:20px;clear:both">
                <?php $this->widget('zii.widgets.jui.CJuiButton',
                        array(
                            'name'=>'InventoryButton',
                            'buttonType'=>'button',
                            'caption'=>$model->isNewRecord ? Sii::t('sii','Create') : Sii::t('sii','Save'),
                            'value'=>'inventoryBtn',
                            'onclick'=>'js:function(){submitform(\'inventory-form\');}',
                            )
                    );
                 ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php if (!$this->hasParentProduct()): ?>
<script>
$(document).ready(function(){
    $('#Inventory_obj_id').change(function() {
      if ($('#Inventory_obj_id').val().length > 0){
            clearerror();
            getskuform($('#Inventory_obj_id').val());
      }
    });    
});
</script>
<?php endif;?>
