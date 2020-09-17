<?php 
$this->widget('common.widgets.SDetailView', [
    'data'=>$model,
    'columns'=>[
        [
            ['label'=>Sii::t('sii','Theme'),'value'=>$model->getThemeName(user()->getLocale())],
            ['label'=>Sii::t('sii','Style'),'value'=>$model->getStyleName(user()->getLocale())],
            ['label'=>Sii::t('sii','Designer'),'value'=>$model->getDesigner()],
            ['label'=>Sii::t('sii','Price'),'value'=>$model->getPrice()],
        ]
    ],
]);
?>
<div class="form">
    
    <?php $form=$this->beginWidget('CActiveForm',[
            'id'=>'shop_desgin_form',
        ]); 
    ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo CHtml::activeHiddenField($model,'shop_id');?>
    <?php echo CHtml::activeHiddenField($model,'theme');?>
    <?php echo CHtml::activeHiddenField($model,'style');?>
    
    <?php if($model->installed): //only installed theme can have configuration ?>
    
        <?php $this->widget('common.widgets.spagesection.SPageSection',['sections'=>$this->getSectionsData($model,user()->getLocale()),'showEmptyText'=>false]);?>

    <?php endif; ?>

    <div class="row" style="padding-top:20px;display: inline-block;">
        <?php 
            $this->widget('zii.widgets.jui.CJuiButton', [
                    'name'=>'actionButton',
                    'buttonType'=>'button',
                    'caption'=>$model->installed ? Sii::t('sii','Save') : Sii::t('sii','Install'),
                    'value'=>'actionbtn',
                    'htmlOptions'=>['class'=>'ui-button'],
                    'onclick'=>'js:function(){submitform(\'shop_desgin_form\');}',
                ]);
         ?>
    </div> 
    
    <?php if($model->installed): //only installed theme can have configuration ?>
    
        <div class="row" style="margin-left: 10px;display: inline-block;">
            <?php if (!$model->current):?>
                <?php echo CHtml::activeLabelEx($model,'publish',array('style'=>'display: inline-block;')); ?>
                <?php echo CHtml::activeDropDownList($model,'current', Helper::getBooleanValues());?>
            <?php else:?>
                <?php echo CHtml::activeHiddenField($model,'current');?>
            <?php endif;?>
        </div>   

    <?php endif; 
    //todo For new theme, need to show terms and conditions and accept terms of services etc.
    ?>
    
    <?php $this->endWidget(); ?>
    
</div><!-- form div -->
