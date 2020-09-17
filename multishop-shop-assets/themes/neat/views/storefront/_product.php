<div class="product">
    
    <?php if ($this->onPreview()):?>
        <div class="preview-status">
            <?php echo Sii::t('sii','PREVIEW ONLY');?>
            <?php echo Helper::htmlColorText($data->getStatusText(),false);?>
        </div>
    <?php endif;?>
    
    <a href="javascript:void(0);" onclick="<?php echo $this->loadProductViewScript($data,$page);?>">
        <?php echo $data->getImageThumbnail(Image::VERSION_LARGE,['class'=>'img'],$data->displayLanguageValue('name',user()->getLocale()));?>
    </a>
    
    <a class="product-info" href="javascript:void(0);" onclick="<?php echo $this->loadProductViewScript($data,$page);?>">
        <div class="product-name">
            <?php echo Helper::rightTrim($data->displayLanguageValue('name',user()->getLocale()),38); ?>
        </div>
        <div class="product-price">
            <?php echo $data->formatCurrency($data->getPrice()); ?>
            <?php if ($data->hasCampaign())
                      echo CHtml::tag('s',[],$data->formatCurrency($data->unit_price));
            ?>
       </div>
    </a>
    
    <?php if ($data->hasCampaign()):?>
    <div class="offer-tag">
        <a href="javascript:void(0);" onclick="<?php echo $this->loadProductViewScript($data,$page);?>">
            <?php if ($data->isNew()):?>
                <?php echo CHtml::tag('span',['style'=>'font-size:0.5em;'],Helper::htmlColorTag(Sii::t('sii','New'),'orangered',false));?>
            <?php endif;?>
            <?php echo Helper::htmlColorText($data->getCampaign()->getOfferTag(false,true),false);?>
        </a>
    </div>
    <?php endif;?>
    
    <?php if ($data->isNew()&&!$data->hasCampaign()):?>
    <div class="new-tag">
        <?php echo Helper::htmlColorTag(Sii::t('sii','New'),'red',false);?>
    </div>
    <?php endif;?>
    
    <?php if (isset($data->most_counter) && $page->trendTopic==ShopPage::TREND_MOSTPURCHASED):?>
        <div class="most-counter">
            <span class="fa-stack fa-lg">
                <i class="fa fa-bookmark fa-stack-2x"></i>
                <span class="fa fa-stack-1x value"><?php echo $data->most_counter;?></span>
            </span>
        </div>
    <?php endif;?>

    <?php 
//        echo 'Product field 1: '.$theme->getValue('product','product_field_1');
//        echo 'Product field 2: '.$theme->getValue('product','product_field_2');
//        echo 'Layout field 2: '.$theme->getValue('layout','layout_field_1');
    ?>
    
    
</div>
