<span style="float:left;margin-right:10px;">
    <?php echo $model->getImageThumbnail(Image::VERSION_SMALL,array('id'=>'product-img-'.$model->id,'width'=>80,'height'=>80)); ?>
</span>
<span class="tag" style="float:right">
    <?php echo Helper::htmlColorText($model->getStatusText());?>
</span>
<?php
$this->widget('common.widgets.SDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        array('name'=>'unit_price',
              'value'=>$model->formatCurrency($model->unit_price)
        ),
    ),
    'htmlOptions'=>array('class'=>'detail-view'),
));