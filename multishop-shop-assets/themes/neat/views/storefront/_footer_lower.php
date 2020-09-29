<p class="copyright">
    <?php echo Sii::t('sii','&copy; {year},',['{year}'=>date('Y')]);?>
    <a href="<?php echo $page->shopModel->getUrl(request()->isSecureConnection);?>" title=""><?php echo $page->shopModel->displayLanguageValue('name',user()->getLocale());?></a>
</p>