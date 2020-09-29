<div class="subform">  
    <div>
        <?php echo CHtml::activeLabelEx($model,'name'); ?>
        <?php echo CHtml::activeTextField($model,'name',array('size'=>60,'maxlength'=>50)); ?>
        <?php $this->stooltipWidget($model->getToolTip('name')); ?>
        <?php //echo Chtml::error($model,'name'); ?>
    </div>

    <div>
        <?php echo CHtml::activeLabelEx($model,'slug'); ?>
        <?php echo $model->baseUrl.'/'; ?>
        <?php echo CHtml::activeTextField($model,'slug',array('size'=>40,'maxlength'=>50)); ?>
        <?php $this->stooltipWidget($model->getToolTip('slug')); ?>
        <?php //echo Chtml::error($model,'slug'); ?>
    </div>
    
</div>  
