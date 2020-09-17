<?php cs()->registerScript('chosen','$(\'.chzn-select\').chosen();',CClientScript::POS_END);?>
<?php cs()->registerScript('chosen-objtype1','$(\'.chzn-select-objtype\').chosen();',CClientScript::POS_END);?>
<?php if (!$model->isNewRecord)
        cs()->registerScript('chosen-objtype2','$(\'.chzn-select-objtype\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
 ?>
<?php
/* @var $this AttributeController */
/* @var $model Attribute */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'attribute-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>

	<?php echo $form->errorSummary($model); ?>

        <div class="row">
                <?php echo $form->labelEx($model,'obj_type'); ?>
                <?php echo $form->dropDownList($model,'obj_type', Attribute::model()->getObjectTypes(), 
                                                array('class'=>'chzn-select-objtype',
                                                  'prompt'=>'',
                                                  'data-placeholder'=>Sii::t('sii','Select Class'),
                                                  'style'=>'width:200px;margin-top:3px;'
                                                 )); ?>
                <?php echo $form->error($model,'obj_type'); ?>
        </div>
        
        <div class="row" style="margin-top:10px">

            <div class="column">
                    <?php echo $form->labelEx($model,'name'); ?>
                    <?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>50)); ?>
                    <?php echo $form->error($model,'name'); ?>
            </div>
            <div class="column">
                    <?php echo $form->labelEx($model,'code'); ?>
                    <?php echo $form->textField($model,'code',array('size'=>3,'maxlength'=>2,'disabled'=>!$model->isNewRecord,'style'=>$model->isNewRecord?'':'background:whitesmoke;border:1px solid #a7a6ac;')); ?>
                    <?php echo $form->error($model,'code'); ?>
            </div>
            <div class="column">
                    <?php echo $form->labelEx($model,'type',array('style'=>'margin-bottom:3px;')); ?>
                    <?php echo $form->dropDownList($model,'type', Attribute::model()->getTypes(), 
                                                    array('class'=>'chzn-select',
                                                      'prompt'=>'',
                                                      'data-placeholder'=>Sii::t('sii','Select Input Type'),
                                                      'style'=>'width:200px;margin-top:3px;'
                                                     )); ?>
                    <?php echo l('<i class="fa fa-plus-square-o"></i>',
                                 'javascript:void(0);',
                                 array('width'=>15,
                                       'class'=>'add-button',
                                       'style'=>($model->type==Attribute::TYPE_SELECT?'':'display:none;').'vertical-align:middle;margin-left:10px;',
                                       'title'=>'Add Option'));
                    ?>
                    <?php echo $form->error($model,'type',array('style'=>'margin-top:6px;')); ?>
            </div>
        </div>
        
        <div class="grid-view" style="padding-top:20px;clear:both">
            <table id="options_table" <?php echo SActiveSession::exists($this->stateVariable)?'class="items"':''; ?>>
                <thead <?php echo SActiveSession::exists($this->stateVariable)?'':'style="display:none"'; ?> >
                    <tr>
                        <th>
                            <?php echo AttributeOption::model()->getAttributeLabel('name');?>
                        </th>        
                        <th>
                            <?php echo AttributeOption::model()->getAttributeLabel('code');?>
                        </th>     
                        <th></th>        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (SActiveSession::load($this->stateVariable) as $option)
                              $this->renderPartial('_form_option',array('model'=>$option));
                    ?>
                </tbody>                
                <?php cs()->registerScript('del-button','$(".del-button:last").show();',CClientScript::POS_END);?>
            </table>
        </div>

        <div class="row buttons" style="padding-top:20px;clear:both">
                <?php $this->widget('zii.widgets.jui.CJuiButton',
                        array(
                            'name'=>'AttributeButton',
                            'buttonType'=>'button',
                            'caption'=>$model->isNewRecord ? Sii::t('sii','Create') : Sii::t('sii','Save'),
                            'value'=>'attrBtn',
                            'onclick'=>'js:function(){submitform(\'attribute-form\');}',
                            )
                    );
                 ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
$(document).ready(function(){
    $('#Attribute_type').change(function() {
          clearerror();
          if ($('#Attribute_type').val()==<?php echo Attribute::TYPE_SELECT;?>)
              $('.add-button').show();
          else {
              clearerror();
              $('.add-button').hide();
              removealloptions('attributes/management');
          }
    });
    $('.add-button').click(function(){
          if ($('#Attribute_type').length > 0){
            clearerror();
            getoption('attributes/management',$('#Attribute_type').val());
          }
    });
});
</script>
