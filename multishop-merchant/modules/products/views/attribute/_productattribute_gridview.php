 <?php 
    $this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    //'filter'=>$searchModel,
    'columns'=>array(
        array(
           'name'=>'code',
           'value'=>'$data->code',
           'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
           'type'=>'html',
         ),
        array(
           'name'=>'name',
           'value'=>'$data->displayLanguageValue(\'name\',user()->getLocale())',
           'htmlOptions'=>array('style'=>'text-align:center;width:33%'),
           'type'=>'html',
         ),
        array(
            'header'=>Sii::t('sii','Option'),
            'value'=>'$data->getOptionsText(user()->getLocale())',
            'htmlOptions'=>array('style'=>'text-align:center;width:40%'),
            'type'=>'html',
        ),
        array(
            'header'=>Sii::t('sii','Share'),
            'value'=>'$data->getShareText()',
            'htmlOptions'=>array('style'=>'text-align:center;width:10%'),
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
                    'url'=>'url(\'product/attribute/update/id/\'.$data->id)', 
                    'visible'=>'$data->product->updatable()', 
                ),                                    
                'delete' => array(
                    'label'=>'<i class="fa fa-trash-o" title="'.Sii::t('sii','Delete {object}',array('{object}'=>$searchModel->displayName())).'"></i>', 
                    'imageUrl'=>false,  
                    'url'=>'url(\'product/attribute/delete/id/\'.$data->id)', 
                    'click'=>'js:function(){if (!confirm("'.Sii::t('sii','Are you sure you want to delete attribute').' "+$(this).parent().parent().children(\':nth-child(2)\').text()+"?")) return false;}',  
                    'visible'=>'$data->product->deletable()', 
                ),                                    
            ),
            'template'=>'{view} {update} {delete}',
            'htmlOptions'=>array('style'=>'text-align:center;width:7%'),
      ),
    ),
));