<?php $this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    'viewOptionRoute'=>$viewOptionRoute,
    'htmlOptions'=>array('data-description'=>$pageDescription,'data-view-option'=>$viewOption),
    //'filter'=>$searchModel,
    'columns'=>array(
        array(
            'class' =>$this->getModule()->getClass('imagecolumn'),
            'header'=>Shop::model()->getAttributeLabel('image'),
            'name' =>'imageOriginalUrl',
            'htmlOptions'=>array('style'=>'text-align:center;width:120px;'),
        ),
        array(
            'name' =>'name',
            'value'=>'$data->parseName(user()->getLocale())',
            'htmlOptions'=>array('style'=>'text-align:center;width:30%'),
            'type'=>'html',
        ),                 
        array(
            'name'=>'tagline',
            'value'=>'$data->displayLanguageValue(\'tagline\',user()->getLocale())',
            'htmlOptions'=>array('style'=>'text-align:center;width:25%'),
            'type'=>'html',
         ),
        array(
            'name'=>'create_time',
            'header'=>Sii::t('sii','Application Date'),
            'value'=>'$data->formatDateTime($data->create_time,true)',
            'htmlOptions'=>array('style'=>'text-align:center'),
            'type'=>'html',
         ),
        array(
            'name'=>'status',
            'value'=>'Helper::htmlColorText($data->getStatusText())',
            'htmlOptions'=>array('width'=>'10%','style'=>'text-align:center'),
            'type'=>'html',
        ),     
        array(
            'class'=>'CButtonColumn',
            'buttons'=> array (
                'view' => array(
                    'label'=>'<i class="fa fa-info-circle" title="'.Sii::t('sii','More Information').'"></i>', 
                    'imageUrl'=>false,  
                    'url'=>'$data->viewUrl', 
                ),                                    
                'update' => array(
                    'label'=>'<i class="fa fa-edit" title="'.Sii::t('sii','Update {object}',array('{object}'=>$searchModel->displayName())).'"></i>', 
                    'imageUrl'=>false,  
                    'visible'=>'$data->updatable()', 
                ),                                    
            ),
            'template'=>'{view} {update}',
            'htmlOptions'=>array('style'=>'text-align:center;width:7%'),
      ),
    ),
)); 