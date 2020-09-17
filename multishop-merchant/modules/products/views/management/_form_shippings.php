<?php   
$runScript = <<<EOJS
$(document).ready(function(){
    $('.chzn-select-shipping').chosen().change(function(){getshipping($(this).val());});
});
EOJS;

$this->widget('common.widgets.schildform.SChildForm', array(
    'stateVariable' => $this->stateVariable,
    'divSection' => CHtml::dropDownList('Shipping[ids]', '',
                        $model->getShippingsArray(user()->getLocale()),
                        array('prompt'=>'',
                             'class'=>'chzn-select-shipping',
                             'multiple'=>true,
                             'options'=>SActiveSession::loadAsSelected($this->stateVariable,'shipping_id'),
                             'data-placeholder'=>Sii::t('sii','Select Shipping'),
                             'style'=>'width:100%;')
    ),
    'htmlOptions' => array(
        'id'=>'shipping_table_container',
    ),
    'headerData'=>array(
        Shipping::model()->getAttributeLabel('status'),
        Shipping::model()->getAttributeLabel('name'),
        Shipping::model()->getAttributeLabel('method'),
        Shipping::model()->getAttributeLabel('rate'),
        ProductShipping::model()->getAttributeLabel('surcharge'),
    ),
    'runScript' => $runScript,
));