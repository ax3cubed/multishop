<?php
$this->widget('common.widgets.SListView', [
    'id' => 'promotion_list',
    'dataProvider' => $this->dataProvider,
    'htmlOptions' => array('class'=>'promotion-container','data-total'=>$this->dataProvider->getTotalItemCount()),
    'itemView' => '_promoproduct',
    'template' => '{items}',
    'viewData'=>[
        'showActionBar'=>$this->showActionBar,
        'controller'=>$this->controller,
        'page'=>$this->page,
    ],
]);
