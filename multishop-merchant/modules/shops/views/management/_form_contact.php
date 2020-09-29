<div class="subform">  
    <div>
        <?php echo CHtml::activeLabelEx($model,'contact_person'); ?>
        <?php echo CHtml::activeTextField($model,'contact_person',array('size'=>60,'maxlength'=>32)); ?>
        <?php $this->stooltipWidget($model->getToolTip('contact_person')); ?>
        <?php echo Chtml::error($model,'contact_person'); ?>
    </div>

    <div>
        <?php echo CHtml::activeLabelEx($model,'contact_no'); ?>
        <?php echo CHtml::activeTextField($model,'contact_no',array('size'=>60,'maxlength'=>20)); ?>
        <?php $this->stooltipWidget($model->getToolTip('contact_no')); ?>
        <?php echo Chtml::error($model,'contact_no'); ?>
    </div>
    
    <div>
        <?php echo CHtml::activeLabelEx($model,'email'); ?>
        <?php echo CHtml::activeTextField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
        <?php $this->stooltipWidget($model->getToolTip('email')); ?>
        <?php echo Chtml::error($model,'email'); ?> 
    </div>
</div>        


