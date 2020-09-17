<?php cs()->registerScript('chosen-allow','$(\'.chzn-select-allow\').chosen();$(\'.chzn-search\').hide();',CClientScript::POS_END);?>
<div class="subform">
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'guestCheckout',array('style'=>';margin-bottom:3px;')); ?>
        <?php echo CHtml::activeDropDownList($model,'guestCheckout', 
                                        Helper::getBooleanValues(), 
                                        array('prompt'=>'',
                                              'class'=>'chzn-select-allow',
                                              'data-placeholder'=>Sii::t('sii','Select Mode'),
                                              'style'=>'width:80px;'));
        ?>
        <?php echo CHtml::error($model,'guestCheckout'); ?>
    </div>
    <div class="row" style="margin-top: 15px;">
        <?php echo CHtml::activeLabelEx($model,'checkoutQtyLimit',array('style'=>'margin-bottom:3px;')); ?>
        <?php echo CHtml::activeTextField($model,'checkoutQtyLimit',array('size'=>4,'maxlength'=>2)); ?>
        <?php echo CHtml::error($model,'checkoutQtyLimit'); ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'cartItemsLimit',array('style'=>'margin-bottom:3px;')); ?>
        <?php echo CHtml::activeTextField($model,'cartItemsLimit',array('size'=>4,'maxlength'=>2)); ?>
        <?php echo CHtml::error($model,'cartItemsLimit'); ?>
    </div>
    <div class="row">
        <?php echo CHtml::activeLabelEx($model,'allowReturn',array('style'=>'margin-bottom:3px;')); ?>
        <?php echo CHtml::activeDropDownList($model,'allowReturn', 
                                        Helper::getBooleanValues(), 
                                        array('prompt'=>'',
                                              'class'=>'chzn-select-allow',
                                              'data-placeholder'=>Sii::t('sii','Select Mode'),
                                              'style'=>'width:80px;'));
        ?>
        <?php echo CHtml::error($model,'allowReturn'); ?>
    </div>
    <div class="row" style="margin-top: 15px;">
        <?php echo CHtml::activeLabelEx($model,'registerToViewMore',array('style'=>'margin-bottom:3px;')); ?>
        <?php echo CHtml::activeDropDownList($model,'registerToViewMore', 
                                        Helper::getBooleanValues(), 
                                        array('prompt'=>'',
                                              'class'=>'chzn-select-allow',
                                              'data-placeholder'=>Sii::t('sii','Select Mode'),
                                              'style'=>'width:80px;'));
        ?>
        <?php echo CHtml::error($model,'registerToViewMore'); ?>
    </div>
    <div class="row" style="margin-top: 15px;">
        <?php echo CHtml::activeLabelEx($model,'productOverlayView',array('style'=>'margin-bottom:3px;')); ?>
        <?php echo CHtml::activeDropDownList($model,'productOverlayView', 
                                        Helper::getBooleanValues(), 
                                        array('prompt'=>'',
                                              'class'=>'chzn-select-allow',
                                              'data-placeholder'=>Sii::t('sii','Select Mode'),
                                              'style'=>'width:80px;'));
        ?>
        <?php echo CHtml::error($model,'productOverlayView'); ?>
    </div>
</div>