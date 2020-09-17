<div class="css-form">
    <div class="form settings">
        <div class="row">
            <?php //echo CHtml::activeLabelEx($model,'css',[]); ?>
            <?php echo CHtml::activeTextArea($model,'css',['rows'=>5,'placeholder'=>Sii::t('sii','Paste your custom CSS script here')]); ?>
            <?php echo CHtml::error($model,'css'); ?>
        </div>    
    </div>
    <?php 
        $this->widget('common.widgets.SDetailView', [
            'data'=>$model,
            'htmlOptions'=>['class'=>'css-disclaimer'],
            'columns'=>[
                [
                    ['label'=>Sii::t('sii','Disclaimer'),'value'=>$model->disclaimer],
                ],
            ],
        ]);
    ?>
</div>