<?php $this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    'viewOptionRoute'=>$viewOptionRoute,
    //'filter'=>$searchModel,
    'columns'=>array(
        array(
            'name'=>'name',
            'value'=>'$data->displayLanguageValue(\'name\',user()->getLocale())',
            'htmlOptions'=>array('style'=>'text-align:center'),
            'type'=>'html',
        ),
        array(
            'name'=>'country',
            'value'=>'SLocale::getCountries($data->country)',
            'htmlOptions'=>array('style'=>'width:50%;text-align:center'),
            'type'=>'html',
        ),
//        array(
//            'name'=>'state',
//            'htmlOptions'=>array('style'=>'width:20%;text-align:center'),
//            'type'=>'html',
//        ),
//        array(
//            'name'=>'city',
//            'htmlOptions'=>array('style'=>'width:20%;text-align:center'),
//            'type'=>'html',
//        ),
//        array(
//            'name'=>'postcode',
//            'htmlOptions'=>array('style'=>'width:10%;text-align:center'),
//            'type'=>'html',
//        ),
        /*'create_time',
        'update_time',
        */
        array(
            'class'=>'SButtonColumn',
            'buttons'=>SButtonColumn::getZoneButtons(array(
                'view'=>true,
                'update'=>'$data->updatable()',
                'delete'=>'$data->deletable()',
            )),            
            'template'=>'{view} {update} {delete}',
            'htmlOptions'=>array('width'=>'8%'),
        ),
    ),
)); 