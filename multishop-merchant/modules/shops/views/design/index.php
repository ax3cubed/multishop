<?php
$this->breadcrumbs=[
    Sii::t('sii','Shops')=>url('shops'),
    $form->shop->parseName(user()->getLocale())=>$form->shop->viewUrl,
    Sii::t('sii','Themes'),
];
$this->menu=[];
$this->getPage([
    'id' => 'shop_update_page',
    'cssClass'=>'bootstrap-page',//to enable support of bootstrap
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'=>get_class($form),
    'heading'=> [
        'name'=>$this->viewName,
        'image'=> $form->shop->getImageThumbnail(Image::VERSION_ORIGINAL,['style'=>'width:'.Image::VERSION_XSMALL.'px;']),
    ],
    'description'=>Sii::t('sii','Decorate your shop with beautiful themes. Each theme comes with preset styles to help you setup shop fast. You can further customize themes at page level using our powerful page layout editor.'),
    'body'=>$this->renderPartial('_index_body', ['model'=>$form],true),
]);
