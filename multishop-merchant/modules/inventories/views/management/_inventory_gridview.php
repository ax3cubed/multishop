<?php $this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    'viewOptionRoute'=>$viewOptionRoute,
    //'filter'=>$searchModel,
    'columns'=>array(
        array(
           'name'=>'shop_id',
           'value'=>'$data->shop->displayLanguageValue(\'name\',user()->getLocale())',
           'htmlOptions'=>array('style'=>'text-align:center;width:15%'),
           'type'=>'html',
           'visible'=>!$this->hasParentShop(),
         ),
        array(
            'name'=>'id',//use id as proxy for product name search
            'class' =>$this->getModule()->getClass('itemcolumn'),
            'label' =>Sii::t('sii','Product Name'),
            'value' =>'$data->source->getNameColumnData(user()->getLocale())',
        ),
        array(
           'name'=>'sku',
           'htmlOptions'=>array('style'=>'text-align:center;width:30%'),
           'type'=>'html',
         ),
        array(
           'name'=>'quantity',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
           'type'=>'html',
         ),
        array(
           'name'=>'available',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
           'type'=>'html',
         ),
        array(
           'name'=>'sold',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
           'type'=>'html',
         ),
        array(
            'class'=>'CButtonColumn',
            'buttons'=> array (
                'view' => array(
                    'label'=>'<i class="fa fa-info-circle" title="'.Sii::t('sii','More information').'"></i>', 
                    'imageUrl'=>false,  
                    'url'=>'$data->viewUrl', 
                ),
                'update' => array(
                    'label'=>'<i class="fa fa-edit" title="'.Sii::t('sii','Update {object}',array('{object}'=>$searchModel->displayName())).'"></i>', 
                    'imageUrl'=>false,  
                    'visible'=>'$data->updatable()', 
                ),                                    
                'delete' => array(
                    'label'=>'<i class="fa fa-trash-o" title="'.Sii::t('sii','Delete {object}',array('{object}'=>$searchModel->displayName())).'"></i>', 
                    'imageUrl'=>false,  
                    'visible'=>'$data->deletable()', 
                    'click'=>'js:function(){if (!confirm("'.Sii::t('sii','Are you sure you want to delete SKU').' "+$(this).parent().parent().children(\':nth-child(2)\').text()+"?")) return false;}',  
                ),                                    
            ),
            'template'=>'{view} {update} {delete}',
            'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
        ),
    ),
));