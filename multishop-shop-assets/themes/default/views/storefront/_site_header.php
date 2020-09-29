<?php
//---------------------------------------//
//This is the default site header to be displayed if shop does not configure to hide it.
//Each theme could customize this header according to theme needs.
//The theme file can be found at SHOPTHEME_BASE_PATH/[theme_name]/views/storefront/_site_header.php
//---------------------------------------//
?>
<?php if ($showAppLogo): ?>
<span class="sitelogo">
    <?php echo CHtml::link(app()->name,app()->urlManager->getHomeUrl()); ?>
    <span class="subscript rounded3"><?php echo param('APP_VERSION'); ?></span>
</span>
<?php endif; ?>
<?php 
$this->widget('shopwidgets.shopmenu.ShopNavMenu',[
    'user'=>user(),
    'shop'=>$shop,
    'offSite'=>$offSite,
]);