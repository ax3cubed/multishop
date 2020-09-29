<thead>
    <tr>
        <th>
            <?php echo $model->getAttributeLabel('sku');?>
        </th>        
        <th>
            <?php echo $model->getAttributeLabel('quantity');?>
        </th>
    </tr>
</thead>
<tbody>
    <tr class="odd">
        <td style="text-align:center">
            <?php echo CHtml::activeTextField($model,'sku',array('size'=>30,'maxlength'=>30)); ?>
            <?php echo CHtml::error($model,'sku'); ?>
        </td>
        <td style="text-align:center">
            <?php echo CHtml::activeTextField($model,'quantity',array('size'=>10,'maxlength'=>11)); ?>
            <?php echo CHtml::error($model,'quantity'); ?>
        </td>
    </tr>    
</tbody>
