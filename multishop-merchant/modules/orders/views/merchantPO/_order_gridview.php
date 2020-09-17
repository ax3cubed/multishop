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
           'htmlOptions'=>array('style'=>'text-align:center;width:15%'),
         ),
        array(
           'name'=>'order_no',
           'type'=>'html',
           'value'=>'CHtml::link($data->order_no,$data->viewUrl)',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
        ),
        array(
            'name'=>'id',//use id as proxy for item name search
            'class' =>$this->getModule()->getClass('itemcolumn'),
            'label' => Sii::t('sii','Products'),
            'value' => '$data->getItemColumnData()',
        ),
        array(
            'name'=>'grand_total',
            'value'=>'$data->formatCurrency($data->grand_total)',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%'),
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
            'class'=>'SButtonColumn',
            'buttons'=>SButtonColumn::getOrderButtons(array(
                'view'=>true,
                'verify'=>'$data->verifiable()',
            )),
            'template'=>'{view} {verify}',
            'htmlOptions'=>array('style'=>'text-align:center;width:6%'),
        ),
    ),
));
