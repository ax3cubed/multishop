<?php
$this->widget('common.widgets.spage.SPage', [
    'id'=>'package_page',
    'flash' => ['Plan'],
    'heading' => false,
    'body'=>$this->renderPartial('plans.views.subscription._package_listview',['dataProvider'=>$this->getPublishedPackages(true),'shop'=>isset($shop)?$shop:null],true),
]);
