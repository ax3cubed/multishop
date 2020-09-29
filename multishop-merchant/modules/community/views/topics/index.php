<?php 
$this->widget('common.widgets.spage.SPage',[
    'id'=>'topics_page',
    'breadcrumbs'=>false,
    'menu'=>$this->menu,
    'layout' => false,
    'heading'=> $this->renderPartial('_header',[],true),
    'linebreak'=>false,
    'body'=> $this->renderPartial('_topics',['dataProvider'=>$dataProvider],true),
]);

$this->smodalWidget();

