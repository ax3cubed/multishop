<div class="column-left">

    <div class="product rounded">

        <div class="name">
            <?php if ($model->isNew()&&!$model->hasCampaign()){
                        echo CHtml::openTag('div',array('class'=>'promotion-info'));
                        echo Helper::htmlColorTag(Sii::t('sii','New'),'red',false);
                        echo CHtml::closeTag('div');
                  }
            ?>
            <?php if ($model->hasCampaign()){
                        echo CHtml::openTag('div',array('class'=>'promotion-info'));
                        echo Helper::htmlColorText($model->getCampaign()->getOfferTag(false,true),false);
                        if ($model->isNew())
                            echo CHtml::tag('span',array('class'=>'new-info'),Sii::t('sii','New'));
                        echo CHtml::tag('span',array('class'=>'validity'),$model->getCampaign()->getValidityText());
                        echo CHtml::closeTag('div');
                  }
            ?>
            <div class="title"><?php echo $model->displayLanguageValue('name',user()->getLocale());?></div>
            <div class="info-container">
                <div class="source"><?php echo Sii::t('sii','From').' '.l($model->shop->displayLanguageValue('name',user()->getLocale()),$model->shopurl);?></div>
                <div class="counter-bar">
                    <?php $this->renderPartial($this->getThemeView('_product_counter'),
                                               array('likeForm'=>$likeForm,
                                                     'shareUrl'=>$model->getUrlForSocialMedia(),
                                                     'commentForm'=>$commentForm,
                                                     'showLikeButton'=>false));
                    ?>
                </div>
            </div>
        </div>

        <?php $this->simagezoomerWidget(array('imageOwner'=>$model));?>
        <?php //$this->simageviewerWidget(array('imageModel'=>$model));?>

        <?php echo $this->renderPartial($this->getThemeView('_product_comments'),['page'=>$page,'dataProvider'=>$commentDataProvider,'commentForm'=>$commentForm,'modal'=>true]);?>
        
    </div>

</div>

<div class="column-right">

    <div class="palette rounded">
        <?php $this->renderPartial($this->getThemeView('_product_form'),[
                'model'=>$model,
                'cartForm'=>$cartForm,
                'commentForm'=>$commentForm,
                'likeForm'=>$likeForm,
                'modal'=>true,
            ]);
        ?>
    </div>
        
    <div class="tailing rounded-bottom"></div>

    <div class="spaceline"></div>

    <div class="palette rounded">
        <div class="head rounded-top">
            <?php echo Sii::t('sii','Actions');?>
        </div>
        <div class="normal border-top">
            <?php echo $this->renderPartial($this->getThemeView('_product_like'),['likeForm'=>$likeForm]);?>
        </div>
        <div  class="normal border-top">
            <?php echo l(Sii::t('sii','I want to ask question'),$page->getProductPageUrl(ProductPage::QUESTION));?>
        </div>
        <div class="normal border-top rounded-bottom">
            <?php echo l(Sii::t('sii','I want to view full product page'),$page->productUrl);?>
        </div>
    </div>
</div>