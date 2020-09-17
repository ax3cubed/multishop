<div class="header-wrapper">
    <span class="fa-stack fa-lg">
        <i class="fa fa-circle fa-stack-2x"></i>
        <i class="fa fa-search fa-stack-1x fa-inverse"></i>
    </span>
    <div class="header-name">
        <?php echo CHtml::tag('h1',[],Sii::t('sii','Search Results'));?>
        <?php echo CHtml::tag('div',['class'=>'header-desc'],'');?>
    </div>
</div>
<?php 
$this->renderPartial($this->module->getView('search'),['page'=>'portal','value'=>$query]);
