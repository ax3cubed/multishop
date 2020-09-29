<div class="form">
    <?php $form=$this->beginWidget('CActiveForm',[
                'id'=>'theme_payment_form',
            ]); 
    ?>

    <?php echo CHtml::activeHiddenField($model,'shop_id');?>
    <?php echo CHtml::activeHiddenField($model,'theme');?>

    <div class="row info">
        <div>
            <?php echo Sii::t('sii','Payment Method');?>
        </div>
        <?php $this->renderPartial('billings.views.payment._card',['model'=>$model]);?>
    </div>
    
    <div class="row info" >
        <span><?php echo Sii::t('sii','Total');?></span>
        <span><?php echo $model->currency.$model->amount;?></span>
    </div>
    
    <div class="row info">
        <?php 
            $this->widget('zii.widgets.jui.CJuiButton', [
                'name'=>'actionButton',
                'buttonType'=>'submit',
                'caption'=>Sii::t('sii','Confirm Purchase'),
                'value'=>'actionbtn',
                'htmlOptions'=>['class'=>'ui-button'],
            ]);
        ?>
    </div> 

    <?php echo $this->getFlashAsString('notice',Sii::t('sii','Please check if the card above is good for payment. If not, please {change}.',['{change}'=>CHtml::link(Sii::t('sii','change card'),url('billing/payment'))]),null);?>

    <?php $this->endWidget(); ?>
</div>