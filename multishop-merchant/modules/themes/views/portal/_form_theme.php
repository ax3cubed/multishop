<div class="button-form">
    
    <div>
        <?php echo CHtml::link(Sii::t('sii','Start with this theme'),$this->getAccessUrl(),['class'=>'ui-button']); ?>
    </div>
    
    <div class="demo-link">
        <?php echo CHtml::link('<i class="fa fa-tv"></i> '.Sii::t('sii','View Demo'),$this->getAccessUrl()); ?>
    </div>
    
</div>
