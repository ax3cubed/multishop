<?php
$this->widget('common.widgets.spage.SPage',[
    'id'=>'signup_page',
    'heading'=> false,
    'body'=>$this->renderPartial('_form',['model'=>$model],true),
]);
