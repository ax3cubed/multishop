<tr id="option_<?php echo $model->id;?>">
    <td style="text-align:center">
        <?php echo CHtml::activeTextField($model,'name',array('name'=>'Attribute[Option]['.$model->id.'][name]','size'=>20,'maxlength'=>50)); ?>
        <?php echo CHtml::error($model,'name'); ?>
    </td>
    <td style="text-align:center">
        <?php echo CHtml::activeTextField($model,'code',array('name'=>'Attribute[Option]['.$model->id.'][code]','size'=>3,'maxlength'=>2)); ?>
        <?php echo CHtml::error($model,'code'); ?>
    </td>              
    <td style="text-align:center">
        <?php echo CHtml::activeHiddenField($model,'id',array('name'=>'Attribute[Option]['.$model->id.'][id]')); ?>
        <?php echo CHtml::activeHiddenField($model,'attr_id',array('name'=>'Attribute[Option]['.$model->id.'][attr_id]')); ?>
        <span class="del-button" style="display:none">
            <?php echo l(CHtml::image($this->getImage('cancel.png'),Sii::t('sii','Remove Option'),
                                    array('width'=>15,
                                        'style'=>'vertical-align:bottom;',
                                        'title'=>'Remove Option',
                                        'onclick'=>'javascript:removeoption(\'attributes/management\','.$model->id.');')));
            ?>
        </span>
    </td>         
</tr>