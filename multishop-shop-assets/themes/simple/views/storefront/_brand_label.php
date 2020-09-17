<?php echo l($page->shopModel->getLogo(['class'=>'brand-logo']),$page->homeUrl);?>
<div class="tagline">
    <?php echo $page->shopModel->displayLanguageValue('tagline',user()->getLocale()); ?>
</div>
