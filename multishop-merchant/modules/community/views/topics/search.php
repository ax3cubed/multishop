<?php
$this->widget('common.widgets.spage.SPage',[
    'id'=>'topics_page',
    'cssClass'=>'search-results',
    'breadcrumbs'=>false,
    'menu'=>$this->menu,
    'layout' => false,
    'heading'=> $this->renderPartial('_header',[],true),
    'linebreak'=>false,
    'body'=> CHtml::tag('h1',['class'=>'search-heading'],Sii::t('sii','Filtered by topic "{topic}"',['{topic}'=>Sii::t('sii',Tag::getList(user()->getLocale())[$topic])]))
]);

$this->widget('CTabView', ['tabs'=>$this->getPages($topic),'cssFile'=>false,'id'=>'search_tabs','htmlOptions'=>['class'=>'cTab']]);
