<?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'prev_question_form',
            'enableAjaxValidation'=>false,
        )); 
?>

    <?php echo $form->hiddenField($questionForm,'obj_id'); ?>
    <?php echo CHtml::hiddenField('QuestionForm[page]',($questionForm->page+1)); ?>

    <?php echo CHtml::link(Sii::t('sii','View previous questions'), 'javascript:void(0);',array('onclick'=>'prevdata(\'question\',\''.$route.'\')'));?>

    <div class="question-loader-wrapper">
        <?php 
            $this->widget('common.widgets.sloader.SLoader',array(
                'id'=>'question_loader',
                'type'=>SLoader::RELATIVE,
            ));
        ?>    
    </div>

<?php $this->endWidget(); ?>