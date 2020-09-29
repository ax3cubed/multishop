<?php
$this->widget('common.widgets.spage.SPage',[
    'id'=>'search_page',
    'breadcrumbs'=>false,
    'menu'=>$this->menu,
    'layout' => false,
    'heading'=>$this->renderPartial('_search_result_header',['query'=>$query],true),
    'linebreak'=>false,
    'body'=>$this->getSearchResults($response),
]);
