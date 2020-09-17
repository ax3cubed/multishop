<?php $this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>'total-grid',
    'dataProvider' => $dataProvider,
    'template'=>'{items}',
    'columns'=>array(
            array(
               'name'=>Sii::t('sii','Purchase Date'),
               'value'=>'$data->formatDateTime($data->create_time,true)',
               'htmlOptions'=>array('style'=>'text-align:center;width:18%'),
             ),
            array(
               'name'=>Sii::t('sii','Purchaser'),
               'value'=>'$data->order->account->name',
               'htmlOptions'=>array('style'=>'text-align:center'),
             ),
            array(
               'header'=> Sii::t('sii','Payment Method'),
               'name'=>'payment_method',
               'value'=>'$data->getPaymentMethodName(user()->getLocale())',
               'htmlOptions'=>array('style'=>'text-align:center'),
               'type'=>'html',
            ),
            array(
               'name'=>'item_count',
               'htmlOptions'=>array('style'=>'text-align:center'),
            ),
            array(
               'name'=>'item_total',
               'value'=>'$data->formatCurrency($data->item_total)',
               'htmlOptions'=>array('style'=>'text-align:center'),
            ),
            array(
               'name'=>'item_shipping',
               'value'=>'$data->formatCurrency($data->getShippingTotal())',
               'htmlOptions'=>array('style'=>'text-align:center'),
            ),
            array(
               'name'=>'tax',
               'value'=>'$data->hasTax()?Helper::htmlList($data->getTaxDisplayText(user()->getLocale())):Sii::t(\'sii\',\'not set\')',
               'htmlOptions'=>array('style'=>'text-align:center'),
               'type'=>'raw',
            ),
            array(
               'name'=>'grand_total',
               'value'=>'$data->formatCurrency($data->grand_total)',
               'htmlOptions'=>array('style'=>'text-align:center'),
            ),
    ),
));