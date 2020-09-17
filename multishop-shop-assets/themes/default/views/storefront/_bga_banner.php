<div class="promo-product detail-view " <?php echo isset($onclick)?'onclick="'.$onclick.'"':'';?> >
    <div class="promo-block">
        <div class="offer large-font">
            <div>
                <?php echo $model->getAttributeLabel('buy_x'); ?>
                <?php echo $model->buy_x_qty;?>
            </div>
        </div>
        <div class="offer" <?php echo $model->hasG()?'':'style="max-width:100%;"'; ?> >
            <div>
                <?php echo $model->x_product->getImageThumbnail(Image::VERSION_SMALL,array('width'=>80,'height'=>80)); ?>
            </div>    
            <div>
                <span class="product-name">
                    <?php echo $model->x_product->displayLanguageValue('name',user()->getLocale()); ?>
                    <?php  if (isset($showStatus))
                            echo Helper::htmlColorText($model->x_product->getStatusText()); 
                    ?>
                </span>

                <span class="unit-price">
                    <strong><?php echo CHtml::encode($model->x_product->getAttributeLabel('unit_price')); ?></strong>
                    <?php echo CHtml::encode($model->x_product->formatCurrency($model->x_product->unit_price)); ?>
                </span>
                <?php if (!$model->hasG()): ?>
                <span class="usual-price">
                    <strong><?php echo Sii::t('sii','Usual Price'); ?></strong>
                    <i><s><?php echo CHtml::encode($model->formatCurrency($model->getUsualPrice($model->x_product,$model->buy_x_qty))); ?></s></i>
                </span>
                <span class="offer-price">
                    <strong><?php echo Sii::t('sii','Offer Price'); ?></strong>
                    <?php echo CHtml::encode($model->formatCurrency($model->getOfferPrice($model->x_product,$model->buy_x_qty))); ?>
                </span>
                <?php endif;?>
            </div>
        </div>
    </div>    

    <?php if ($model->hasG()): ?>
    <div class="promo-block">
        <div class="offer large-font">
            <div>
                <?php echo $model->getAttributeLabel('get_y'); ?>
                <?php echo $model->get_y_qty;?>
            </div>
        </div>
        <div class="offer">
            <div>
                <?php echo $model->y_product->getImageThumbnail(Image::VERSION_SMALL,array('width'=>80,'height'=>80)); ?>
            </div>    
            <div>
                <span class="product-name">
                    <?php echo $model->y_product->displayLanguageValue('name',user()->getLocale()); ?>
                    <?php  if (isset($showStatus))
                            echo Helper::htmlColorText($model->y_product->getStatusText()); 
                    ?>
                </span>
                <span class="unit-price">
                    <strong><?php echo CHtml::encode($model->y_product->getAttributeLabel('unit_price')); ?></strong>
                    <?php echo CHtml::encode($model->y_product->formatCurrency($model->y_product->unit_price)); ?>
                </span>
                <span class="usual-price">
                    <strong><?php echo Sii::t('sii','Usual Price'); ?></strong>
                    <i><s><?php echo CHtml::encode($model->formatCurrency($model->getUsualPrice($model->y_product,$model->get_y_qty))); ?></s></i>
                </span>
                <span class="offer-price">
                    <strong><?php echo Sii::t('sii','Offer Price'); ?></strong>
                    <?php echo CHtml::encode($model->formatCurrency($model->getOfferPrice($model->y_product,$model->get_y_qty))); ?>
                </span>
            </div>
        </div>
    </div>
    <?php endif;?>
    
    <div class="promo-block">
        <div class="offer large-font">
            <div class="offer-text">
                <?php echo $model->getCampaignText(user()->getLocale(),CampaignBga::A); ?>
            </div>
        </div>
    </div>

    <div class="validity <?php echo $model->hasExpired()?'expired':'';?> ">
        <?php echo ($model->hasExpired()?$model->getExpiredTag():'').$model->getValidityText(); ?>
    </div>
    
</div>
