<?php
$this->widget('common.widgets.SDetailView', [
    'id'=>'seo_section',
    'data'=>$model,
    'attributes'=>[
        ['label'=>$model->getAttributeLabel('seoTitle'),'type'=>'raw','value'=>$model->getMetaTag('seoTitle')],
        ['label'=>$model->getAttributeLabel('seoDesc'),'type'=>'raw','value'=>$model->getMetaTag('seoDesc')],
        ['label'=>$model->getAttributeLabel('seoKeywords'),'type'=>'raw','value'=>$model->getMetaTag('seoKeywords')],
    ],
]);