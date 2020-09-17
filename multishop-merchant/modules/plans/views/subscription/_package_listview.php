<?php
$this->widget('common.widgets.SListView', [
    'id'=>'plan_listview',
    'dataProvider'=>$dataProvider,
    'summaryText'=>false,
    'viewData'=>['shop'=>$shop,'total'=>$dataProvider->getItemCount()],
    'itemView'=>'plans.views.subscription._package_body',
]);