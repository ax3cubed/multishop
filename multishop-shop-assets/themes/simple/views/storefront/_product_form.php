<?php
$form = $this->beginWidget('CActiveForm', array(
    'id'=>'cart-'.(isset($modal)?'modal-':'').'form',
    'action'=>$cartForm==null?'dummy':$cartForm->addCartUrl,
    'enableAjaxValidation'=>false,
  )); 

    echo $this->getCartFormPreparedData($cartForm,$model,user()->getLocale());

    $this->widget('common.widgets.SDetailView', array(
        'id'=>'product_view',
        'data'=>$model,
        'columns'=>array(
            array(
                'image-column'=>array(
                    'image'=>$this->simagezoomerWidget(array('imageOwner'=>$model),true),
                    'cssClass'=>SPageLayout::WIDTH_50PERCENT,
                    'visible'=>!isset($modal),
                ),
            ),        
            array(
                array('template'=>'<div id="flash-bar" class="flash-message">{value}</div>',
                      'type'=>'raw',
                      'value'=>$this->sflashWidget('cart',true)),
                array('template'=>'<div class="{class}"><div class="heading">{value}</div></div>',
                      'type'=>'raw',
                      'value'=>($model->isNew()?Helper::htmlColorTag(Sii::t('sii','New'),'red',false):'').
                               ($model->hasCampaign()?Helper::htmlColorText($model->getCampaign()->getOfferTag(false,true),false):'').
                               $model->displayLanguageValue('name',user()->getLocale()).
                               ($this->onPreview()?' '.Helper::htmlColorText($model->getStatusText()):''),
                      'visible'=>!isset($modal)),
                array('template'=>'<div class="{class}"><div class="price">{value}</div></div>',
                      'type'=>'raw',
                      'cssClass'=>'price-container product-field',
                      'value'=>$this->renderPartial($this->getThemeView('_product_price'),array('data'=>$this->module->serviceManager->getCampaignManager()->checkProductPrice($model,1,$model->hasCampaign()?$model->getCampaign():null)),true)
                ),
                array('name'=>'weight','value'=>$model->formatWeight($model->weight),'visible'=>$model->hasWeightBasedShipping()),
                array('template'=>'<div class="{class}">{value}</div>',
                      'type'=>'raw',
                      'cssClass'=>'product-option product-field',
                      'value'=>Helper::htmlKeyValueArray($this->getProductOptionsArray($model,user()->getLocale()),array('class'=>'options','id'=>'product_option'))
                ),
                array('name'=>'quantity',
                      'type'=>'raw',
                      'label'=>$cartForm->getAttributeLabel('quantity'),
                      'cssClass'=>'product-quantity product-field',
                      'value'=>CHtml::activeDropDownList(
                                    $cartForm, 
                                    'quantity', 
                                    $model->hasCampaign()?Helper::getQuantityList($cartForm->quantity,$model->shop->getCheckoutQuantityLimit(),$cartForm->quantity):Helper::getQuantityList(1,$model->shop->getCheckoutQuantityLimit(),1)
                                ).
                                CHtml::tag('span',array('class'=>'inventory-status'),$model->getInventoryText()),
                ),
                array('template'=>'<div class="{class}">{value}</div>',
                      'type'=>'raw',
                      'cssClass'=>'product-shipping product-field',
                      'value'=>Helper::htmlKeyValueArray(array($cartForm->getAttributeLabel('shipping_id')=>$model->hasCampaign()?$model->getCampaign()->getShippingsDataArray(user()->getLocale()):$model->getShippingsDataArray(user()->getLocale())),array('class'=>'options','id'=>'shipping_id'))
                ),
                array('template'=>'<div class="{class}">{value}</div>',
                      'cssClass'=>'cartbutton',
                      'type'=>'raw',
                      'value'=>$this->widget('zii.widgets.jui.CJuiButton',array(
                                        'name'=>'buy-button',
                                        'buttonType'=>'button',
                                        'caption'=>Sii::t('sii','Add To Cart'),
                                        'value'=>'buybtn',
                                        'options'=>array('disabled'=>($this->onPreview()||!$model->hasInventory()||$cartForm==null)?true:false),
                                        'htmlOptions'=>array('class'=>'ui-button','form'=>'cart-'.(isset($modal)?'modal-':'').'form'),
                                        'onclick'=>'js:function(){'.$cartForm->addCartScript.'}',
                                    ),true)
                ),                                
                array('template'=>'<div id="promo-buttons" class="{class}">{value}</div>',
                      'type'=>'raw',
                      'value'=>'<div>'.Sii::t('sii','or').'</div>'.
                               $this->getCampaignButtons($model,$model->hasCampaign()?$model->getCampaign()->id:null),
                      'visible'=>$model->hasOtherCampaigns()
                ),
                [
                    'template'=>'<div class="{class}">{value}</div>',
                    'cssClass'=>'product-counter-wrapper',
                    'type'=>'raw',
                    'value'=>$this->renderPartial($this->getThemeView('_product_counter'),[
                        'likeForm'=>$likeForm,
                        'commentForm'=>$commentForm,
                        'shareUrl'=>$model->getUrlForSocialMedia(),
                        'showLikeButton'=>true,
                    ],true),
                    'visible'=>!isset($modal),
                ],                                                
            ),         
        ),
        'htmlOptions'=>array('class'=>'detail-view','data-inventory'=>$model->getInventory()),
    ));//end detail view widget

$this->endWidget();//end form widget