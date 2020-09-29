<?php
$this->breadcrumbs=$breadcrumbs;
$this->menu=[];
    

$this->widget('common.widgets.spage.SPage', array(
    'breadcrumbs' => $this->breadcrumbs,
    'menu' => $this->menu,
    'flash' => $flashId,
    'heading' => array(
        'name'=> $heading,
    ),
    'body'=>Sii::t('sii','You have not subscribed to this service.'),
    'sidebars' => $sidebar,
));
