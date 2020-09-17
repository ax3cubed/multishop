<?php cs()->registerScript('renderform','rendercampaignbgaform();renderproduct(\''.(!$model->isNewRecord?$model->buy_x:'nx').'\',\''.($model->hasG()?$model->get_y:'ny').'\');',CClientScript::POS_END);?>
<?php cs()->registerScript('chosen2','$(\'.chzn-select-shipping\').chosen().change(function(){getshipping($(this).val());});',CClientScript::POS_END);?>
<?php if (!$this->hasParentShop()){
        cs()->registerScript('chosen3','$(\'.chzn-select-shop\').chosen();',CClientScript::POS_END);
        if (!$model->isNewRecord)
            cs()->registerScript('chosen-shop','$(\'.chzn-select-shop\').attr(\'disabled\', true).trigger("liszt:updated")',CClientScript::POS_END);
      }
?>

<div class="image-form">
    <?php $this->renderImageForm($model->modelInstance);?>    
</div>

<div class="data-form">

    <div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'campaign-form',
            'enableAjaxValidation'=>false,
    )); ?>
        
        <p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>

        <?php echo $form->hiddenField($model,'id'); ?>

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
                                           'style'=>'width:250px;'));
                ?>
                <?php echo $form->error($model,'shop_id'); ?>
        </div>
        <?php endif;?>

        <div class="row">
            <?php $model->renderForm($this,!Helper::READONLY,array('name'));?>
        </div>

        <div class="row">
            <div>
                <?php echo $form->labelEx($model,'buy_x',array('style'=>'display:inline;')); ?>
                <?php echo $form->dropDownList($model,'buy_x_qty',Helper::getQuantityList(),
                                                    array('class'=>'chzn-select-x-qty',
                                                      'prompt'=>'',
                                                      'data-placeholder'=>1,
                                                      'style'=>'width:50px;'
                                                     )); 
                ?>
                <?php echo $form->dropDownList($model,'buy_x', 
                                            $model->getProductsArray(user()->getLocale()),
                                            array('class'=>'chzn-select-x select-product',
                                              'prompt'=>'',
                                              'data-placeholder'=>Sii::t('sii','Select Product'),
                                            )); 
                ?>
                <?php echo $form->error($model,'buy_x'); ?>
            </div>
            <div class="x-product-area rounded" style="min-height:<?php echo Image::VERSION_SMALL;?>px;display:<?php echo $model->isNewRecord?'none':'block';?>">
                <div id="x_area" class="product_area"></div>
                <div id="x_offer" class="offer_area" style="display:none;"></div>
                <?php if (!$model->isNewRecord):?>
                <div class="loader-area">
                    <?php $this->widget('common.widgets.sloader.SLoader',array(
                            'id'=>'x_loader',
                            'type'=>SLoader::RELATIVE,
                            'display'=>'inline-block',
                          ));
                    ?>
                </div>
                <?php endif;?>
            </div>
        </div>

        <div class="row">
            <div>
                <?php echo $form->labelEx($model,'get_y',array('style'=>'display:inline;margin-right:10px;')); ?>
                <?php echo $form->dropDownList($model,'get_y_qty',Helper::getQuantityList(), 
                                                    array('class'=>'chzn-select-y-qty',
                                                      'prompt'=>0,
                                                      'data-placeholder'=>0,
                                                      'style'=>'width:50px;margin-top:3px;'
                                                     )); 
                ?>
                <?php echo $form->dropDownList($model,'get_y', 
                                            $model->getProductsArray(user()->getLocale()),
                                            array('class'=>'chzn-select-y select-product',
                                              'prompt'=>Sii::t('sii','- Blank Product -'),
                                              'data-placeholder'=>Sii::t('sii','- Blank Product -'),
                                            )); 
                ?>
                <?php echo $form->error($model,'get_y'); ?>
            </div>
            <div class="y-product-area rounded" style="min-height:<?php echo Image::VERSION_SMALL;?>px;display:<?php echo $model->isNewRecord||!$model->hasG()?'none':'block';?>">
                <div id="y_area" class="product_area"></div>
                <div id="y_offer" class="offer_area" style="display:none;"></div>
                <?php if (!$model->isNewRecord):?>
                <div class="loader-area">
                    <?php $this->widget('common.widgets.sloader.SLoader',array(
                            'id'=>'y_loader',
                            'type'=>SLoader::RELATIVE,
                            'display'=>'inline-block',
                          ));
                    ?>
                </div>
                <?php endif;?>
            </div>
        </div>

        <div class="row">
            <div>
                <?php echo CHtml::label(Sii::t('sii','At'),'',array('required'=>true,'style'=>'display:inline;margin-right:10px;'));?>
                <?php echo $form->textField($model,'at_offer',array('size'=>1,'maxlength'=>10,'style'=>'width:30px;display:'.($model->onOfferFree()?'none':'inline-block'))); ?>
                <?php echo $form->dropDownList($model,'offer_type',CampaignBga::model()->getOfferTypes(), 
                                                array('class'=>'chzn-select-offer',
                                                  'prompt'=>'',
                                                  'data-placeholder'=>Sii::t('sii','Offer'),
                                                  'style'=>'width:90px;margin-top:3px;'
                                                 )); ?>
                <?php echo $form->error($model,'at_offer'); ?>
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
                            'style'=>'margin-right:5px;vertical-align:middle',

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
                            'style'=>'margin-right:5px;vertical-align:middle',

                        ),
                    ));
                ?>
                <?php echo $form->error($model,'end_date'); ?>
            </div>
        </div>

        <?php $this->spagesectionWidget($this->getSectionsData($model,true)); ?>
        
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

</div>