<?php $this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    'htmlOptions'=>array('data-description'=>$pageDescription,'data-view-option'=>$viewOption),
    'viewOptionRoute'=>$viewOptionRoute,
    //'filter'=>$searchModel,
    'columns' => array(
        array(
            'name'=>'create_time',
            'value'=>'$data->formatDateTime($data->create_time,true)',
            'htmlOptions'=>array('style'=>'text-align:center;width:12%'),
        ),
        array(
            'name' =>'order_no',
            'value' => '$data->order_no',
            'htmlOptions'=>array('style'=>'text-align:center;width:9%'),
        ),
        array(
            'name' =>'name',
            'class' =>$this->getModule()->getClass('itemcolumn'),
            'label' => Sii::t('sii','Item Name'),
            'value' => '$data->getItemColumnData(user()->getLocale())',
        ),
        array(
            'name'=>'unit_price',
            'value'=>'$data->formatCurrency($data->unit_price,$data->currency)',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%'),
            'type'=>'html',
        ),
        array(
            'name' =>'quantity',
            'value' => '$data->quantity',
            'htmlOptions'=>array('style'=>'text-align:center;width:6%'),
            'type'=>'html',
        ),
        array(
            'name'=>'total_price',
            'value'=>'$data->formatCurrency($data->total_price,$data->currency)',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%'),
            'type'=>'html',
        ),
//        array(
//            'name' =>Sii::t('sii','Weight'),
//            'value' => '$data->total_weight',
//        ),
        array(
            'name'=>'status',
            'value'=>'Helper::htmlColorText($data->getStatusText())',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%'),
            'type'=>'html',
 //           'filter'=>true,
        ),
        array(
            'class'=>'SButtonColumn',
            'buttons'=>SButtonColumn::getItemButtons(array(
                'view'=>true,
                'refund'=>'$data->refundable()',
                'ship'=>'$data->shippable() && $data->threeStepsWorkflow()',
                'pack'=>'$data->packable()',
                'pick'=>'$data->pickable()',
                'return'=>'$data->returnable()',
                'process_item'=>'$data->processable() && $data->oneStepWorkflow()',//this is for 1 step processing
                'rollback'=>isset($customer)?'return false;':'$data->undoable()',
                'stockmanage'=>'$data->outOfStock()',                
            ),true),//customer mode
            'template'=>'{view} {process_item} {rollback} {pick} {pack} {ship} {return} {stockmanage} {refund}',
            'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
        ),
    ),
));