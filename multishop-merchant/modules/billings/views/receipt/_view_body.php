<?php 
echo $this->htmlItems($model);

if ($model->receipt_file!=null)
    $this->widget('common.widgets.SDetailView', array(
        'data'=>array('download'),
        'columns'=>array(
            array(
                array('label'=>SButtonColumn::getButtonIcon('download'),'type'=>'raw','value'=>$model->downloadLink),
            ),
        ),
    ));