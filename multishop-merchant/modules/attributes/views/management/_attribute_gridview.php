<?php $this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    'viewOptionRoute'=>$viewOptionRoute,
    //'filter'=>$searchModel,
    'columns'=>array(
        array(
           'name'=>'obj_type',
           'value'=>'$data->getObjectTypeText()',
           'htmlOptions'=>array('style'=>'text-align:center;width:15%'),
           'type'=>'html',
         ),
        array(
           'name'=>'type',
           'value'=>'$data->getTypeText()',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
           'type'=>'html',
         ),
        array(
           'name'=>'code',
           'value'=>'$data->code',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
           'type'=>'html',
         ),
        array(
           'name'=>'name',
           'value'=>'$data->name',
           'htmlOptions'=>array('style'=>'text-align:center;'),
           'type'=>'html',
         ),
        array(
            'header'=>Sii::t('sii','Option Code - Name'),
            'value'=>'$data->getOptionsText()',
            'htmlOptions'=>array('style'=>'text-align:center;width:40%'),
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
                'update' => array(
                    'label'=>'<i class="fa fa-edit" title="'.Sii::t('sii','Update {object}',array('{object}'=>$searchModel->displayName())).'"></i>', 
                    'imageUrl'=>false,  
                    'visible'=>'$data->updatable()', 
                ),                                    
                'delete' => array(
                    'label'=>'<i class="fa fa-trash-o" title="'.Sii::t('sii','Delete {object}',array('{object}'=>$searchModel->displayName())).'"></i>', 
                    'imageUrl'=>false,  
                    'visible'=>'$data->deletable()', 
                ),                                    
            ),
            'template'=>'{view} {update} {delete}',
            'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
      ),
    ),
)); 