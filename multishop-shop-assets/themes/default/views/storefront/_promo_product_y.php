<?php $this->widget('common.widgets.SDetailView', array(
        'id'=>'promo_product_view',
        'htmlOptions'=>array('class'=>isset($modal)?'promo-product detail-view':'promo-product-y detail-view rounded'),
        'data'=>$campaign,
        'attributes'=>array(
            array('template'=>'<div class="{class}"><span class="name">{value}</span></div>',
                  'type'=>'raw',
                  'value'=>Helper::htmlColorText($campaign->getOfferTag(false,true)).$campaign->y_product->displayLanguageValue('name',user()->getLocale()),
            ),
            array('template'=>'<div class="{class} product-y-image">{value}</div>',
                'type'=>'raw',
                'value'=>$this->simagezoomerWidget(array('imageOwner'=>$campaign->y_product,'defaultVersion'=>Image::VERSION_MEDIUM),true),
                //'value'=>$this->simageviewerWidget(array('imageModel'=>$campaign->y_product,'imageName'=>$campaign->y_product->getLanguageValue('name',user()->getLocale()),'imageVersion'=>Image::VERSION_MEDIUM,'showThumbnail'=>false),true)
            ),
            array('template'=>'<div class="{class}">{value}</div>',
                'type'=>'raw',
                'cssClass'=>'info-container price',
                'value'=>$this->renderPartial($this->getThemeView('_product_price'),
                         array('data'=>$this->module->serviceManager->getCampaignManager()->checkProductPrice($campaign->y_product,$campaign->get_y_qty,$campaign)),true)
            ),
            array('template'=>'<div class="{class}">{value}</div>',
                  'type'=>'raw',
                  'cssClass'=>'option-container',
                  'value'=>Helper::htmlKeyValueArray(
                            $this->module->runControllerMethod('shops/storefront','getProductOptionsArray',$campaign->y_product),
                            array('class'=>'options','id'=>'promo_product_option')),
                  'visible'=>count($campaign->y_product->attrs)>0,
            ),
        ),
    ));//end detail view widget
