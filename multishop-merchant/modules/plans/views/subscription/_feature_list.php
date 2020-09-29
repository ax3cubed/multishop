<?php 
$this->widget('common.widgets.SListView', array(
    'dataProvider'=>$this->getPlanItemDataProvider($data['items']),
    'template'=>'{items}',
    'itemView'=>'plans.views.subscription._feature',
));
