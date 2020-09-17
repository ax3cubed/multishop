<?php
$this->widget('common.widgets.SDetailView', array(
    'data'=>$model,
    'columns'=>array(
        array(
            array('label'=>Sii::t('sii','Shop'),'value'=>$model->shop->displayLanguageValue('name',user()->getLocale())),
            array('label'=>Sii::t('sii','Purchase Date'),'value'=>$model->formatDatetime($model->create_time,true)),
            array('label'=>Sii::t('sii','Purchased By'),'value'=>$model->account->name),
            array('label'=>Sii::t('sii','Payment Method'),'value'=>$model->getPaymentMethodName(user()->getLocale())),
        ),
        array(
            array('label'=>Sii::t('sii','Total Purchased Items'),'value'=>$model->item_count),
            array('label'=>Sii::t('sii','Total Item Price'),'value'=>$model->formatCurrency($model->item_total)),
            array('label'=>Sii::t('sii','Discount'),'type'=>'raw','value'=>$this->getDiscountInfo($model),'visible'=>$this->hasDiscountInfo($model)
            ),
        ),
        array(
            array('label'=>Sii::t('sii','Tax'),'type'=>'raw','value'=>$model->getTaxDisplayString(user()->getLocale())),
            array('label'=>Sii::t('sii','Total Shipping Fee'),'type'=>'raw','value'=>$model->formatShippingTotal($model->shipping_total,user()->getLocale(),true)),
            array('label'=>Sii::t('sii','Grand Total'),'value'=>$model->formatCurrency($model->grand_total)),
            array('label'=>Sii::t('sii','Refund'),'value'=>$model->formatCurrency($model->refundTotal),'visible'=>$model->refundTotal>0),
        ),
    ),
    //'htmlOptions'=>array('class'=>'detail-view solid'),
));
