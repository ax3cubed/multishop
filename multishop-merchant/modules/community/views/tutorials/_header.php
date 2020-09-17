<div class="header-wrapper">
    <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-file-text-o fa-stack-1x fa-inverse"></i>
    </span>
    <div class="header-name width-p-62">
        <?php echo CHtml::tag('h1',[],Sii::t('sii','Tutorials'));?>
        <?php echo CHtml::tag('div',['class'=>'header-desc'],Sii::t('sii','We have many tutorials to help you get started, learn, and become experts. You can also make contributions to the community by writing your own.'));?>
    </div>
    <div class="header-button">
        <?php 
               $this->widget('zii.widgets.jui.CJuiButton',[
                        'name'=>'tutorial-button',
                        'buttonType'=>'button',
                        'caption'=>Sii::t('sii','Write a Tutorial'),
                        'onclick'=>new CJavaScriptExpression('function(){window.location.href="'.url('tutorials/management/write').'";}'),
                        'htmlOptions'=>['class'=>'button-input'],
                    ]);
        ?>
    </div>    
</div>

<?php
$this->renderPartial($this->module->getView('search'),['placeholder'=>Sii::t('sii','Search {object}',['{object}'=>Tutorial::model()->displayName()])]);