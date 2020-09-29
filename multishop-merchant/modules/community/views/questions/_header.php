<div class="header-wrapper">
    <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-question fa-stack-1x fa-inverse"></i>
    </span>
    <div class="header-name width-p-62">
        <?php echo CHtml::tag('h1',[],Sii::t('sii','Questions'));?>
        <?php echo CHtml::tag('div',['class'=>'header-desc'],Sii::t('sii','Ask questions and get reply from our active and friendly community, or help others by answering their questions.'));?>
    </div>
    <div class="header-button">
        <?php 
                $this->widget('zii.widgets.jui.CJuiButton',[
                            'name'=>'question-button',
                            'buttonType'=>'button',
                            'caption'=>Sii::t('sii','Ask a Question'),
                            'onclick'=>new CJavaScriptExpression('function(){window.location.href="'.url('question/ask').'";}'),
                            'htmlOptions'=>['class'=>'button-input'],
                        ]);    
        ?>
    </div>    
</div>

<?php
$this->renderPartial($this->module->getView('search'),['placeholder'=>Sii::t('sii','Search {object}',['{object}'=>Question::model()->displayName()])]);