<?php $this->widget('common.widgets.SDetailView', array(
        'data'=>$dataProvider,
        'htmlOptions'=>array('class'=>'data'),
        'attributes'=>array(
            array(
                'type'=>'raw',
                'template'=>'<h1 class="question-total-message">{value}</h1>',
                'value'=>Sii::t('sii','1#{n} Question|n>1#{n} Questions',array($dataProvider->totalItemCount)),
                'visible'=>$dataProvider->totalItemCount>0&&!isset($modal),
            ),
            array(
                'type'=>'raw',
                'template'=>'<h3 class="question-login-message">{value}</h3>',
                'value'=>Sii::t('sii','You must be {loginlink} to ask question.',array('{loginlink}'=>CHtml::link(Sii::t('sii','logged in'),'javascript:void(0);',array('onclick'=>$questionForm->signInScript)))),
                'visible'=>user()->isGuest,
            ),
            array(
                'type'=>'raw',
                'template'=>'<div class="prevlink-question">{value}</div>',
                'value'=>$this->renderPartial($this->getThemeView('_product_question_prev'),array('questionForm'=>$questionForm,'route'=>$this->getShopRoute($page->shopModel,'prevdata')),true),
                'visible'=>$dataProvider->pagination->pageSize<$dataProvider->totalItemCount,
            ),
            array(
                'type'=>'raw',
                'template'=>'<div class="prevdata-question"></div>',
            ),
            array(
                'type'=>'raw',
                'template'=>'{value}',
                'value'=>$this->renderPartial($this->getThemeView('_product_question'),array('dataProvider'=>$dataProvider,'questionForm'=>$questionForm),true),
            ),
        ),
    )); 