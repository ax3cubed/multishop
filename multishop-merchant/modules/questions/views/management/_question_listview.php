<?php
/* @var $this ManagementController */
/* @var $data Question */
?>
<div class="list-box">
    <span class="status">
        <?php echo Helper::htmlColorText($data->getStatusText(),false); ?>
        <?php echo Helper::htmlColorText($data->getTypeLabel(),false); ?>
    </span>
    <div class="image">
        <?php echo $data->getReferenceImage(); ?> 
    </div>
    <?php 
        $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data address-to'),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="data-element">{value}</div>',
                    'value'=>Sii::t('sii','To: ').CHtml::link(CHtml::encode($data->getReference()->displayLanguageValue('name',user()->getLocale())), $data->getReference()->viewUrl),
                ),
            ),
        ));
            
        $this->widget('common.widgets.SDetailView', array(
            'data'=>$data,
            'htmlOptions'=>array('class'=>'data content'),
            'attributes'=>array(
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>$this->renderPartial($this->module->getView('questiontemplate'),array(
                                    'title'=>Sii::t('sii','Q: '),
                                    'content'=>CHtml::link(Helper::purify($data->question),$this->getQuestionUrl($data)),
                                    'datetime'=>$data->formatDatetime($data->question_time,true),
                                    'cssClass'=>'question',
                                 ),true),
                ),         
                array(
                    'type'=>'raw',
                    'template'=>'<div class="element">{value}</div>',
                    'value'=>$this->renderPartial($this->module->getView('questiontemplate'),array(
                                    'title'=>Sii::t('sii','A: '),
                                    'content'=>Helper::purify($data->answer),
                                    'datetime'=>$data->formatDatetime($data->answer_time,true),
                                    'cssClass'=>'answer',
                                 ),true),
                    'visible'=>isset($data->answer),
                ),         
            ),
        )); 
    
    ?>    
</div>	