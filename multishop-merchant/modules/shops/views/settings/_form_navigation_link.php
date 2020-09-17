<div class="nav-link-form">
    <div class="row field">
        <?php $model->renderForm($this); ?>
    </div>
    <div class="row field link">
        <?php echo CHtml::activeTextField($model,'link',['maxlength'=>500,'placeholder'=>Sii::t('sii','e.g. https://domain/any-menu-link (optional)')]); ?>
        <?php echo CHtml::error($model,'link'); ?>
    </div>
</div>