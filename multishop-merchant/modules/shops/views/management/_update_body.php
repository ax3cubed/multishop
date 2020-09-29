<div class="image-form">
    <?php $this->renderImageForm($model->modelInstance,CHtml::label(Sii::t('sii','Shop Logo'),'',['required'=>true]));?>    
</div>

<div class="data-form">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'shop-form',
            'enableAjaxValidation'=>false,
    )); ?>
    
    <div class="form">
        
        <div class="row">
            <p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>
            <?php //echo $form->errorSummary($model,null,null,array('style'=>'width:320px;')); ?>
        </div>  
        
        <div class="row" style="margin-top:15px;margin-bottom:20px;">
            <?php cs()->registerScript('chosen-category','$(\'.chzn-select-category\').chosen();',CClientScript::POS_END);?>
            <?php echo CHtml::activeLabelEx($model, 'category',array('style'=>'margin-bottom:5px;')); ?>
            <?php echo Chtml::activeDropDownList($model,
                                                 'category',
                                                 Shop::getCategories(), 
                                                 array('prompt'=>'',
                                                       'class'=>'chzn-select-category',
                                                       'data-placeholder'=>Sii::t('sii','Select {field}',array('{field}'=>$model->getAttributeLabel('category'))),
                                                    ));
            ?>
            <?php $this->stooltipWidget($model->getToolTip('category')); ?>
            <?php echo Chtml::error($model,'category'); ?>
        </div>
        
        <div class="row" style="margin-top:15px;">
            <?php $model->renderForm($this);?>
        </div>        
        
        <div class="row">
            <?php echo $form->labelEx($model,'slug'); ?>
            <?php 
                if ($model->prototype())
                    echo 'https:// '.$form->textField($model,'slug',['size'=>30,'maxlength'=>50,'disabled'=>false,'class'=>'enabled']).resolveDomain(app()->urlManager->shopDomain); 
                else
                    echo 'https:// '.CHtml::textField('slug', $model->customDomain,['size'=>30,'maxlength'=>50,'disabled'=>true,'class'=>'disabled']).resolveDomain(app()->urlManager->shopDomain); 
            ?>
        </div>  
        
        <?php $this->spagesectionWidget($this->getSectionsData($model,true)); ?>

        <div class="row" style="margin-top:30px">
            <?php $this->widget('zii.widgets.jui.CJuiButton',[
                    'name'=>'actionButton',
                    'buttonType'=>'button',
                    'caption'=>Sii::t('sii','Save'),
                    'value'=>'actionBtn',
                    'htmlOptions'=>['class'=>'ui-button'],
                    'onclick'=>'js:function(){submitform(\'shop-form\');}',
                ]);
            ?>
        </div>  

    </div><!-- form -->
        
    <?php $this->endWidget(); ?>
    
</div>
