<?php $buttonCaption = $this->getCheckoutButtonCaption($model);?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'subscription-form',
        'action'=>url('plans/subscription/billing'),
)); ?>

    <?php if (isset($model->shop_id)):?>
        <div class="row" style="margin-top:10px;">
            <?php echo $form->hiddenField($model,'shop_id'); ?>
            <?php echo $form->labelEx($model,'shop',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo $model->getShopName(user()->getLocale()); ?>
        </div>
        <div class="row" style="margin-top:10px;">
            <?php echo $form->labelEx($model,'currentPackage',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo $model->getCurrentPlanName(); ?>
        </div>
    <?php endif;?>
    
    <div class="row" style="margin-top:10px;">
        <?php echo $form->labelEx($model,'package',array('style'=>'margin-bottom:3px;')); ?>
        <?php echo $form->dropDownList(
                        $model,'package', 
                        $model->getPackageChoices(), 
                        array('prompt'=>'',
                              'class'=>'chzn-select-package',
                              'data-placeholder'=>Sii::t('sii','Select {item}',array('{item}'=>$model->getAttributeLabel('package'))),
                              'style'=>'width:250px;'));
        ?>
        <?php echo $form->error($model,'package'); ?>
    </div>

    <div class="row plan" style="margin-top:10px;">
        <?php $this->renderPartial('_plan',array('model'=>$model));?>
    </div>
    
    <div class="row" style="margin-top:20px;">
        <?php $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'name'=>'actionButton',
                    'caption'=> $buttonCaption,
                    'value'=>'actionbtn',
                )
            );
        ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
$script = <<<EOJS
$('.chzn-select-package').chosen();$('.chzn-search').hide();
$('.chzn-select-plan').chosen();$('.chzn-search').hide();
$('#SubscriptionForm_package').change(function(){
    $('.page-loader').show(); 
    $.getJSON('/plans/subscription/getplans?package='+$(this).val(),function(data) {
        $('.page-loader').hide(); 
        var planSelector = $('#SubscriptionForm_plan');
        planSelector.empty(); // remove old options
        $.each(data.plans, function(value,text) {
            planSelector.append('<option value="'+value+'">'+text+'</option>');
        });        
        $('.chzn-select-plan').chosen().trigger('liszt:updated');
        $('.chzn-search').hide();
        $('#actionButton').val(data.buttonCaption);
    })
    .error(function(XHR) { 
        error(XHR); 
    });    
});
EOJS;
Helper::registerJs($script);