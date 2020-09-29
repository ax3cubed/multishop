<?php
$this->widget('common.widgets.SDetailView', [
    'data'=>$callout,
    'htmlOptions'=>['class'=>'callout '.(isset($callout['cssClass'])?$callout['cssClass']:'')],
    'attributes'=>array(
        [
            'type'=>'raw',
            'template'=>'<div class="element">{value}</div>',
            'value'=>$callout['icon'],
        ],
        [
            'type'=>'raw',
            'template'=>'<div class="element"><h3>{value}</h3></div>',
            'value'=>$callout['heading'],
        ],
        [
            'type'=>'raw',
            'template'=>'<div class="element"><p>{value}</p></div>',
            'value'=>isset($callout['description'])?$callout['description']:'',
            'visible'=>isset($callout['description']),
        ],
        [
            'type'=>'raw',
            'template'=>'<div class="element">{value}</p></div>',
            'value'=>isset($callout['link'])?$callout['link']:'',
            'visible'=>isset($callout['link']),
        ],
    ),
]); 