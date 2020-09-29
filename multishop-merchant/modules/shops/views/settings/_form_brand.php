<div class="subform">
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'customDomain',array('style'=>'margin-bottom:3px;')); ?>
        <?php if ($model->customDomain==null):?>
            <?php echo CHtml::activeTextField($model,'customDomain',array('style'=>'text-align:right','size'=>25,'maxlength'=>50,'placeholder'=>'your-shop-domain')); ?>
        <?php else:?>
            <?php echo CHtml::activeTextField($model,'customDomain',array('size'=>25,'maxlength'=>50,'disabled'=>true,'class'=>'disabled','style'=>'cursor:not-allowed')); ?>
            <?php echo CHtml::activeHiddenField($model,'customDomain'); ?>
        <?php endif;?>
        <span><?php echo resolveDomain(app()->urlManager->shopDomain);?></span>
        <?php $this->stooltipWidget($model->getToolTip('customDomain')); ?>
        <?php echo CHtml::error($model,'customDomain'); ?>
    </div>
    
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'myDomain',array('style'=>'margin-bottom:3px;')); ?>
        <?php echo CHtml::activeTextField($model,'myDomain',array('style'=>'text-align:right','size'=>50,'maxlength'=>500,'placeholder'=>'e.g. www.your-shop-domain.com')); ?>
        <?php $this->stooltipWidget($model->getToolTip('myDomain')); ?>
        <?php echo CHtml::error($model,'myDomain'); ?>
    </div>
</div>