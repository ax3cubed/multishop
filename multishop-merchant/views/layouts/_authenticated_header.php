<span class="sitelogo <?php echo app()->ctrlManager->hasSiteLogo ? 'on' :'off'; ?>">
    <?php echo app()->ctrlManager->getSiteLogo(); ?>
    <span class="subscript rounded3"><?php echo param('APP_VERSION'); ?></span>
</span>
<?php Yii::app()->ctrlManager->getCanvasMenu($this); ?>
<div class="color-bar">
    <div class="red"></div>
    <div class="yellow"></div>
    <div class="green"></div>
    <div class="blue"></div>    
</div>