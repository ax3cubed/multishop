<div class="list-box">
<?php $this->widget('common.widgets.SDetailView', [
        'data'=>$data,
        'htmlOptions'=>['class'=>'detail-view'],
        'columns'=>[
            $this->getCardQuickViewColumn($data),
            [
                [
                    'template'=>'<div class="{class} payment-method"><div class="button">{value}</div></div>',
                    'type'=>'raw',
                    'value'=>$this->renderPartial('billings.views.management._button',['data'=>$data['payload'],'url'=>url('billing/payment/change'),'caption'=>Sii::t('sii','Change Payment Card')],true),
                ],            
            ]
        ],
    ]);
?>
</div>
