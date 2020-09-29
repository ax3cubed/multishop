<?php if ($model->hasSubForm()):?>
    <!-- Second form -->
    <div class="form settings-2">
        <?php $model->renderSubForm($this);?>
    </div>
<?php endif;?>
<!-- Main form -->
<div class="form settings" style="<?php echo $model->hasSubForm()!=false?'display:inline-block;':'';?>">
    
    <p class="note"><?php echo Sii::t('sii','Fields with <span class="required">*</span> are required.');?></p>

    <?php $model->renderActiveForm($this);?>
    
</div>
