<div class="list-box" id="tutorial_article">
    <?php $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data'),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="heading-element">{value}</div>',
                    'value'=>CHtml::link(CHtml::encode($data->title), url('community/questions/'.$data->slug)),
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>Helper::prettyDate($data->question_time).
                            '<span class="tag-label">'.Sii::t('sii','tagged in').'</span>'.
                             ($data->hasTags()?Helper::htmlList($data->parseTags(),array('class'=>'tags')):Sii::t('sii','not set')),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<span class="avatar">'.$data->account->getAvatar(Image::VERSION_XXXSMALL).'</span>'.
                             CHtml::encode($data->account->nickname),
                ),
            ),
        )); 
    ?> 
</div>
