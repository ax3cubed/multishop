<?php 
$this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>'items-grid',
    'dataProvider'=>$dataProvider,
    'filter'=>isset($searchModel)?$searchModel:null,
    'template'=>'{items}',
    'columns'=>array(
        array(
           'name'=>'id',
           'htmlOptions'=>array('style'=>'text-align:center;width:5%;'),
        ),
        array(
            'name' =>'name',
            'class' =>$this->getModule()->getClass('itemcolumn'),
            'label' => Sii::t('sii','Product'),
            'value' => '$data->getItemColumnData(user()->getLocale())',
        ),
        array(
           'name'=>'unit_price',
           'type'=>'raw',
           'value'=>'Yii::app()->controller->widget(Yii::app()->controller->module->getClass(\'listview\'), 
                        array(
                            \'dataProvider\'=> $data->getPriceInfoDataProvider(user()->getLocale()),
                            \'template\'=>\'{items}\',
                            \'itemView\'=>Yii::app()->controller->module->getView(\'items.keyvalue\'),
                            \'emptyText\'=>\'\',
                            \'htmlOptions\'=>array(\'class\'=>\'price-details\'),
                        ),true)',
           'htmlOptions'=>array('style'=>'text-align:center;width:9%;'),
        ),
        array(
           'name'=>'quantity',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%;'),
        ),
        array(
            'header'=>Sii::t('sii','Discount'),
            'type'=>'html',
            'value'=>'$data->formatCurrency($data->orderDiscount).$data->getOrderDiscountTag(user()->getLocale())',
            'htmlOptions'=>array('style'=>'text-align:center;width:6%;'),
        ),
        array(
            'header'=>Sii::t('sii','Tax'),
            'value'=>'$data->formatCurrency($data->taxPrice)',
            'htmlOptions'=>array('style'=>'text-align:center;width:9%;'),
        ),
        array(
            'name'=>'total_price',
            'value'=>'$data->formatCurrency($data->grandTotal)',
            'htmlOptions'=>array('style'=>'text-align:center;width:9%;'),
        ),
        array(
            'name' =>'shipping_id',
            'header' => Sii::t('sii','Shipping'),
            'value' => '$data->shipping->displayLanguageValue(\'name\',user()->getLocale())',
           'htmlOptions'=>array('style'=>'text-align:center;'),
        ),
        array(
            'name'=>'status',
            'value'=>'Helper::htmlColorText($data->getStatusText())',
            'htmlOptions'=>array('style'=>'text-align:center;width:8%;'),
            'type'=>'html',
            'visible'=>isset($statusColumnInvisible)?false:true,
        ),
        array(
            'class'=>'SButtonColumn',
            'buttons'=>SButtonColumn::getItemButtons(array(
                'view'=>true,
                'refund'=>'$data->refundable()',
                'process_item'=>'$data->shippable() && $data->oneStepWorkflow()',//this is for 1 step processing
                'ship'=>'$data->shippable() && $data->threeStepsWorkflow()',
                'pack'=>'$data->packable()',
                'pick'=>'$data->pickable()',
                'rollback'=>'$data->undoable()',
                'return'=>'$data->returnable()',
            )),
            'template'=>'{view} {process_item} {rollback} {pick} {pack} {ship} {refund} {return}',
            'htmlOptions'=>array('style'=>'text-align:center;width:7%;'),
        ),
    ),
)); 