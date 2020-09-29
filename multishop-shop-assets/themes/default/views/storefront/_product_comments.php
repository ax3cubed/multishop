<?php $this->widget('common.widgets.SDetailView', array(
        'data'=>$dataProvider,
        'htmlOptions'=>array('class'=>'data'),
        'attributes'=>array(
            array(
                'type'=>'raw',
                'template'=>'<h2 class="comment-invite-message">{value}</h2>',
                'value'=>Sii::t('sii','Be The First One To Make A Comment'),
                'visible'=>$dataProvider->totalItemCount==0,
            ),
            array(
                'type'=>'raw',
                'template'=>'<h1 class="comment-total-message">{value}</h1>',
                'value'=>Sii::t('sii','1#{n} Comment|n>1#{n} Comments',array($dataProvider->totalItemCount)),
                'visible'=>$dataProvider->totalItemCount>0&&!isset($modal),
            ),
            array(
                'type'=>'raw',
                'template'=>'<h3 class="comment-login-message">{value}</h3>',
                'value'=>Sii::t('sii','You must be {loginlink} to post comment.',array('{loginlink}'=>CHtml::link(Sii::t('sii','logged in'),'javascript:void(0);',array('onclick'=>$commentForm->signInScript)))),
                'visible'=>user()->isGuest,
            ),
            array(
                'type'=>'raw',
                'template'=>'<div class="prevlink-comment">{value}</div>',
                'value'=>$this->renderPartial($this->getThemeView('_product_comment_prev'),array('commentForm'=>$commentForm,'route'=>$this->getShopRoute($page->shopModel,'prevdata')),true),
                'visible'=>$dataProvider->pagination->pageSize<$dataProvider->totalItemCount,
            ),
            array(
                'type'=>'raw',
                'template'=>'<div class="prevdata-comment"></div>',
            ),
            array(
                'type'=>'raw',
                'template'=>'{value}',
                'value'=>$this->renderPartial($this->getThemeView('_product_comment'),array('dataProvider'=>$dataProvider,'commentForm'=>$commentForm),true),
            ),
        ),
    )); 