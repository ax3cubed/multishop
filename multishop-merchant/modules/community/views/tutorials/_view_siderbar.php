<?php $this->widget('common.widgets.SDetailView', array(
    'id'=>'tutorial_page_sidecol',
    'data'=>$model,
    'attributes'=>array(
        array('type'=>'raw','value'=> $model->author->getAvatar(Image::VERSION_SMALL),'cssClass'=>'avatar'),
        array('value'=> $model->author->nickname,'cssClass'=>'author'),
        array('value'=> Helper::prettyDate($model->create_time),'cssClass'=>'datetime'),
        array('type'=>'raw','value'=>$this->renderPartial('_tutorial_like',array('likeForm'=>$this->getLikeForm($model)),true),'cssClass'=>'like-content'),
        array('name'=>'difficulty','value'=> $model->getDifficultyText(),'cssClass'=>'difficulty'),
        array('name'=>'tags','type'=>'raw','value'=>$model->hasTags()?Helper::htmlList($model->parseTags(),array('class'=>'tags-value')):Sii::t('sii','not set'),'cssClass'=>'tags-content'),
        //array('name'=>Sii::t('sii','Series'),'type'=>'raw','value'=>$model->hasSeries()?$this->getSeriesTags($model):Sii::t('sii','not set'),'cssClass'=>'series-content'),
    ),
));