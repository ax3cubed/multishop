<?php cs()->registerScript('renderform','rendercampaignpromocodeform();',CClientScript::POS_END);?>
<?php if (!$this->hasParentShop()){
        cs()->registerScript('chosen3','$(\'.chzn-select-shop\').chosen();',CClientScript::POS_END);
        if (!$model->isNewRecord)
            cs()->registerScript('chosen-shop','$(\'.chzn-select-shop\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
      }
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'campaign-form',
        'enableAjaxValidation'=>false,
)); ?>

    <p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>

    <?php //echo $form->errorSummary($model,null,null,array('style'=>'width:57%')); ?>

    <?php if (!$this->hasParentShop()): ?>
    <div class="row">
        <?php echo $form->labelEx($model,'shop_id'); ?>
        <?php echo Chtml::activeDropDownList($model,
                                    'shop_id',
                                    $model->getShopsArray(user()->getLocale()),
                                    array('prompt'=>'',
                                           'class'=>'chzn-select-shop',
                                           'data-placeholder'=>Sii::t('sii','Select Shop'),
                                           'style'=>'width:250px;')); ?>
        <?php echo $form->error($model,'shop_id'); ?>
    </div>
    <?php endif;?>

    <div class="row">
            <?php echo $form->labelEx($model,'code'); ?>
            <?php echo $form->textField($model,'code',array('size'=>60,'maxlength'=>12)); ?>
            <?php echo $form->error($model,'code'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'offer_value'); ?>
        <div>
            <?php echo $form->textField($model,'offer_value',array('size'=>1,'maxlength'=>10,'style'=>'width:30px;display:inline-block')); ?>
            <?php echo $form->dropDownList($model,'offer_type',CampaignPromocode::model()->getOfferTypes(), 
                                            array('class'=>'chzn-select-offer',
                                                  'prompt'=>'',
                                                  'data-placeholder'=>Sii::t('sii','Offer'),
                                                  'style'=>'width:100px;',
                                            )); ?>
            <?php echo $form->error($model,'offer_value'); ?>
        </div>
    </div>

    <div class="row">
        <div class="column">
            <?php echo $form->labelEx($model,'start_date'); ?>
            <?php 
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name'=>get_class($model).'[start_date]',
                    'value'=>$model->start_date,
                    // additional javascript options for the date picker plugin
                    'options'=>array(
                        'showAnim'=>'fold',
                        'showOn'=>'both',
                        'changeMonth'=>true,
                        'changeYear'=>true,
                        'numberOfMonths'=> 3,
                        //'yearRange'=>'2013:2023',
                        'dateFormat'=> 'yy-mm-dd',
                        'gotoCurrent'=>true,
                        'buttonImage'=> $this->getImage('datepicker',false),
                        'buttonImageOnly'=> true,
                        'onClose'=>'js:function(selectedDate){$(\'#'.get_class($model).'_end_date\').datepicker(\'option\',\'minDate\',selectedDate);}',
                    ),
                    'htmlOptions'=>array(
                        'class'=>'date-field',
                        'style'=>'width:100px;',
                    ),
                ));
            ?>
            <?php echo $form->error($model,'start_date'); ?>
        </div>
        <div class="column">
            <?php echo $form->labelEx($model,'end_date'); ?>
            <?php 
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name'=>get_class($model).'[end_date]',
                    'value'=>$model->end_date,
                    // additional javascript options for the date picker plugin
                    'options'=>array(
                        'showAnim'=>'fold',
                        'showOn'=>'both',
                        'changeMonth'=>true,
                        'changeYear'=>true,
                        'numberOfMonths'=>3,
                        //'yearRange'=>'2013:2023',
                        'dateFormat'=> 'yy-mm-dd',
                        'gotoCurrent'=>false,
                        'buttonImage'=> $this->getImage('datepicker',false),
                        'buttonImageOnly'=> true,
                        'onClose'=>'js:function(selectedDate){$(\'#'.get_class($model).'_start_date\').datepicker(\'option\',\'maxDate\',selectedDate);}',
                    ),
                    'htmlOptions'=>array(
                       'class'=>'date-field',
                       'style'=>'width:100px;',
                    ),
                ));
            ?>
            <?php echo $form->error($model,'end_date'); ?>
        </div>
    </div>

    <div class="row">
        <?php 
            $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'name'=>'campaginButton',
                    'buttonType'=>'button',
                    'caption'=>$model->isNewRecord ? Sii::t('sii','Create') : Sii::t('sii','Save'),
                    'value'=>'campaignbtn',
                    'onclick'=>'js:function(){submitform(\'campaign-form\');}',
                    )
            );
         ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
