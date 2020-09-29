<?php
/* @var $this CategoryController */
/* @var $data Category */
?>
<div class="list-box float-image">
    <div class="image">
        <?php echo $data->getImageThumbnail(Image::VERSION_SMEDIUM);?>
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
                    'value'=>'<strong>'.Sii::t('sii','n<=1#Subcategory|n>1#Subcategories',array($data->subcategoryCount)).'</strong>'.
                            ($data->hasSubcategories()?Helper::htmlList($data->getSubcategoriesToArray(user()->getLocale()),array('style'=>'margin:0px;padding:5px 15px 0px;')):CHtml::encode($data->subcategoryCount)),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<strong>'.Sii::t('sii','n<=1#Product|n>1#Products',array($data->productCount)).'</strong>'.
                        ($data->hasProducts()?$this->widget($this->module->getClass('listview'), 
                                        array(
                                            'dataProvider'=> new CArrayDataProvider($data->products),
                                            'template'=>'{items}',
                                            'emptyText'=>'',
                                            'itemView'=>$this->module->getView('brands.productthumbnail'),
                                        ),true):CHtml::encode($data->productCount)),
                ),        
            ),
        )); 
    ?>
</div>