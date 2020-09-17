<?php
//Desktop navigation menu
$this->getOwner()->widget('common.widgets.snavigationmenu.SNavigationMenu',[
    'menu'=>$navMenu,
    'menuCssClass'=>'nav-menu',
    'itemsCssClass'=>'nav-menuitems',
    'loadPikabu'=>false,
]);

//Mobile shop menu opener
echo $shopMobileButton;

//Mobile cart button opener
echo $cartMobileButton;

//Mobile login menu opener
echo $welcomeMobileButton;    