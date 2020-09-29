<div class="column-left">

    <div class="product rounded">

        <div class="name">
            <div class="promotion-info">
                <?php echo Helper::htmlColorTag($campaign->getOfferTag(true),'#f23649',false);?>
                <span class="validity"><?php echo $campaign->getValidityText();?></span>
            </div>
            <div class="title"><?php echo $campaign->x_product->displayLanguageValue('name',user()->getLocale());?></div>
            <!--<span class="inventory-status"><?php //echo $campaign->x_product->getInventoryText();?></span>-->
            <div class="info-container">
                <div class="source">
                    <?php echo Sii::t('sii','From').' '.l($campaign->x_product->shop->displayLanguageValue('name',user()->getLocale()),$campaign->x_product->shopurl);?>
                </div>
                <div class="counter-bar">
                    <?php $this->renderPartial($this->getThemeView('_product_counter'),
                                               array('likeForm'=>$likeForm,'commentForm'=>$commentForm,'showLikeButton'=>false));
                    ?>
                </div>
            </div>
        </div>

        <?php $this->simagezoomerWidget(array('imageOwner'=>$campaign->x_product));?>
        <?php //$this->simageviewerWidget(array('imageModel'=>$campaign->x_product)); ?>
        
        <?php echo $this->renderPartial($this->getThemeView('_product_comments'),array('page'=>$page,'dataProvider'=>$commentDataProvider,'commentForm'=>$commentForm,'modal'=>true));?>
        
    </div>

</div>

<div class="column-right">

    <?php if (isset($campaign->y_product)):?>
    <div class="palette promotion-item rounded ">
        <?php echo $this->renderPartial($this->getThemeView('_promo_product_y'),array('campaign'=>$campaign,'modal'=>true));?>
    </div>
    <?php endif;?>
    
    <div class="palette rounded">
        <?php $this->renderPartial($this->getThemeView('_promo_form'),
                                   array('model'=>$campaign,
                                         'cartForm'=>$cartForm,
                                         'commentForm'=>$commentForm,
                                         'likeForm'=>$likeForm,
                                         'modal'=>true));?>
    </div>
        
    <div class="tailing rounded-bottom"></div>

    <div class="spaceline"></div>

    <div class="palette rounded">
        <div class="head rounded-top">
            <?php echo Sii::t('sii','Actions');?>
        </div>
        <div class="normal border-top">
            <?php echo $this->renderPartial($this->getThemeView('_product_like'),array('likeForm'=>$likeForm));?>
        </div>
        <div  class="normal border-top">
            <?php echo l(Sii::t('sii','I want to ask question'),$page->getCampaignPageUrl(CampaignPage::QUESTION));?>
        </div>
        <div class="normal border-top rounded-bottom">
            <?php echo l(Sii::t('sii','I want to view full promotion page'),$page->campaignUrl);?>
        </div>
    </div>
</div>