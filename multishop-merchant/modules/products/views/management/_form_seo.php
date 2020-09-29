<div class="meta-tags" style="margin-bottom:10px;margin-left:15px;">
    <?php echo CHtml::activeLabelEx($model,'seoTitle'); ?>
    <?php $this->stooltipWidget($model->getToolTip('seoTitle')); ?>
    <p>
        <?php echo CHtml::activeTextArea($model,'seoTitle',['maxlength'=>ProductForm::$pageTitleLength,'rows'=>3]); ?>
        <?php echo CHtml::error($model,'seoTitle'); ?>
    </p>
</div>
<div class="meta-tags" style="margin-bottom:10px;margin-left:15px;">
    <?php echo CHtml::activeLabelEx($model,'seoDesc'); ?>
    <?php $this->stooltipWidget($model->getToolTip('seoDesc')); ?>
    <p>
        <?php echo CHtml::activeTextArea($model,'seoDesc',['maxlength'=>ProductForm::$metaDescLength,'rows'=>3]); ?>
        <?php echo CHtml::error($model,'seoDesc'); ?>
    </p>
</div>
<div class="meta-tags" style="margin-bottom:10px;margin-left:15px;">
    <?php echo CHtml::activeLabelEx($model,'seoKeywords'); ?>
    <?php $this->stooltipWidget($model->getToolTip('seoKeywords')); ?>
    <p>
        <?php echo CHtml::activeTextArea($model,'seoKeywords',['maxlength'=>ProductForm::$metaKeywordsLength,'rows'=>3]); ?>
        <?php echo CHtml::error($model,'seoKeywords'); ?>
    </p>
</div>