<?php
$checkout = false;//Not yet checkout at this stage (cart quick view only)
if ($this->cart->isEmpty($model->id)){
    echo CHtml::tag('div',['class'=>'cart-empty'],$this->renderPartial('common.modules.carts.views.base._empty', ['shop'=>$model->id], true));
}
else {
    $this->widget('common.widgets.SListView', [
        'dataProvider'=> $this->getCartShippingDataProvider($checkout,$model->id),
        'template'=>'{items}',
        'itemView'=>'carts.views.management._cart_shipping_quickview',
        'viewData'=>[
            'checkout'=>$checkout,
            'shopModel'=>$model,
            'queryParams'=>isset($queryParams)?$queryParams:[],
        ],
        'htmlOptions'=>['class'=>'offcanvas-cart'],
    ]);
}
