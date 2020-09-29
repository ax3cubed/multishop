<?php
echo CHtml::form($url,'post');
echo CHtml::hiddenField('payment_data',isset($data)?$data:null);
$this->widget('zii.widgets.jui.CJuiButton',
    array(
        'id'=>'actionButton'.uniqid(),
        'name'=>'actionButton',
        'caption'=>$caption,
        'value'=>'actionbtn',
    )
);    
echo CHtml::endForm();
