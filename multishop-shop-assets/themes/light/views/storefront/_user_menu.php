<?php 
$this->widget('shopwidgets.shopmenu.ShopNavMenu',[
    'user'=>user(),
    'page'=>$page,
    'cartScript'=>'opencartdrawer();',
    'offSite'=>ShopNavigation::isOffSite(),
]);