<?php 
$this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>'shipping_products_grid',
    'dataProvider'=>$model->searchProductShippings(),
    'columns'=>array(
            array(
               'name'=>'status',
               'value'=>'Helper::htmlColorText($data->product->getStatusText())',
               'htmlOptions'=>array('style'=>'text-align:center;width:20%'),
               'type'=>'html',
             ),
            array(
                'class'=>'CLinkColumn',
                'header'=>Sii::t('sii','Name'),
                'labelExpression'=>'$data->product->displayLanguageValue(\'name\',user()->getLocale())',
                'urlExpression'=>'$data->product->viewUrl',
                'htmlOptions'=>array('style'=>'text-align:center;'),
            ),
    ),
)); 

//reset pagination url
SPageSection::loadPaginationUrlScript('shippings/management/products', $model->id, 'shipping_products_grid', 'ProductShipping');

