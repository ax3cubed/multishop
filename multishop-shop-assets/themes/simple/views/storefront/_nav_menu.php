<?php 
bootstrap()->beginNavBar([
    //'brandLabel' => 'Brand Label',
]);
echo bootstrap()->Nav([
    'items'=> $this->getNavigationMenu($page->shopModel,$page)->data,
    'options'=>[
        'class'=>'nav-pills'
    ],
]);        
bootstrap()->endNavBar();
