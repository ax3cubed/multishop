<?php 
$columns = [
        $this->getPlanOverviewContent($model),
        [
//           ['name'=>'create_time','value'=>$model->shop->formatDatetime($model->create_time,true)],
           ['name'=>'update_time','value'=>$model->shop->formatDatetime($model->update_time,true)],
        ],
    ];
if ($model->hasReceipt){
    $columns[] = [
        [
            'template'=>'<div class="{class}"><div class="button">{value}</div></div>',
            'type'=>'raw',
            'value'=>$this->renderPartial('billings.views.management._button',['url'=>Receipt::viewUrl($model->receipt->receipt_no),'caption'=>Sii::t('sii','View Receipt')],true),
        ],          
    ];
}
                
$this->widget('common.widgets.SDetailView', [
    'data'=>$model,
    'columns'=>$columns,
]);

$this->widget('common.widgets.SDetailView', [
    'data'=>$model,
    'columns'=>[
        $this->getPlanDetails($model),
    ],
]);