<tr>
    <td width="10%" style="text-align:center">
        <?php echo Helper::htmlColorText($model->shipping->getStatusText());?>
    </td>
    <td width="25%" style="text-align:center">
        <?php echo $model->shipping->displayLanguageValue('name',user()->getLocale());?>
    </td>
    <td width="15%" style="text-align:center">
        <?php echo $model->shipping->getMethodDesc();?>
    </td>
    <td width="35%" style="text-align:center">
        <?php echo $this->getShippingRateData($model->shipping);?>
    </td>         
    <td width="15%" style="text-align:center">
        <?php echo CHtml::activeHiddenField($model,'id',array('name'=>$model->formatAttributeName(null,'id'))); ?>
        <?php echo CHtml::activeHiddenField($model,'campaign_id',array('name'=>$model->formatAttributeName(null,'campaign_id'))); ?>
        <?php echo CHtml::activeHiddenField($model,'shipping_id',array('name'=>$model->formatAttributeName(null,'shipping_id'))); ?>
        <?php if ($this->hasParentShop()) echo $this->getParentShop()->getCurrency(); ?>
        <?php echo CHtml::activeTextField($model,'surcharge',array('name'=>$model->formatAttributeName(null,'surcharge'),'size'=>3,'maxlength'=>10,'class'=>$model->hasErrors($model->formatErrorName(null,'surcharge'))?'error':'')); ?>
        <?php echo CHtml::error($model,$model->formatErrorName(null,'surcharge')); ?>        
    </td>         
</tr>
