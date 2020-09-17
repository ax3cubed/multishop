<?php $this->widget($this->getModule()->getClass('gridview'), array(
    'id'=>$scope,
    'dataProvider'=>$this->getDataProvider($scope, $searchModel),
    'viewOptionRoute'=>$viewOptionRoute,
    'htmlOptions'=>array('data-description'=>$pageDescription,'data-view-option'=>$viewOption),
    //'filter'=>$searchModel,
    'columns'=>array(
        array(
            'name'=>'shop_id',
            'value'=>'$data->shop->displayLanguageValue(\'name\',user()->getLocale())',
            'htmlOptions'=>array('style'=>'width:10%;text-align:center'),
            'type'=>'html',
        ),
        array(
            'name' =>'name',
            'class' =>$this->getModule()->getClass('itemcolumn'),
            'label' => Sii::t('sii','Name'),
            'value' => '$data->getNameColumnData(user()->getLocale())',
            'type'=>'html',
            ),
        array(
            'name' =>'sale_value',
            'value'=>'$data->getCampaignText(user()->getLocale(),CampaignSale::TAG_SALE)',
            'type'=>'html',
            'htmlOptions'=>array('style'=>'width:20%;text-align:center'),
            ),
        array(
            'name' =>'offer_value',
            'value'=>'$data->getCampaignText(user()->getLocale(),CampaignSale::TAG_OFFER)',
            'type'=>'html',
            'htmlOptions'=>array('style'=>'width:20%;text-align:center'),
            ),
        array(
            'name'=>'status',
            'value'=>'Helper::htmlColorText($data->getStatusText())',
            'type'=>'html',
            'filter'=>false,
            'htmlOptions'=>array('style'=>'width:6%;text-align:center'),
        ),
        array(
            'class'=>'SButtonColumn',
            'buttons'=>SButtonColumn::getCampaignButtons(array(
                'view'=>true,
                'update'=>'$data->updatable()',
                'delete'=>'$data->deletable()',
            )),
            'template'=>'{view} {update} {delete}',
            'htmlOptions'=>array('style'=>'width:8%;text-align:center'),
        ),
    ),
));
