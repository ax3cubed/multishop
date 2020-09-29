<div class="list-box float-image">
    <span class="status">
        <?php echo Helper::htmlColorText($data->getStatusText(),false); ?>
    </span>
    <div class="image">
        <?php echo $data->getImageThumbnail(Image::VERSION_SMEDIUM); ?>
    </div>
    <?php $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data'),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="heading-element">{value}</div>',
                    'value'=>CHtml::link(CHtml::encode($data->displayLanguageValue('name',user()->getLocale())), $data->viewUrl),
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('shop_id')).'</strong>'.
                             CHtml::link(CHtml::encode($data->shop->displayLanguageValue('name',user()->getLocale())), $data->shop->viewUrl),
                    'visible'=>!$this->hasParentShop(),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode($data->getAttributeLabel('unit_price')).'</strong>'.
                             CHtml::encode($data->formatCurrency($data->unit_price)),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode(Sii::t('sii','Inventory')).'</strong>'.
                             $data->getInventoryText(),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode(Sii::t('sii','Category')).'</strong>'.
                             ($data->hasCategories?Helper::htmlList($data->getCategoriesData(user()->getLocale()),array('style'=>'margin:0;')):Sii::t('sii','unset')),
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode(Sii::t('sii','Shipping')).'</strong>'.
                             $this->getProductShippingList($data,user()->getLocale()),
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.CHtml::encode(Sii::t('sii','Campaign')).'</strong>'.
                             $this->getProductCampaignList($data,user()->getLocale()),
                ),
            ),
        )); 
    ?> 
</div>