<?php 
$this->widget('zii.widgets.CBreadcrumbs', [
    'htmlOptions'=>['id'=>'shop-breadcrumbs','class'=>'breadcrumbs'],
    'links'=>$links,
    'homeLink'=>CHtml::link(ShopPage::getTitle(ShopPage::HOME), $homeUrl),
    'separator'=>' / ',
]);