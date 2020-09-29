<?php $this->widget('common.widgets.SDetailView', array(
    'id'=>'question_page_sidecol',
    'data'=>$model,
    'attributes'=>array(
        array('type'=>'raw','value'=> $model->account->getAvatar(Image::VERSION_SMALL),'cssClass'=>'avatar'),
        array('value'=> $model->account->nickname,'cssClass'=>'author'),
        array('value'=> Helper::prettyDate($model->question_time),'cssClass'=>'datetime'),
        array('type'=>'raw','value'=>$this->renderPartial('_question_like',array('likeForm'=>$this->getLikeForm($model)),true),'cssClass'=>'like-content'),
        array('name'=>'tags','type'=>'raw','value'=>$model->hasTags()?Helper::htmlList($model->parseTags(),array('class'=>'tags-value')):Sii::t('sii','not set'),'cssClass'=>'tags-content'),
    ),
));