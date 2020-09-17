<div class="subform">  
    
    <div>
        <?php echo CHtml::activeLabelEx($model,'address1'); ?>
        <?php echo CHtml::activeTextField($model,'address1',array('size'=>60,'maxlength'=>100)); ?>
        <br>
        <?php echo CHtml::activeTextField($model,'address2',array('size'=>60,'maxlength'=>100)); ?>
        <?php echo Chtml::error($model,'address1'); ?>
        <?php echo Chtml::error($model,'address2'); ?>
    </div>

    <div>
        <div style="display:inline-block;vertical-align:top;">
            <?php echo CHtml::activeLabelEx($model,'postcode'); ?>
            <?php echo CHtml::activeTextField($model,'postcode',array('size'=>30,'maxlength'=>20)); ?>
            <?php echo Chtml::error($model,'postcode'); ?>
       </div>
        <div style="display:inline-block">
            <?php echo CHtml::activeLabelEx($model,'city'); ?>
            <?php echo CHtml::activeTextField($model,'city',array('size'=>26,'maxlength'=>40)); ?>
            <?php echo Chtml::error($model,'city'); ?>
        </div>
   </div>

    <div>
        <div style="display:inline-block">
            <?php echo CHtml::activeLabelEx($model,'country',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo Chtml::activeDropDownList($model,'country',
                                            SLocale::getCountries(),
                                            array('class'=>'chzn-select-country',
                                                  'prompt'=>'',
                                                  'data-placeholder'=>Sii::t('sii','Select Country'),
                                                  'style'=>'width:200px;')); 
            ?>
            <?php echo Chtml::error($model,'country'); ?>
        </div>
        
        <div class="state-wrapper" style="display:inline-block;vertical-align:top;">
            <?php echo CHtml::activeLabelEx($model,'state',array('style'=>'margin-bottom:3px;')); ?>
            <?php echo Chtml::activeDropDownList($model,'state',
                                            SLocale::getStates($model->country),
                                            array('class'=>'chzn-select-state',
                                                  'prompt'=>'',
                                                  'data-placeholder'=>Sii::t('sii','Select State'),
                                                  'style'=>'width:180px;')); 
            ?>
            <?php echo Chtml::error($model,'state'); ?>
        </div>
        
    </div>

</div>
<?php $this->widget('SStateDropdown',array(
    'stateGetActionUrl'=>url('shops/management/stateget'),
    'countryFieldId'=>'ShopAddressForm_country',
    'stateFieldId'=>'ShopAddressForm_state',
));