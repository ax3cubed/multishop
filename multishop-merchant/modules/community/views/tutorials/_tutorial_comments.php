<?php $this->widget('common.widgets.SDetailView', array(
        'data'=>$dataProvider,
        'htmlOptions'=>array('class'=>'data'),
        'attributes'=>array(
            array(
                'type'=>'raw',
                'template'=>'<h2>{value}</h2>',
                'value'=>Sii::t('sii','Be The First One To Make A Comment'),
                'visible'=>$dataProvider->totalItemCount==0,
            ),
            array(
                'type'=>'raw',
                'template'=>'<h1 class="comment-total-message">{value}</h1>',
                'value'=>Sii::t('sii','1#{n} Comment|n>1#{n} Comments',array($dataProvider->totalItemCount)),
                'visible'=>$dataProvider->totalItemCount>0,
            ),
            array(
                'type'=>'raw',
                'template'=>'<h3>{value}</h3>',
                'value'=>Sii::t('sii','You must be logged in to post comment.'),
                'visible'=>user()->isGuest,
            ),
            array(
                'type'=>'raw',
                'template'=>'<div class="prevlink-comment">{value}</div>',
                'value'=>$this->renderPartial($this->module->getView('comments.commentprev'),array('commentForm'=>$commentForm,'message'=>$this->prevdataMessage,'route'=>$this->prevdataRoute),true),
                'visible'=>$dataProvider->pagination->pageSize<$dataProvider->totalItemCount,
            ),
            array(
                'type'=>'raw',
                'template'=>'<div class="prevdata-comment"></div>',
            ),
            array(
                'type'=>'raw',
                'template'=>'{value}',
                'value'=>$this->renderPartial('community.views.tutorials._tutorial_comment',array('dataProvider'=>$dataProvider,'commentForm'=>$commentForm),true),
            ),
        ),
    )); 