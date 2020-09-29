<div class="header-wrapper tutorial-series">
    <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-files-o fa-stack-1x fa-inverse"></i>
    </span>
    <div class="header-name">
        <?php echo CHtml::tag('h1',[],Sii::t('sii','Tutorial Series'));?>
        <?php echo CHtml::tag('div',['class'=>'header-desc'],Sii::t('sii','We make short courses to help you get started even easier and faster.'));?>
    </div>
</div>
<?php

$this->renderPartial($this->module->getView('search'),['placeholder'=>Sii::t('sii','Search {object}',['{object}'=>TutorialSeries::model()->displayName()])]);