<?php
$this->widget('zii.widgets.CMenu', [
    'items'=>user()->getShopMenu($model),
    'encodeLabel'=>false,                            
    'htmlOptions'=>['class'=>'sidebar-menu theme shop'],
]);
