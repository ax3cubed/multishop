<?php cs()->registerScript('chosen','$(\'.chzn-select\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<div class="form">
    
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'product-attribute-form',
            'action'=>url('product/attribute/'.($model->isNewRecord?'create':'update/id/'.$model->id)),
            'enableAjaxValidation'=>false,
    )); ?>

	<p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>

	<?php //echo $form->errorSummary($model); ?>

        <?php echo $form->hiddenField($model,'product_id'); ?>

        <div class="row">
            <?php //echo $form->labelEx($model,'name'); ?>
            <?php //if ($model->isNewRecord)
//                        $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
//                                    'name'=>'ProductAttribute[name]',
//                                    'source'=>$this->getAllowedAttrNamesAsArray(),
//                                    // additional javascript options for the autocomplete plugin
//                                    'options'=>array(
//                                        'minLength'=>'1',
//                                        'select'=>'js:function(){getattrform(\'name\',$(this).val())}',
//                                    ),
//                                    'htmlOptions'=>array('size'=>30,'maxlength'=>50),
//                                ));
                  //else 
                       $model->renderForm($this,!Helper::READONLY);
            ?>
            <?php echo $form->error($model,'name'); ?>
        </div>
        
        <div class="row">
            <div class="column">
                <?php echo $form->labelEx($model,'code'); ?>
                <?php   if ($model->isNewRecord)
                            echo $form->textField($model,'code',array('size'=>3,'maxlength'=>2));
//                            $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
//                                    'name'=>get_class($model).'[code]',
//                                    'value'=>$model->code,
//                                    'source'=>$this->getAllowedAttrCodesAsArray(),
//                                    // additional javascript options for the autocomplete plugin
//                                    'options'=>array(
//                                        'minLength'=>'1',
//                                        'select'=>'js:function(){getattrform(\'code\',$(this).val())}',
//                                    ),
//                                    'htmlOptions'=>array('size'=>3,'maxlength'=>2,'class'=>$model->hasErrors('code')?'error':''),
//                                ));
                        else 
                            echo $form->textField($model,'code',array('size'=>3,'maxlength'=>2,'disabled'=>true,'style'=>'background:whitesmoke;border:1px solid #a7a6ac;'));
                 ?>
                <?php echo $form->error($model,'code'); ?>
            </div>
            <div class="column">
                <?php echo $form->labelEx($model,'type',array('style'=>'margin-bottom:3px;')); ?>
                <?php echo $form->dropDownList($model,'type', ProductAttribute::model()->getTypes(), 
                                                array('class'=>'chzn-select',
                                                  'prompt'=>'',
                                                  'data-placeholder'=>Sii::t('sii','Select Input Type'),
                                                  'style'=>'width:200px;margin-top:3px;'
                                                 )); ?>
                <?php echo CHtml::link('<i class="fa fa-plus-square-o"></i>','javascript:void(0);',
                                    array('width'=>15,
                                          'class'=>'add-button',
                                          'style'=>($model->type==ProductAttribute::TYPE_SELECT?'':'display:none;').'vertical-align:middle;margin-left:10px;',
                                          'title'=>'Add Option'));
                ?>
                <?php echo $form->error($model,'type',array('style'=>'margin-top:6px;')); ?>
            </div>
        </div>
        
        <?php $this->loadChildFormWidget($this->formType.'_type'); ?>

        <div class="row buttons" style="margin-top:20px;clear:both">
                <?php $this->widget('zii.widgets.jui.CJuiButton',
                        array(
                            'name'=>'ProductAttributeButton',
                            'buttonType'=>'button',
                            'caption'=>$model->isNewRecord ?  Sii::t('sii','Create') :  Sii::t('sii','Save'),
                            'value'=>'productattrBtn',
                            'onclick'=>'js:function(){submitform(\'product-attribute-form\');}',
                            )
                    );
                 ?>
                <?php echo $form->checkBox($model,'share',array('style'=>'vertical-align: middle;margin-right: 10px;margin-left:20px;')); ?>
                <?php echo $model->getAttributeLabel('share'); ?>
	</div>

    <?php $this->endWidget(); ?>

</div><!-- form -->