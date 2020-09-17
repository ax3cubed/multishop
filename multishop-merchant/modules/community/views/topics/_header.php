<div class="header-wrapper">
    <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-tags fa-stack-1x fa-inverse"></i>
    </span>
    <div class="header-name">
        <?php echo CHtml::tag('h1',[],Sii::t('sii','Topics'));?>
        <?php echo CHtml::tag('div',['class'=>'header-desc'],Sii::t('sii','Choose any specific topics that you are interested in and learn more.'));?>
    </div>
</div>
<?php
$this->renderPartial($this->module->getView('search'),['placeholder'=>Sii::t('sii','Search Topics')]);