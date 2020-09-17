<?php
$this->widget('common.widgets.SDetailView', array(
    'data'=>$data,
    'attributes'=>array(
        array('label'=>Sii::t('sii','Usual Price'),
              'value'=>$data['usual_price']
        ),
        array('label'=>Sii::t('sii','Offer Price'),
              'value'=>$data['offer_price'],
              'cssClass'=>'offer-price'
        ),
    ),
    'htmlOptions'=>array('class'=>'detail-view'),
));