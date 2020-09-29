<div class="promotion-heading-wrapper">
    <div class="heading">
        <?php echo Helper::htmlColorTag($model->getOfferTag(true),'#f23649',false);?>
        <?php echo $model->x_product->displayLanguageValue('name',user()->getLocale());?>
        <span class="validity"><?php echo $model->getValidityText();?></span>
    </div>
</div>    
<?php 

//Not showing this as already show one under the promoform below
//$this->renderPartial($this->getThemeView('_product_counter'),[
//    'likeForm'=>$likeForm,
//    'commentForm'=>$commentForm,
//    'showLikeButton'=>true,
//]);

$promoColumns = [
    [
        'image-column'=> [
            'image'=>$this->simagezoomerWidget(['imageOwner'=>$model->x_product],true),
            'cssClass'=>isset($model->y_product)?SPageLayout::WIDTH_33PERCENT:SPageLayout::WIDTH_50PERCENT,
            'visible'=>!isset($modal),
        ],
    ],
];        
//display product y if any
if (isset($model->y_product)){
    $promoColumns[] = [ 
        [
            'template'=>'<div class="{class}">{value}</div>',
            'type'=>'raw',
            'value'=>$this->renderPartial($this->getThemeView('_promo_product_y'),['campaign'=>$model],true).
                    $this->renderPartial($this->getThemeView('_product_counter'),[
                        'likeForm'=>$likeForm,
                        'commentForm'=>$commentForm,
                        'showLikeButton'=>true,
                    ],true)
        ]
    ];
}
$promoColumns[] = [
    [
        'template'=>'<div class="{class}">{value}</div>',
        'type'=>'raw',
        'cssClass'=>'promo-form',
        'value'=>$this->renderPartial($this->getThemeView('_promo_form'),[
            'model'=>$model,
            'cartForm'=>$cartForm,
            'likeForm'=>$likeForm,
            'commentForm'=>$commentForm,
            'modal'=>null],true),
    ],
];

$this->widget('common.widgets.SDetailView', [
    'id'=>'promo_view',
    'data'=>$model,
    'htmlOptions'=>['class'=>'detail-view'],
    'columns'=>$promoColumns,
]);

$this->widget('CTabView', ['tabs'=>$pages,'cssFile'=>false,'id'=>'campaigninfo','htmlOptions'=>['class'=>'pTab']]);
