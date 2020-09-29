<?php
$this->widget('common.widgets.SDetailView', array(
    'data'=>$data,
    'htmlOptions'=>array('id'=>'shop_list','class'=>'list-box float-image'),
    'attributes'=>array(
        array(
            'type'=>'raw',
            'template'=>'<div class="status">{value}</div>',
            'value'=>Helper::htmlColorText($data->getStatusText(),false),
        ),
        array(
            'type'=>'raw',
            'template'=>'<div class="action">{value}</div>',
            'value'=>$this->widget('zii.widgets.CMenu', array(
                'encodeLabel'=>false,
                'htmlOptions'=>array('class'=>'shortcuts'),
                'items'=>array(
                    array('label'=>SButtonColumn::getButtonIcon('dashboard'), 'url'=>$data->gotoUrl('dashboard'),'itemOptions'=>array('title'=>Sii::t('sii','Dashboard')),'visible'=>$data->updatable()),
                    array('label'=>SButtonColumn::getButtonIcon('product'), 'url'=>$data->gotoUrl('products'),'itemOptions'=>array('title'=>Sii::t('sii','Products Management')),'visible'=>$data->updatable()),
                    array('label'=>SButtonColumn::getButtonIcon('campaign'), 'url'=>$data->gotoUrl('campaigns'),'itemOptions'=>array('title'=>Sii::t('sii','Campaigns Management')),'visible'=>$data->updatable()),
                    array('label'=>SButtonColumn::getButtonIcon('ship'), 'url'=>$data->gotoUrl('shippings'),'itemOptions'=>array('title'=>Sii::t('sii','Shippings Management')),'visible'=>$data->updatable()),
                    array('label'=>SButtonColumn::getButtonIcon('payment'), 'url'=>$data->gotoUrl('paymentMethods'),'itemOptions'=>array('title'=>Sii::t('sii','Payment Methods Management')),'visible'=>$data->updatable()),
                    array('label'=>SButtonColumn::getButtonIcon('tax'), 'url'=>$data->gotoUrl('taxes'),'itemOptions'=>array('title'=>Sii::t('sii','Taxes Management')),'visible'=>$data->updatable()),
                    array('label'=>SButtonColumn::getButtonIcon('inventory'), 'url'=>$data->gotoUrl('inventories'),'itemOptions'=>array('title'=>Sii::t('sii','Inventory Management')),'visible'=>$data->updatable()),
                    array('label'=>SButtonColumn::getButtonIcon('news'), 'url'=>$data->gotoUrl('news'),'itemOptions'=>array('title'=>Sii::t('sii','News Blog')),'visible'=>$data->updatable()),
                    array('label'=>SButtonColumn::getButtonIcon('question-circle'), 'url'=>$data->gotoUrl('shop-questions'),'itemOptions'=>array('title'=>Sii::t('sii','Questions Management')),'visible'=>$data->updatable()),
                    array('label'=>SButtonColumn::getButtonIcon('design'), 'url'=>$data->gotoUrl('shop-themes'),'itemOptions'=>array('title'=>Sii::t('sii','Themes Management')),'visible'=>$data->updatable()),
                    array('label'=>SButtonColumn::getButtonIcon('settings'), 'url'=>$data->gotoUrl('shop-settings'),'itemOptions'=>array('title'=>Sii::t('sii','Shop Settings')),'visible'=>$data->updatable()),
                ),
            ),true),
        ),
        array(
            'type'=>'raw',
            'template'=>'<div class="image">{value}</div>',
            'value'=>$data->getImageThumbnail(Image::VERSION_ORIGINAL,array('style'=>'width:'.Image::VERSION_MEDIUM.'px;')),
        ),
        array(
            'type'=>'raw',
            'template'=>'{value}',
            'value'=>$this->widget('common.widgets.SDetailView', array(
                'data'=>$data,
                'htmlOptions'=>array('class'=>'data'),
                'attributes'=>array(
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="heading-element">{value}</div>',
                        'value'=>CHtml::link(CHtml::encode($data->parseName(user()->getLocale())), $data->viewUrl),
                    ),
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="element">{value}</div>',
                        'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('timezone')).'</strong>'.
                                 CHtml::encode(SLocale::getTimeZones($data->timezone)),
                    ),        
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="element">{value}</div>',
                        'value'=>'<strong>'.CHtml::encode(Sii::t('sii','Payment Method')).'</strong>'.
                                 $this->getPaymentMethodList($data->id,user()->getLocale()),
                    ),        
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="element">{value}</div>',
                        'value'=>'<strong>'.CHtml::encode(Sii::t('sii','Shipping')).'</strong>'.
                                 $this->getShippingList($data->id,user()->getLocale()),
                    ),        
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="element">{value}</div>',
                        'value'=>'<strong>'.CHtml::encode(Sii::t('sii','Tax')).'</strong>'.
                                 $this->getTaxList($data->id,user()->getLocale()),
                    ),        
                    array(
                        'type'=>'raw',
                        'template'=>'<div class="element">{value}</div>',
                        'value'=>'<strong>'.CHtml::encode(Sii::t('sii','Campaign')).'</strong>'.
                                 $this->getCampaignList($data->id,user()->getLocale()),
                    ),        
                ),
            ),true),
        ),                
    ),
));