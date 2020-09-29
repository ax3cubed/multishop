<?php
$this->widget('common.widgets.SDetailView', array(
    'data'=>$model,
    'columns'=>array(
//        array(
//            array('label'=>Sii::t('sii','Expected Refund Item Amount'),'value'=>$model->formatCurrency($model->expectedRefundItemsTotal)),
//            array('label'=>Sii::t('sii','Expected Refund Shipping Fee'),'value'=>$model->formatCurrency($model->expectedRefundItemsShippingSurchargeTotal)),
//            array('label'=>Sii::t('sii','Expected Refund Total'),'value'=>$model->formatCurrency($model->expectedRefundAmount)),
//        ),
        array(
            array('label'=>Sii::t('sii','Actual Refund Total'),'value'=>$model->formatCurrency($model->actualRefundAmount)),
            array('label'=>Sii::t('sii','Refund Information'),'value'=>$model->refundSupportingInfo,'visible'=>!$model instanceof Order),
        ),
    ),
    'htmlOptions'=>array('class'=>'detail-view','style'=>'background:#ffe5e9;'),
));