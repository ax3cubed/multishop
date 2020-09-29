<?php 
$this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>'purchaseorder_so_summary_grid',
    'dataProvider'=>$dataProvider,
    'template'=>'{items}',
    'columns'=>array(
//            array(
//                'class'=>'CLinkColumn',
//                'header'=>Sii::t('sii','Shipping No'),
//                'labelExpression'=>'$data->shipping_no',
//                'urlExpression'=>'$data->viewUrl',
//                'htmlOptions'=>array('style'=>'text-align:center;'),
//            ),
        array(
            'header'=>Sii::t('sii','Shipping Name'),
            'value'=>'$data->getShippingName(user()->getLocale())',
            'htmlOptions'=>array('style'=>'text-align:center'),
            'type'=>'html',
        ),
        array(
           'name'=>'item_total',
           'value'=>'$data->formatCurrency($data->item_total)',
           'htmlOptions'=>array('style'=>'text-align:center;width:15%;'),
           'type'=>'html',
        ),
        array(
           'name'=>'discount',
           'value'=>'$data->formatCurrency($data->getDiscountTotal())',
           'htmlOptions'=>array('style'=>'text-align:center;width:15%;'),
           'type'=>'raw',
        ),
        array(
           'name'=>'tax',
           'value'=>'$data->hasTax()?Helper::htmlList($data->getTaxDisplayText(user()->getLocale())):Sii::t(\'sii\',\'not set\')',
           'htmlOptions'=>array('style'=>'text-align:center;width:15%;'),
           'type'=>'raw',
        ),
        array(
           'name'=>'item_shipping',
           'value'=>'$data->formatCurrency($data->getShippingTotal())',
           'htmlOptions'=>array('style'=>'text-align:center;width:15%;'),
           'type'=>'html',
        ),
        array(
            'name'=>'grand_total',
           'value'=>'$data->formatCurrency($data->grand_total)',
            'htmlOptions'=>array('style'=>'text-align:center;width:15%;'),
            'type'=>'html',
        ),
    ),
)); 
