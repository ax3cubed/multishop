<?php echo CHtml::activeLabelEx($model, 'plan', array('style'=>'margin-bottom:3px;')); ?>
<?php echo CHtml::activeDropDownList(
                $model,'plan', 
                $model->plans, 
                array('prompt'=>'',
                      'class'=>'chzn-select-plan',
                      'data-placeholder'=>Sii::t('sii','Select {item}',array('{item}'=>$model->getAttributeLabel('plan'))),
                      'style'=>'width:250px;'));
?>
<?php echo CHtml::error($model,'plan'); 