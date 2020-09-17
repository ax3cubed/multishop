<?php 
$this->widget('shopwidgets.shopmenu.ShopNavMenu',[
    'user'=>user(),
    'page'=>$page,
    'offSite'=>ShopNavigation::isOffSite(),
]);