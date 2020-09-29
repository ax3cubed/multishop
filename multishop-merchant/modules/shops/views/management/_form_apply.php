<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'shop-form',
            'action'=>$model->applyUrl,    
            'enableAjaxValidation'=>false,
    )); ?>

    <p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>

    <?php //echo $form->errorSummary($model); ?>

    <?php $this->spagesectionWidget($this->getModule()->runControllerMethod('shops/management','getApplySectionsData',$model));?>

    <div class="row" style="margin-top:40px">
    <?php $this->widget('zii.widgets.jui.CJuiButton',array(
                    'name'=>'shop-button',
                    'buttonType'=>'button',
                    'caption'=>Sii::t('sii','Submit Application'),
                    'value'=>'shopBtn',
                    'onclick'=>'js:function(){submitform(\'shop-form\');}',
                )); 
    ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->