<div class="row">
    <?php if (!$model->isFbPageLinked):?>
        <div>
            <p><?php echo Sii::t('sii','Boost more sales by making your shop accessible at Facebook page.');?></p>
        </div>
        <?php $this->widget('zii.widgets.jui.CJuiButton',array(
                    'name'=>'actionButtonForm2',
                    'buttonType'=>'button',
                    'caption'=>$model->getAttributeLabel('fbPageData'),
                    'value'=>'actionbtn',
                    'onclick'=>'js:function(){servicepostcheck("'.$formId.'");}',
                    'htmlOptions'=>['style'=>'background: #3b5998'],
                ));
        ?>
    <?php else: ?>
        <div>
            <p><?php echo Sii::t('sii','You have installed Facebook Shop.');?></p>
        </div>
        <?php $this->widget('zii.widgets.jui.CJuiButton',array(
                    'name'=>'actionButton',
                    'buttonType'=>'link',
                    'caption'=>Sii::t('sii','View Facebook Shop'),
                    'value'=>'actionbtn',
                    'url'=>$model->fbPageAppLink,
                    'htmlOptions'=>array('target'=>'_blank'),
                ));
        ?>
        <?php echo CHtml::hiddenField(get_class($model).'[fbPageData]',null); ?>
        <?php $this->widget('zii.widgets.jui.CJuiButton',array(
                    'name'=>'actionUninstallButton',
                    'buttonType'=>'button',
                    'caption'=>Sii::t('sii','Uninstall Facebook Shop'),
                    'value'=>'actionbtn',
                    'htmlOptions'=>array('style'=>'background:lightgray'),
                    'onclick'=>'js:function(){uninstallfbshop(\''.$formId.'\',\''.url('shop/settings/marketing').'\');}',
                ));
        ?>
    <?php endif; ?>
</div>
