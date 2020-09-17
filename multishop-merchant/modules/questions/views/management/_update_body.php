<div class="task-form">
    <div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'answer-form',
            'enableAjaxValidation'=>false,
    )); ?>

            <?php echo $form->hiddenField($model,'id'); ?>

            <?php //echo $form->errorSummary($model); ?>

            <div class="row">
                <?php echo $form->labelEx($model,'answer'); ?>
                <?php echo $form->textArea($model,'answer',array('rows'=>6)); ?>
                <?php echo $form->error($model,'answer'); ?>
            </div>

            <div class="row" style="margin-top:20px">
            <?php $this->widget('zii.widgets.jui.CJuiButton',
                                array(
                                    'name'=>'answer-button',
                                    'buttonType'=>'submit',
                                    'caption'=>Sii::t('sii','Update Answer'),
                                    'value'=>'answerBtn',
                                    )
                                ); 
            ?>
            </div>

    <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>