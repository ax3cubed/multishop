<?php $this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    'viewOptionRoute'=>$viewOptionRoute,
    'htmlOptions'=>array('data-description'=>$pageDescription,'data-view-option'=>$viewOption),
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
            'name'=>'code',
            'value'=>'$data->code',
            'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
            'type'=>'html',
        ),            
        array(
            'name' =>'name',
            'class' =>$this->getModule()->getClass('itemcolumn'),
            'label' => Sii::t('sii','Name'),
            'value' => '$data->getNameColumnData(user()->getLocale())',
        ),
        array(
            'name' => ProductCategory::model()->getAttributeLabel('category_id'),
            'value' => 'Helper::htmlList($data->getCategoriesData(user()->getLocale()))',
            'type'=>'raw',
        ),
        array(
            'name'=>'unit_price',
            'value'=>'$data->formatCurrency($data->unit_price)',
            'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
            'type'=>'html',
        ),
        array(
            'name'=>'status',
            'value'=>'Helper::htmlColorText($data->getStatusText())',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%'),
            'type'=>'html',
            'filter'=>false,
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
                    'url'=>'url(\'product/management/update\',array(\'id\'=>$data->id))', 
                    'visible'=>'$data->updatable()', 
                ),
                'delete' => array(
                    'label'=>'<i class="fa fa-trash-o" title="'.Sii::t('sii','Delete {object}',array('{object}'=>$searchModel->displayName())).'"></i>', 
                    'imageUrl'=>false,  
                    'url'=>'url(\'product/management/delete\',array(\'id\'=>$data->id))', 
                    'click'=>'js:function(){if (!confirm("'.Sii::t('sii','Are you sure you want to delete').' "+$(this).parent().parent().children(\':nth-child(1)\').text()+"?")) return false;}',  
                    'visible'=>'$data->deletable()',
                ),
             ),
            'template'=>'{view} {update} {delete}',
            'htmlOptions' => array('style'=>'width:8%;'),
        ),
    ),    
)); 