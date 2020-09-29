<?php 
$this->widget('common.widgets.SDetailView', [
    'data'=>$model,
    'columns'=>[
        [
            ['name'=>'slug','type'=>'raw','value'=>$model->url.($model->online()?CHtml::link(Sii::t('sii','Go to product'),$model->url,['class'=>'shortcut-button rounded','target'=>'_blank']):'')],
        ],
    ],
]);
$this->widget('common.widgets.SDetailView', [
    'data'=>$model,
    'columns'=>[
        [
            'image-column'=>[
                'image'=>$this->simagezoomerWidget(['imageOwner'=>$model,'defaultVersion'=>Image::VERSION_SLARGE],true),
                //'image'=>$this->simageviewerWidget(['imageModel'=>$model,'imageName'=>$model->getLanguageValue('name',user()->getLocale()),'imageVersion'=>Image::VERSION_XMEDIUM),true],
                'cssClass'=>SPageLayout::WIDTH_33PERCENT,
            ],
        ],
        [
            ['name'=>'shop_id','value'=>$model->shop->displayLanguageValue('name',user()->getLocale())],
            ['name'=>'brand_id','type'=>'raw','value'=>$model->brand==null?Sii::t('sii','unset'):CHtml::link($model->brand->displayLanguageValue('name',user()->getLocale()),$model->brand->viewUrl)],
            ['name'=> ProductCategory::model()->displayName(Helper::PLURAL),'type'=>'raw','value'=>$model->hasCategories?Helper::htmlList($model->getCategoriesData(user()->getLocale()),['class'=>'product-categories-list']):Sii::t('sii','unset')],
            ['name'=>'code'],
            ['name'=>'unit_price','value'=>$model->formatCurrency($model->unit_price)],
            ['name'=>'weight','value'=>$model->formatWeight($model->weight)],
        ],
        [
            ['name'=>'create_time','value'=>$model->formatDatetime($model->create_time,true)],
            ['name'=>'update_time','value'=>$model->formatDatetime($model->update_time,true)],
        ],
    ],
]);

