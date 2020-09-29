<?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'plan_form',
        'action'=>$button['actionUrl'],
)); ?>

    <?php 
        if ($button['freeTrial'])
            echo CHtml::hiddenField('Package_id', $button['freeTrialId']); 
        else
            echo $form->hiddenField($package,'id'); 
        
        if ($subscription instanceof Subscription){
            echo $form->hiddenField($subscription,'shop_id'); 
        }
        
//Not using CJuiButton, as this widget is shared with pricing page (merchant portal) which uses bootstrap <- having conflict
//        $this->widget('zii.widgets.jui.CJuiButton',
//            array(
//                'name'=>'actionButton'.$model->id,
//                'caption'=> $button['caption'],
//                'value'=>'actionbtn',
//                'htmlOptions'=>['class'=>'ui-button'],
//            ));
    ?>

    <input class="ui-button ui-widget ui-state-default ui-corner-all" id="actionButton30" name="actionButton<?php echo $package->id;?>" type="submit" value="<?php echo $button['caption'];?>" role="button">

<?php $this->endWidget();?>