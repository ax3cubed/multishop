<?php
$this->widget('zii.widgets.jui.CJuiAccordion',[
    'id'=>$this->menuId,
    'htmlOptions'=>['class'=>'product-base-menu '.$this->menuCssClass],
    'panels'=>[
        $this->menuName => $this->widget('zii.widgets.CMenu', [
                                            'items'=>$this->menu->getData($this->activeMenuItem),
                                            'encodeLabel'=>false, 
                                        ],true),
    ],
    // additional javascript options for the accordion plugin
    'options'=>[
        //'animated'=>'bounceslide',
        'autoHeight'=>false,
        'collapsible'=>true,
        'active'=>0,//put 0 for first panel to be active, 1 for second panel and so on
    ],
]);