<div style="clear:both;">
    
    <?php echo CHtml::activeHiddenField($model,'obj_type'); ?>
    <?php echo CHtml::activeHiddenField($model,'obj_id'); ?>
    <?php echo CHtml::hiddenField('Inventory[product_code]',$model->source==null?'':$model->source->code); ?>

    <?php $attrSelectedMap = SActiveSession::get($this->stateVariable);  ?>
 
    <?php if ($model->source!=null): ?>
        <?php foreach ($model->source->attrs as $attr): ?>

            <?php $attrSelected = $attrSelectedMap==null?'':$attrSelectedMap->itemAt($attr->name); ?>
    
            <div class="column" style="margin:15px 10px 15px 0px;">
                <?php echo CHtml::label($attr->displayLanguageValue('name',user()->getLocale()),'',array('style'=>'margin-bottom:3px;','required'=>true)); ?>
                <?php echo CHtml::dropDownList('Inventory[Attribute]['.$attr->code.']',$attrSelected, $attr->getOptionsArray(user()->getLocale()), 
                                                array('class'=>'chzn-select-attr',
                                                  'prompt'=>'',
                                                  'data-placeholder'=>Sii::t('sii','Select {field}',array('{field}'=>$attr->displayLanguageValue('name',user()->getLocale()))),
                                                  'style'=>'width:240px;margin-top:3px;'
                                                 )); ?>
            </div>

        <?php endforeach; ?>
    <?php endif; ?>
    
    <div style="width:300px;clear:both;padding-top:20px" >
        <table id="sku-table" class="items">
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
                        <?php //echo CHtml::error($model,'sku'); ?>
                    </td>
                    <td style="text-align:center">
                        <?php echo CHtml::activeTextField($model,'quantity',array('size'=>10,'maxlength'=>11)); ?>
                        <?php //echo CHtml::error($model,'quantity'); ?>
                    </td>
                </tr>    
            </tbody>
        </table>
        
    </div>
    
</div>

