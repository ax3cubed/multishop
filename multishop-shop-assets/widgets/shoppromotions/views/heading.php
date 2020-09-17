<div class="section promotions">
    <span class="header">
        <?php echo Sii::t('sii','Promotions');?>
        <span class="more">
            <?php echo CHtml::link(Sii::t('sii','More'),$this->page->getUrl(ShopPage::PROMOTIONS),['data-page'=>ShopPage::PROMOTIONS]);?>
        </span>
    </span>
</div>
