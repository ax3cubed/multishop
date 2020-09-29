<div class="list-box" id="tutorial_article">
    <span class="status">
        <?php echo Helper::htmlColorText($data->getDifficultyTag(),false); ?>
    </span>
    <?php $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data'),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="heading-element">{value}</div>',
                    'value'=>CHtml::link(CHtml::encode($data->localeName(user()->getLocale())), $data->url),
                ),
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<span class="series-label">'.Sii::t('sii','Collected in series').'</span>'.
                             Helper::htmlList($data->parseSeries(user()->getLocale()),array('class'=>'tags series')),
                    'visible'=>$data->hasSeries(),
                ),                        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>Helper::prettyDate($data->create_time).
                            ($data->hasTags()?'<span class="tag-label">'.Sii::t('sii','tagged in').'</span>'.Helper::htmlList($data->parseTags(),array('class'=>'tags')):''),
                ),        
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>'<span class="avatar">'.$data->author->getAvatar(Image::VERSION_XXXSMALL).'</span>'.
                             CHtml::encode($data->author->nickname),
                ),
            ),
        )); 
    ?> 
</div>
