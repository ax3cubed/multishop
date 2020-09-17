<?php
$this->widget('common.widgets.spage.SPage',[
    'id'=> 'shopping_cart',
    'flash' => ['cart','cart2'],
    'layout'=> false,
    'heading'=> false,
    'linebreak'=>false,
    'loader'=>false,
    'body'=>$this->getCartData($shop,$queryParams),
]);
