<?php 
    $this->widget($this->getModule()->getClass('gridview'), array(
        'id'=>'attribute_usage_grid',
        'dataProvider'=>$dataProvider,
        'columns'=>array(
            array(
                'class' =>'common.widgets.SItemColumn',
                'label' => Sii::t('sii','Product Name'),
                'value' => '$data->product->getNameColumnData()',
            ),                                    
            array(
               'header'=>Sii::t('sii','Product Code'),
               'value'=>'$data->product->code',
               'htmlOptions'=>array('style'=>'text-align:center;width:20%'),
               'type'=>'html',
             ),
            array(
               'header'=>Sii::t('sii','Product Unit Price'),
               'value'=>'$data->product->formatCurrency($data->product->unit_price)',
               'htmlOptions'=>array('style'=>'text-align:center;width:20%'),
               'type'=>'html',
             ),
        ),
    )); 