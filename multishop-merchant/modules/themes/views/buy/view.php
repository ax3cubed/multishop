<?php
$this->breadcrumbs=[
    Sii::t('sii','Themes')=>url('shop/themes'),
    $theme->displayLanguageValue('name',user()->getLocale()),
];

$this->menu=[];

if (!$shop->hasTheme($theme->theme))
    $extraSection = $this->renderPartial('_form_payment',['model'=>$this->getPaymentForm($theme,$shop)],true);
else
    $extraSection = $this->getFlashAsString('error',Sii::t('sii','You already installed this theme before.'),null);

        
$this->widget('common.widgets.spage.SPage',[
    'id'=>'theme_view_page',
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'  => get_class($theme),
    'heading'=> false,
    'body'=>$this->renderPartial('../portal/_view_body',['model'=>$theme,'extraSection'=>$extraSection],true),
]);

