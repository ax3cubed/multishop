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
                array('template'=>'<div id="flash-bar" class="flash-message">{value}</div>',
                      'type'=>'raw',
                      'value'=>$this->sflashWidget('cart',true)),
                array('template'=>'<div class="{class}"><span class="grand_total">{value}</span><span class="subscript">'.Sii::t('sii','Total Price').'</span></div>',
                      'value'=>$model->formatCurrency($model->getOfferTotalPrice($model->buy_x_qty)),
                      'cssClass'=>'grand-total-wrapper',
                ),
                array('template'=>'<div class="{class} promo"><div class="price">{value}</div></div>',
                      'type'=>'raw',
                      'value'=>$this->renderPartial($this->getThemeView('_product_price'),
                                                    array('data'=>$this->module->serviceManager->getCampaignManager()->checkProductPrice(array('model'=>$model->x_product,'xProduct'=>true),$model->buy_x_qty,$model)),
                                                    true)
                ),
                array('name'=>'quantity',
                      'type'=>'raw',
                      'label'=>$cartForm->getAttributeLabel('quantity'),
                      'value'=>CHtml::activeDropDownList(
                                    $cartForm, 
                                    'quantity', 
                                    Helper::getQuantityList($model->buy_x_qty,$model->shop->getCheckoutQuantityLimit(),$model->buy_x_qty),
                                    array('prompt'=>'',
                                          'class'=>'chzn-select-quantity',
                                          'data-placeholder'=>'',
                                          'style'=>'width:30%;')).
                              CHtml::tag('span',array('class'=>'inventory-status'),$model->x_product->getInventoryText()),
                ),
                array('template'=>'<div class="{class}">{value}</div>',
                      'type'=>'raw',
                      'cssClass'=>'product-shipping',
                      'value'=>Helper::htmlKeyValueArray(array($cartForm->getAttributeLabel('shipping_id')=>$model->getShippingsDataArray(user()->getLocale())),array('class'=>'options','id'=>'shipping_id'))
                ),
                array('name'=>'weight','value'=>$model->formatWeight($model->x_product->weight),'visible'=>$model->x_product->hasWeightBasedShipping()),
                array('template'=>'<div class="{class}">{value}</div>',
                      'type'=>'raw',
                      'cssClass'=>'product-option',
                      'value'=>Helper::htmlKeyValueArray($this->getProductOptionsArray($model->x_product,user()->getLocale()),array('class'=>'options','id'=>'product_option'))
                ),                
                array('template'=>'<div class="{class}">{value}</div>',
                      'cssClass'=>'cartbutton',
                      'type'=>'raw',
                      'value'=>$this->widget('zii.widgets.jui.CJuiButton',array(
                                    'name'=>'buy-button',
                                    'buttonType'=>'button',
                                    'caption'=>Sii::t('sii','Add To Cart'),
                                    'value'=>'buybtn',
                                    'options'=>array('disabled'=>($cartForm==null||!$model->x_product->hasInventory())?true:false),
                                    'htmlOptions'=>array('class'=>'ui-button','form'=>'cart-'.(isset($modal)?'modal-':'').'form'),
                                    'onclick'=>'js:function(){'.$cartForm->addCartScript.'}',
                                ),true)
                ),                                
                array('template'=>'<div id="promo-buttons" class="{class}">{value}</div>',
                      'type'=>'raw',
                      'value'=>'<div>'.Sii::t('sii','or').'</div>'.
                               $this->getCampaignButtons(array('campaign'=>$model,'exclude'=>$model->id)),
                      'visible'=>$model->x_product->countCampaigns($model->id)>0&&$model->buy_x!=$model->x_product->id,
                ),
            ),         
        ),
        'htmlOptions'=>array('class'=>'detail-view'),
    ));//end detail view widget

$this->endWidget();//end form widget