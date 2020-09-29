<div class="<?php echo isset($model->title)?'signup-form-wrapper rounded':'signup-merchant-form-wrapper';?>">

    <div class="form">
        
        <?php if (isset($model->title)):?>
            <div class="form-heading"><?php echo $model->title;?></div>
        <?php endif;?>

        <div class="row">
            <?php 
                $this->widget('zii.widgets.jui.CJuiButton',[
                    'name'=>'signup-button',
                    'buttonType'=>'button',
                    'caption'=>Sii::t('sii','Get started for free'),
//                    'caption'=>Sii::t('sii','Start {n} days free trial',['{n}'=>$model->trialDuration]),
                    'value'=>'btn',
                    'htmlOptions'=>['class'=>'form-input ui-button','style'=>'padding:0'],
                    'onclick'=>'js:function(){window.location.href=\''.Yii::app()->urlManager->createHostUrl('account/signup/merchant').'\';}',
                ]); 
            ?>
        </div>

    </div>
</div>