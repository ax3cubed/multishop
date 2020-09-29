<?php 
$this->widget('common.widgets.SDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        array('type'=>'raw','value'=> '<i class="fa fa-quote-left"></i>'.Helper::purify($model->question).'<i class="fa fa-quote-right"></i>'),
    ),
));

$this->widget('common.widgets.SDetailView', array(
    'data'=>$model,
    'columns'=>array(
        array(
            array('name'=>'tags','type'=>'raw','value'=>$model->hasTags()?Helper::htmlList($model->parseTags(),array('class'=>'tags')):Sii::t('sii','not set')),
        ),
        array(
            array('name'=>'account_id','type'=>'raw','value'=>$model->account->getAvatar(Image::VERSION_SMALL).' '.$model->account->name,'visible'=>user()->hasRole(Role::ADMINISTRATOR)),
            array('name'=>'question_time','value'=>$model->formatDatetime($model->question_time,true)),
            array('name'=>'answer_time','value'=>$model->formatDatetime($model->answer_time,true),'visible'=>$model->hasAnswer()),
        ),
    ),
));
