<?php
$this->breadcrumbs=[
    Sii::t('sii','Shops')=>url('shops'),
    $form->shop->parseName(user()->getLocale())=>$form->shop->viewUrl,
    Sii::t('sii','Themes')=>url('shop/themes'),
    $form->getThemeName(user()->getLocale()),
];
$this->menu= [
    ['id'=>'preview','title'=>Sii::t('sii','Preview Theme'),'subscript'=>Sii::t('sii','preview'),
        'linkOptions'=>[
            'target'=>'_blank',
            'submit'=>$form->previewUrl,
        ]
    ],
];

$this->getPage([
    'id' => 'shop_update_page',
    'cssClass'=>'bootstrap-page',//to enable support of bootstrap
    'breadcrumbs'=>$this->breadcrumbs,
    'menu'=>$this->menu,
    'flash'=>get_class($form),
    'heading'=> [
        'name'=>$form->getThemeName(user()->getLocale()),
        'image'=> $form->shop->getImageThumbnail(Image::VERSION_ORIGINAL,['style'=>'width:'.Image::VERSION_XSMALL.'px;']),
        'subscript'=>$form->getStyleName(user()->getLocale()),
        'superscript'=>$form->getStatusText(),
    ],
    'description'=>$form->getDescription(),
    'body'=>$this->renderPartial('_update_body', ['model'=>$form],true),
]);
