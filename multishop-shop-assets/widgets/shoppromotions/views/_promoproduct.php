<div class="promotion <?php echo $showActionBar?'with-action-bar':'';?> ">

    <div class="wrapper" href="javascript:void(0);" onclick="<?php echo $controller->loadCampaignViewScript($data,$page);?>" >
        <div class="buy-tag">
            <?php echo Sii::t('sii','Buy {n}',array($data->buy_x_qty));?>
        </div>
        <div class="get-tag">
           <div class="upper">
                <?php echo Sii::t('sii','Get {n}',array('{n}'=>$data->get_y_qty));?>
            </div>
            <div class="lower">
                <?php echo $data->getOfferTag(true,true);?>
            </div>
        </div>
        <div class="buy-item">
            <?php echo $data->x_product->getImageThumbnail(Image::VERSION_SMEDIUM,['class'=>'promotion-product-x'],$data->x_product->displayLanguageValue('name',user()->getLocale()));?>
        </div>
        <div class="get-item">
            <?php 
                if ($data->hasG())
                    echo $data->y_product->getImageThumbnail(Image::VERSION_SMEDIUM,['class'=>'promotion-product-y'],$data->y_product->displayLanguageValue('name',user()->getLocale()));
                else
                    echo $data->x_product->getImageThumbnail(Image::VERSION_SMEDIUM,['class'=>'promotion-product-y'],$data->x_product->displayLanguageValue('name',user()->getLocale()));
            ?>
        </div>

        <div class="hovertext">
            <?php if (!$data->onOfferXMore()):?>
                <div class="hover-price x">
                    <?php echo $data->formatCurrency($data->getUsualPrice($data->x_product,$data->buy_x_qty)); ?>
                </div>
            <?php endif;?>
            <div class="hover-price y">
            <?php 
                if ($data->hasG()){
                    echo $data->formatCurrency($data->getOfferPrice($data->y_product,$data->get_y_qty));
                    echo CHtml::tag('s',[],$data->formatCurrency($data->getUsualPrice($data->y_product,$data->get_y_qty)));
                }
                else {
                    echo $data->formatCurrency($data->getOfferPrice($data->x_product,$data->buy_x_qty));
                    echo CHtml::tag('s',[],$data->formatCurrency($data->getUsualPrice($data->x_product,$data->buy_x_qty)));
                }
            ?>
            </div>
        </div>
        
    </div>

    <?php   
        if ($showActionBar){
            $controller->renderPartial('shopwidgets.shoppromotions.views._actionbar',[
                'data'=>$data,
                'page'=>$page,
                'controller'=>$controller,
            ]);
        }
    ?>

</div>