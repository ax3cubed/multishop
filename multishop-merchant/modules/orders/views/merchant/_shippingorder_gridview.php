<?php $this->widget($this->getModule()->getClass('groupview'), array(
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    'viewOptionRoute'=>$viewOptionRoute,
    'htmlOptions'=>array('data-description'=>$pageDescription,'data-view-option'=>$viewOption),
    //'filter'=>$searchModel,
    'mergeColumns' => array('order_no','shop_id'),  
    'afterAjaxUpdate'=>'function(id, data){ wrb(id); }',//refer to tasks.js, id is the above gridview id
    'columns'=>array(
        array(
           'name'=>'create_time',
           'value'=>'$data->formatDateTime($data->create_time,true)',
           'htmlOptions'=>array('style'=>'text-align:center;width:8%'),
         ),
        array(
           'name'=>'order_no',
           'type'=>'html',
           'value'=>'CHtml::link($data->order_no,$data->getPOViewUrl())',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
        ),
        array(
           'name'=>'shipping_no',
           'value'=>'$data->shipping_no',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
        ),
        array(
            'name'=>'id',//use id as proxy for item name search
            'class' =>$this->getModule()->getClass('itemcolumn'),
            'label' => Sii::t('sii','Products'),
            'value' => '$data->getItemColumnData()',
        ),
        array(
            'name'=>'item_shipping',
            'header'=> Sii::t('sii','Shipping Option'),
            'value'=>'$data->getShippingName(user()->getLocale())',
            'htmlOptions'=>array('style'=>'text-align:center;width:15%;'),
            'type'=>'html',
        ),
        array(
            'name'=>'grand_total',
            'value'=>'$data->formatCurrency($data->grand_total)',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%'),
            'type'=>'html',
        ),
        array(
            'name'=>'shop_id',//use shop_id as proxy for item name search
            'header'=>Sii::t('sii','Order Total'),
            'value'=>'$data->formatCurrency($data->getOrderTotal())',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%'),
            'type'=>'html',
            'filter'=>false,
        ),
        array(
            'name'=>'status',
            'value'=>'Helper::htmlColorText($data->getStatusText())',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%'),
            'type'=>'html',
            'filter'=>false,
        ),     
        array(
            'class'=>'SButtonColumn',
            'buttons'=>SButtonColumn::getOrderButtons(array(
                'view'=>true,
                'process'=>'$data->processable()',
                'verify'=>'$data->verifiable()',
                'refund'=>'$data->orderCancelled()',
            )),
            'template'=>'{view} {verify} {process} {refund}',
            'htmlOptions'=>array('style'=>'text-align:center;width:6%'),
        ),
    ),
));
