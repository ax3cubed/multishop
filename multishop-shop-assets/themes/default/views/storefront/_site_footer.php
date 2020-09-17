<?php 
//---------------------------------------//
//This is the default site footer to be displayed if shop does not configure to hide it.
//Each theme could customize this footer according to theme needs.
//The theme file can be found at SHOPTHEME_BASE_PATH/[theme_name]/views/storefront/_site_footer.php
//---------------------------------------//
?>
<div class="lower_footer">
    <?php if (request()->isSecureConnection):?>
        <div class="column ssl-site-seal">
            <?php echo CHtml::image($this->getImage('comodo_secure_seal_100x85.png'),'SSL Site Seal'); ?>
        </div>
    <?php endif;?>
    <div class="<?php echo request()->isSecureConnection?'column':'';?>">
        <?php echo Sii::t('sii','This shop is powered by {app} &copy; {year}.',['{year}'=>date('Y'),'{app}'=>CHtml::link(app()->name,app()->urlManager->createHostUrl('contact'))]); ?>
    </div>    
</div>


