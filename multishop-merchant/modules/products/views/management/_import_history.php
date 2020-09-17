<?php $this->widget($this->getModule()->getClass('gridview'), array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        array(
            'header'=>ProductImport::model()->getAttributeLabel('uploaded_file'),
            'value'=>'$data->attachment->name',
            'htmlOptions'=>array('style'=>'text-align:center;'),
            'type'=>'html',
        ),
        array(
            'header'=>ProductImport::model()->getAttributeLabel('total_count'),
            'value'=>'$data->count',
            'htmlOptions'=>array('style'=>'text-align:center;'),
            'type'=>'html',
        ),            
        array(
            'name'=>'create_time',
            'value'=>'$data->formatDateTime($data->create_time,true)',
            'htmlOptions'=>array('style'=>'text-align:center'),
            'type'=>'html',
        ),
        array(
            'class'=>'CButtonColumn',
            'buttons'=> array (
                'view' => array(
                    'label'=>'<i class="fa fa-info-circle" title="'.Sii::t('sii','More information').'"></i>', 
                    'imageUrl'=>false,  
                    'url'=>'$data->viewUrl', 
                ),
             ),
            'template'=>'{view}',
            'htmlOptions' => array('style'=>'width:6%;'),
        ),
        
    ),    
)); 