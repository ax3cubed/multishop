<div class="<?php echo isset($model->title)?'signup-form-wrapper':'signup-merchant-form-wrapper';?>">

    <div class="form">
        
        <?php if (isset($model->title)):?>
            <div class="form-heading"><?php echo $model->title;?></div>
        <?php endif;?>

        <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'signup-merchant-form',
                'action'=>url('signup'),
                'enableAjaxValidation'=>false,
        )); ?>

        <div class="row">
            <?php echo $form->textField($model,'email',array('class'=>'form-input','maxlength'=>100,'placeholder'=>$model->getAttributeLabel('email'))); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>

        <div class="row">
            <?php echo $form->passwordField($model,'password',array('class'=>'form-input','maxlength'=>32,'placeholder'=>$model->getAttributeLabel('password'))); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>

        <div class="row">
            <input class="form-input ui-button" id="signup-button" name="signup-button" type="submit" value="<?php echo Sii::t('sii','Create Account');?>">
        </div>

        <div class="row tos">
            <?php echo $model->getAttributeLabel('acceptTOS'); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>