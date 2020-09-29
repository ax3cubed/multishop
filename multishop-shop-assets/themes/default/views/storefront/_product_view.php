<?php
$this->renderPartial($this->getThemeView('_product_form'),[
    'model'=>$model,
    'cartForm'=>$cartForm,
    'likeForm'=>$likeForm,
    'commentForm'=>$commentForm,
    'modal'=>null,
]);

$this->widget('CTabView', [
    'tabs'=>$pages,
    'cssFile'=>false,
    'id'=>'productinfo',
    'htmlOptions'=>[
        'class'=>'pTab',
    ],
]);

if (isset($script)){
    Helper::registerJs($script,ShopPage::PRODUCT);
}  