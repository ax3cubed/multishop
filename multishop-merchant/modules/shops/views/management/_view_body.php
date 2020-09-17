<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>$model,
    'columns'=>array(
        array(
            array('name'=>'category','value'=>$model->categoryName),
            array('name'=>'slug','type'=>'raw','value'=>$model->prototype()?Sii::t('sii','unset'):($model->getUrl(true).($model->online()?CHtml::link(Sii::t('sii','Go to shop'),$model->url,array('class'=>'shortcut-button rounded','target'=>'_blank')):''))),
            array('name'=>'tagline','value'=>$model->displayLanguageValue('tagline',user()->getLocale()),'visible'=>$model->operational()),
        ),
        array(
            array('name'=>'create_time','value'=>$model->formatDatetime($model->create_time,true)),
            array('name'=>'update_time','value'=>$model->formatDatetime($model->update_time,true),'visible'=>!$model->prototype()),
        ),
    ),
));