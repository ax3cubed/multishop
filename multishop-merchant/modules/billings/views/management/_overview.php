<div class="list-box">
<?php 
    $this->widget('common.widgets.SDetailView', [
        'data'=>isset($data)?$data:['plan'],
        'htmlOptions'=>['class'=>'detail-view'],
        'columns'=>[
            $this->getPlanOverviewContent($data),
            [
                [
                    'template'=>'<div class="{class} payment-method"><div class="button">{value}</div></div>',
                    'type'=>'raw',
                    'value'=>$this->renderPartial('_button',['url'=>url('subscription/update/shop/'.$data->shop_id),'caption'=>Sii::t('sii','Change Payment Card')],true),
                ],            
                [
                    'template'=>'<div class="{class} change"><div class="button">{value}</div></div>',
                    'type'=>'raw',
                    'value'=>$this->renderPartial('_button',['url'=>url('plans/subscription?shop='.$data->shop_id),'caption'=>Sii::t('sii','Change Plan')],true),
                    'visible'=>!$data->isPastdue,      
                ],            
                [
                    'template'=>'<div class="{class} cancel"><div class="button">{value}</div></div>',
                    'type'=>'raw',
                    'value'=>$this->renderPartial('_button',['url'=>url('plans/subscription/cancel',['shop'=>$data->shop_id]),'caption'=>Sii::t('sii','Cancel Plan')],true),
                    'visible'=>$data->isActive,
                ],           
            ],
        ],
    ]);
?>
</div>