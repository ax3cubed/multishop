<?php $this->widget($this->getModule()->getClass('gridview'), [
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    'viewOptionRoute'=>$viewOptionRoute,
    //'filter'=>$searchModel,
    'columns'=>[
        [
           'name'=>'payment_no',
           'htmlOptions'=>['style'=>'text-align:center;'],
           'type'=>'html',                        
        ],
        [
           'name'=>'create_time',
           'value'=>'$data->formatDatetime($data->create_time,true)',
           'htmlOptions'=>['style'=>'text-align:center;width:15%'],
           'type'=>'html',                        
        ],
        [
           'name'=>'type',
           'value'=>'$data->getTypeDesc()',
           'htmlOptions'=>['style'=>'text-align:center;'],
           'type'=>'html',                        
        ],
        [
            'class'=>'CLinkColumn',
            'header'=>Sii::t('sii','Reference No'),
            'labelExpression'=>'$data->reference_no',
            'urlExpression'=>'app()->controller->getReferenceUrl($data->reference_no)',
            'htmlOptions'=>['style'=>'text-align:center;width:12%'],
        ],
        [
           'name'=>'method',
           'value'=>'$data->getPaymentMethodName(user()->getLocale())',
           'htmlOptions'=>['style'=>'text-align:center;width:18%'],
           'type'=>'html',                        
        ],
        [
           'name'=>'amount',
           'value'=>'$data->formatCurrency($data->amount,$data->currency)',
           'htmlOptions'=>['style'=>'text-align:center;width:10%'],
           'type'=>'html',                        
        ],
        //[
        //   'name'=>'trace_no',
        //   'value'=>'$data->getTraceNo()',
        //   'htmlOptions'=>array('style'=>'text-align:center;'),
        //   'type'=>'html',
        //],
        [
            'class'=>'CButtonColumn',
            'buttons'=> [
                'view' => [
                    'label'=>'<i class="fa fa-info-circle" title="'.Sii::t('sii','More Information').'"></i>', 
                    'imageUrl'=>false,  
                    'url'=>'$data->viewUrl', 
                ],
            ],
            'template'=>'{view}',
            'htmlOptions'=> ['width'=>'8%'],
        ],
    ],
]);
