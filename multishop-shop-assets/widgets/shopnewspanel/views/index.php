<?php
$this->widget('zii.widgets.jui.CJuiAccordion',[
    'id'=>'news_panel_'.$this->id,
    'htmlOptions'=>['class'=>'side-menu news-panel'],
    'panels'=>[
        $this->heading => $this->widget('zii.widgets.CMenu',[
                                'items'=>$this->news,
                                'encodeLabel'=>false, 
                            ],true),       
    ],
    // additional javascript options for the accordion plugin
    'options'=>[
        //'animated'=>'bounceslide',
        'autoHeight'=>false,
        'collapsible'=>false,
        'icons'=>false,
        'active'=>0,//put 0 for first panel to be active, 1 for second panel and so on
    ],
]);
