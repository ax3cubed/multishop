<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'inventory-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>

        <p class="note"><?php echo Sii::t('sii','Enter "+" or "-" to indicate stock increment or decrement. E.g. +50, -50');?></p>

	<?php echo $form->errorSummary($model); ?>

        <div class="grid-view">
            <table id="sku-table" class="items">
                <thead>
                    <tr>
                        <th>
                            <?php echo $model->getAttributeLabel('adjust');?> <span class="required">*</span>
                        </th>
                        <th>
                            <?php echo $model->getAttributeLabel('quantity');?>
                        </th>
                        <th>
                            <?php echo $model->getAttributeLabel('available');?>
                        </th>
                        <th>
                            <?php echo $model->getAttributeLabel('hold');?>
                        </th>
                        <th>
                            <?php echo $model->getAttributeLabel('sold');?>
                        </th>
                        <th>
                            <?php echo $model->getAttributeLabel('bad');?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="odd">
                        <td style="text-align:center" width="20%">
                            <?php echo CHtml::activeTextField($model,'adjust',array('size'=>5,'maxlength'=>8)); ?>
                            <?php echo l(CHtml::image($this->getImage('arrow_up.png'),Sii::t('sii','Increase Stock'),array('width'=>15,'class'=>'increase-button','style'=>'vertical-align:middle')));?>
                            <?php echo l(CHtml::image($this->getImage('arrow_down.png'),Sii::t('sii','Decrease Stock'),array('width'=>15,'class'=>'decrease-button','style'=>'vertical-align:middle')));?>
                            <?php echo CHtml::error($model,'adjust'); ?>
                        </td>
                        <td style="text-align:center">
                            <span id="Inventory_currentTotal" style="display:none"><?php echo $model->quantity;?></span>
                            <span id="Inventory_total"><?php echo $model->quantity;?></span>
                            <?php echo CHtml::error($model,'quantity'); ?>
                        </td>
                        <td style="text-align:center">
                            <span id="Inventory_currentAvailable" style="display:none"><?php echo $model->available;?></span>
                            <span id="Inventory_available"><?php echo $model->available;?></span>
                            <?php echo CHtml::error($model,'available'); ?>
                        </td>
                        <td style="text-align:center">
                            <?php echo $model->hold; ?>
                        </td>
                        <td style="text-align:center">
                            <?php echo $model->sold; ?>
                        </td>
                        <td style="text-align:center">
                            <?php echo $model->bad; ?>
                        </td>
                    </tr>    
                </tbody>
            </table>
            
        </div>

        <p class="clearfix"></p>
        
        <div class="row buttons" style="margin-top:20px;clear:both">
                <?php $this->widget('zii.widgets.jui.CJuiButton',
                        array(
                            'name'=>'InventoryButton',
                            'buttonType'=>'button',
                            'caption'=>Sii::t('sii','Save'),
                            'value'=>'inventoryBtn',
                            'onclick'=>'js:function(){submitform(\'inventory-form\');}',
                            )
                    );
                 ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
$(document).ready(function(){
    $('#InventoryForm_adjust').change(function() {
        clearerror();
        updatestock($('#InventoryForm_adjust').val(),true);
    });
    $('.increase-button').click(function(){
        clearerror();
        updatestock(1,false);
    });
    $('.decrease-button').click(function(){
        clearerror();
        updatestock(-1,false);
    });
});
</script>